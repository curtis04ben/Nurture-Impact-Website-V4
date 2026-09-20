# Nurture Impact Website

This README explains how the site is built, how to run it locally (with or
without Docker), and how to deploy and maintain it — both as a Docker
container for development/review, and conventionally on Blacknight shared
hosting for production. It's written for someone relatively new to web
development.

---

## 1. What's in this project

```
/
├── index.php                  Homepage (dynamic — shows latest published projects & articles)
├── index.html                  Old URL — redirects to index.php
├── about.html                   About Alan / Nurture Impact
├── services.html                 All services overview
├── governance.html                Governance & Board Development
├── grant-applications.html         Funding & Grant Applications
├── events-workshops.html            Events & Workshops
├── events.html                       Old URL — redirects to events-workshops.html
├── projects.php                       Projects list (dynamic, reads /data/projects.json)
├── project.php                         Single project page (?slug=...)
├── insights.php                         Insights/Articles list (dynamic, reads /data/articles.json)
├── article.php                           Single article page (?slug=...), with LinkedIn share
├── contact-handler.php                    Contact form submission handler (sends email)
├── styles.css                              One shared stylesheet for the whole site
├── favicon.ico, assets/favicon-*.png        Browser tab icons
├── assets/js/contact-form.js                 Contact form AJAX + success/error banner
├── .htaccess                                  Pretty URLs + security rules (see below)
├── includes/
│   ├── content.php                             Read/write the JSON content store
│   ├── mailer.php                               Contact-form email building + sending
│   └── partials/nav.php, footer.php              Shared header/footer for the .php pages
├── data/
│   ├── projects.json                              Source of truth for Projects
│   ├── articles.json                               Source of truth for Insights
│   └── admin-credentials.json                       Created automatically — admin login (not shipped)
├── backend/
│   └── config.php                                   Site constants (paths, contact email, mail transport)
├── admin/                                             Password-protected content editor (see §7)
├── assets/                                             Images, incl. assets/uploads/ for admin-uploaded images
├── Dockerfile, docker-compose.yml, .dockerignore        Docker packaging (see §4–6)
└── docker/docker-entrypoint.sh                           Seeds/persists data inside the container
```

Most of the site is still plain HTML/CSS — Home, About, Services, Governance
and Grant Applications are readable files you can edit directly. Projects,
Insights and the homepage's "latest content" sections are PHP-powered
because they need to update automatically as content is published, without
anyone editing HTML by hand.

---

## 2. Contact details used on the site

- **Email:** `info@nurtureimpact.ie` (shown in the footer, About, and used as
  the contact form's destination)
- **Phone:** intentionally not shown publicly anywhere on the site
- **LinkedIn:** links to `https://www.linkedin.com/in/curtisalan` (Alan's
  profile)

If any of these change again in future, they're each defined in exactly
one place that everything else pulls from:
- Email: `backend/config.php` → `NI_CONTACT_EMAIL`, plus the visible
  `mailto:` links in `includes/partials/footer.php` and each page's own
  footer/about section.
- LinkedIn: search the project for `linkedin.com/in/curtisalan`.

---

## 3. Why PHP + JSON files (not a database)?

Blacknight's standard shared hosting runs Apache + PHP, which is enough to
run this whole site. Rather than adding a MySQL database, Projects and
Articles are stored as two JSON files in `/data`. The admin area reads and
writes those files directly, and the homepage/Projects/Insights pages
always read the current content live — so publishing something in
`/admin` immediately becomes the newest item everywhere it's shown,
without editing any HTML. This keeps the site:

- Deployable by uploading files only — no database creation step required
- Easy to back up (the JSON files are just text — copy them anywhere)
- Simple to inspect or hand-edit if you're ever comfortable doing so

If content volume grows a lot in future, `includes/content.php` is the one
place that would need to change to move to MySQL — nothing else in the
site would need to change, because every page calls through those
functions rather than reading files directly.

---

## 4. Running with Docker (development / review — e.g. on CasaOS)

This is the recommended way to try the site out or review changes before
they go live. It packages the exact same PHP/Apache application described
above — Docker doesn't change how the site works, it just makes it easy to
run anywhere without installing PHP/Apache yourself.

### 4.1 Quick start

```bash
docker compose up -d --build
```

Then open:

```
http://<this-machine's-IP>:8080
```

On CasaOS specifically:
1. Copy this whole project folder onto the CasaOS machine.
2. From that folder, run `docker compose up -d --build` (via CasaOS's
   terminal/SSH, or add it as a Compose app through the CasaOS UI if your
   version supports pointing at a `docker-compose.yml`).
3. Visit `http://<CASAOS-IP>:8080` in a browser and bookmark it.

### 4.2 Common commands

```bash
# Start (build the image the first time, or after a code change)
docker compose up -d --build

# Stop (content is untouched — see §5)
docker compose down

# View logs
docker compose logs -f

# Rebuild after changing site code, keeping all published content
docker compose up -d --build
```

`docker compose down` followed by `docker compose up -d --build` is the
normal "update the app" workflow — because of how persistent storage is
set up (§5), this never deletes articles, projects, uploaded images, or
the admin login.

### 4.3 Changing the port

Default is `8080`. To use a different port, create a `.env` file (copy
`.env.example`) and set `NI_HTTP_PORT=<your port>`, then
`docker compose up -d --build` again.

### 4.4 Admin area in Docker

Same as production — visit `http://<host>:8080/admin/`. The first visit
shows a one-time setup form to create the admin username/password. See §7.

---

## 5. Persistent storage (important)

Two folders hold everything the admin area can create or change, and both
are mounted from the host machine into the container so they survive
rebuilds, restarts, and `docker compose down` / `up` cycles:

```
./docker-data/data      →  /var/www/html/data              (projects.json, articles.json, admin-credentials.json)
./docker-data/uploads   →  /var/www/html/assets/uploads     (images uploaded via /admin)
```

`./docker-data/` is created automatically the first time you run
`docker compose up`. It lives next to `docker-compose.yml` on the host and
is **not** part of the Docker image — rebuilding or recreating the
container never touches it.

**The first time** the container starts with an empty `./docker-data/`
folder, it automatically copies in the same starter `projects.json` /
`articles.json` that ship with the code (see `docker/docker-entrypoint.sh`)
so the site isn't just blank — but only if those files don't already
exist on the host side. Once they exist, they're yours: the entrypoint
never overwrites them again, no matter how many times you rebuild.

`admin-credentials.json` is never pre-seeded — its absence is exactly what
makes `/admin/setup.php` show the one-time account-creation form. Once
created, it lives in `./docker-data/data/admin-credentials.json` on the
host and persists across rebuilds the same way.

### Backing up (Docker)

Backing up is just copying a folder:

```bash
cp -r docker-data/ backup-docker-data-2026-09-15/
```

To restore, stop the container, replace `docker-data/` with the backup,
and start again.

---

## 6. Development vs production — read this before deploying

Docker is for **local development and review** (e.g. on a CasaOS box). The
**production** site continues to be deployed conventionally, exactly as
before:

```
Development / review:   CasaOS  →  Docker  →  Apache + PHP  →  Nurture Impact
Production:              Blacknight  →  Apache + PHP  →  Nurture Impact  (no Docker)
```

The same application code runs in both places unchanged — nothing in this
project depends on a Docker-specific API or service. The Dockerfile exists
purely to package Apache + PHP + this code into one container; on
Blacknight, Apache + PHP are simply already there, so Docker isn't needed
at all. See §9 for the Blacknight deployment steps.

---

## 7. The admin area at a glance

- URL: `/admin/`
- Protected by username/password (set on first visit via `/admin/setup.php`)
- Two content types: **Articles** (Insights) and **Projects**
- Each has a list view (Edit/Delete) and an add/edit form with image upload
- A **Published** checkbox controls visibility — unticked items are drafts,
  visible only in `/admin`, never on the public site or homepage
- The homepage, `/insights.php` and `/projects.php` always show the
  **newest published** items first (by date) automatically — nothing to
  configure

To reset a forgotten admin password: delete `data/admin-credentials.json`
(on the server, or `docker-data/data/admin-credentials.json` in Docker)
and visit `/admin/` again to go through setup once more.

---

## 8. Contact form / email configuration

Every contact form on the site (homepage, About, Services, Governance,
Grant Applications, Events & Workshops) submits via JavaScript to
`contact-handler.php`, which:

- Validates the name, email and message are present and the email address
  is well-formed
- Silently discards anything caught by a hidden honeypot field (basic spam
  protection) without sending mail, but still reports "success" to the
  (bot) submitter so it gets no useful signal
- Strips line breaks from any field used in an email header (defends
  against header-injection spam)
- Sends **`info@nurtureimpact.ie`** an email with all submitted fields in
  an HTML table, plus a plain-text fallback version (a proper
  multipart/alternative email — most inboxes will show the HTML table)
- Reports success/failure back to the visitor inline on the page (no
  redirect, no exposed server errors) — and still works, via a redirect,
  if JavaScript is unavailable

### Mail transport

Two transports are supported, chosen by the `NI_MAIL_TRANSPORT` setting in
`backend/config.php` (which reads it from an environment variable — never
hard-coded):

- **`mail` (default)** — PHP's built-in `mail()`, which hands off to the
  server's local mail setup. This works out of the box on Blacknight
  shared hosting and most conventional Apache/PHP hosts with no extra
  configuration.
- **`smtp`** — a small built-in SMTP client (no external library added)
  for sending via a real mail provider, useful where local `mail()`
  delivery is unreliable (this is often the case inside Docker/CasaOS,
  since there's no local mail server in the container). Configure it
  entirely through environment variables — see `.env.example`:

  ```
  NI_MAIL_TRANSPORT=smtp
  NI_SMTP_HOST=smtp.example.com
  NI_SMTP_PORT=587
  NI_SMTP_ENCRYPTION=tls
  NI_SMTP_USERNAME=...
  NI_SMTP_PASSWORD=...
  NI_MAIL_FROM_EMAIL=no-reply@nurtureimpact.ie
  NI_MAIL_FROM_NAME=Nurture Impact Website
  ```

  In Docker: copy `.env.example` to `.env`, fill in the real values, and
  restart with `docker compose up -d --build` — Compose loads `.env`
  automatically. On Blacknight: set these as actual environment variables
  through the hosting control panel if it supports that, or ask
  Blacknight support for the supported way to set PHP environment
  variables for the account; otherwise leave `NI_MAIL_TRANSPORT` unset to
  use the default `mail()` transport.

  **No SMTP password or API key is ever committed to this project** —
  `.env` is listed in `.gitignore` and `.dockerignore`.

### A note on testing

The SMTP transport is implemented as a plain, from-scratch client (SMTP
handshake, STARTTLS, AUTH LOGIN) since adding a full mailer library felt
like more than this simple site needs — but it has **not** been exercised
against a live mail server in this project's development/review
environment (no outbound SMTP access here). Test it against your real
provider (e.g. send yourself a test enquiry through the live form) before
relying on it for production. The default `mail()` transport is the
simpler, more broadly-compatible option for Blacknight and is what's
enabled unless you deliberately switch it.

---

## 9. Deploying to Blacknight (production)

### 9.1 What gets uploaded

Upload the **entire contents of this folder** (excluding `Dockerfile`,
`docker-compose.yml`, `.dockerignore`, `docker/`, `.env*`, and
`docker-data/` — those are Docker-only and irrelevant to Blacknight) to
your hosting account's web root (usually `public_html/` or `httpdocs/`)
via FTP/SFTP or the Blacknight File Manager. Make sure hidden files
(`.htaccess`) are included — set your FTP client to show hidden files, or
they won't transfer.

### 9.2 Database

**None required.** Projects and Articles are stored as JSON files, not in
MySQL.

### 9.3 File permissions

The web server needs to **write** to two places so the admin area can
save content and uploaded images:

- `/data/` (and the files inside it)
- `/assets/uploads/`

Set permissions on those folders to **755** (or **775** if 755 doesn't
work) via FTP or Blacknight's File Manager.

### 9.4 Setting up the admin area

1. Visit `https://www.nurtureimpact.ie/admin/`.
2. The first visit shows a one-time setup form — choose a username and
   password (this creates `data/admin-credentials.json`).
3. From then on, `/admin/` shows a normal login form.

### 9.5 Publishing content

Log into `/admin/` → **Articles** or **Projects** → **+ New...** → fill in
the fields → tick **Published** → Save. It appears immediately on the
relevant list page, at its own pretty URL (`/insights/your-slug` or
`/projects/your-slug`), and — if it's the newest published item — on the
homepage. Leaving **Published** unticked keeps it as a draft.

### 9.6 Pretty URLs

`.htaccess` rewrites `/insights/your-slug` to `article.php?slug=your-slug`
(and similarly for `/projects/your-slug`). This needs Apache's
`mod_rewrite`, standard on Blacknight. If pretty URLs don't work, contact
Blacknight support to confirm `mod_rewrite` is enabled — the
`?slug=...` links keep working regardless either way.

### 9.7 Contact form on Blacknight

Leave `NI_MAIL_TRANSPORT` unset (defaults to `mail`) unless you have a
specific reason to use SMTP — Blacknight's shared hosting supports PHP's
`mail()` out of the box.

---

## 10. Backups and rollback (Blacknight / non-Docker)

Because there's no database, a full backup is just a copy of the files.

**Before every deployment:**
1. Download a full copy of the current live site via FTP into a dated
   folder, e.g. `backup-2026-09-15/`. Pay particular attention to
   `/data/` (published content + admin login) — this is the one thing
   that can't be regenerated from the code.
2. Keep at least the last 2–3 dated backups.

**To roll back:** upload the previous dated backup's files back over the
live site via FTP. If only content changed, you can instead just restore
`/data/projects.json` and `/data/articles.json` without touching anything
else.

**Recommended safety net:** if comfortable, deploy to a staging subdomain
first (e.g. `staging.nurtureimpact.ie`) and check it before copying the
same files to the live site.

---

## 11. Security notes

- `data/admin-credentials.json`, `data/projects.json` and
  `data/articles.json` can never be downloaded directly — `.htaccess`
  denies all direct requests for `*.json` site-wide, plus `/data/` and
  `/backend/` each additionally deny all direct access outright.
- Uploaded images are restricted to JPEG/PNG/WEBP/GIF (checked by actual
  file content, not just the filename), capped at 5MB, saved under a
  randomly generated filename, and `assets/uploads/.htaccess` prevents
  anything in that folder from ever being executed as a script — even if
  someone found a way to upload a disguised file.
- Contact form input is escaped before being placed in the HTML email, and
  stripped of line breaks before being used in any email header, to
  prevent injection.
- Admin login has a small growing delay after failed attempts (basic
  brute-force friction) on top of the existing hashed-password check.
- No secrets are stored in the Dockerfile, docker-compose.yml, or any
  committed file — SMTP credentials are read from environment variables
  only, supplied via a local, git-ignored `.env`.

---

## 12. Images

Section/service images were rebalanced so the same photo isn't reused
across unrelated pages, and a couple of images that were mismatched to
the wrong topic (a funding-process graphic labelled "Strategy & Planning",
a funding-appraisal photo labelled "Bespoke Consultancy") were corrected.
Two exact-duplicate crops of the same stock photo (`workshop_1.png`,
`workshop_2.png`) and one unused near-duplicate (`funding.png`) were
removed since nothing referenced them after the rebalance — every
remaining asset in `/assets` is referenced from at least one page or
content entry.

---

## 13. Local development without Docker

You'll need PHP installed locally.

```bash
php -S localhost:8000
```

Then open `http://localhost:8000/` (serves `index.php` automatically) and
`http://localhost:8000/admin/` to set up your admin login.

There's no build step — no npm, no compiling. Edit a file and refresh the
page to see the change (or `docker compose up -d --build` to see it via
Docker instead).
