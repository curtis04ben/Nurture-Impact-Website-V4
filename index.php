<?php
require_once __DIR__ . '/includes/content.php';
$latestProjects = array_slice(ni_get_projects(true), 0, 3);
$latestArticles = array_slice(ni_get_articles(true), 0, 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700|Great+Vibes:400,100,300,500,700' rel='stylesheet' type='text/css'>
  <title>Nurture Impact | Youth &amp; Community Development Consultancy, Ireland</title>
  <meta name="description" content="Nurture Impact is a consultancy with over 30 years of experience across Ireland's Youth & Community Development sector — training, governance, funding applications and organisational development.">
  <link rel="canonical" href="https://www.nurtureimpact.ie/index.php">
  <meta property="og:title" content="Nurture Impact | Youth & Community Development Consultancy">
  <meta property="og:description" content="Over 30 years of experience across the Youth & Community Development sector, including 20 years of specialist experience within Pobal.">
  <meta property="og:image" content="https://www.nurtureimpact.ie/assets/main_img.png">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://www.nurtureimpact.ie/index.php">
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="assets/favicon-32.png" sizes="32x32">
  <link rel="apple-touch-icon" href="assets/favicon-180.png">
  <link rel="shortcut icon" href="favicon.ico">
  <!-- build:css assets/styles/styles.css-->
  <link rel="stylesheet" href="styles.css">
  <!-- endbuild -->
</head>

<body>
  <!--Beginning of navigation section-->
  <div class="nav-bar">
      <!-- Logo + Title together on the left -->
      <div class="nav-bar__left">
          <a href="index.php" class="nav-bar-brand">
              <img src="assets/main_img.png" alt="Nurture Impact Logo" class="nav-bar-brand__img">
          </a>
          <div class="nav-bar__title">
              <h1>Nurture Impact</h1>
          </div>
      </div>

      <!-- Desktop navigation links -->
      <ul class="nav-bar__links">
        <li><a href="index.php">Home</a></li>
        <li><a href="services.html">Services</a></li>
        <li><a href="projects.php">Projects</a></li>
        <li><a href="insights.php">Insights</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>

      <!-- Skip link (hidden until focused) -->
      <a href="#intro" class="skip-to-main">skip to main content</a>
  </div>
    <div class="navigation">
      <input type="checkbox" class="navigation__checkbox" id="navi-toggle" aria-hidden="true">
      <label for="navi-toggle" class="navigation__button">
        <span class="navigation__icon">&nbsp;</span>
      </label>
      <div class="navigation__background">&nbsp;</div>
      <nav class="navigation__nav">
        <ul class="navigation__list">        
          <li class="navigation__item"><a href="index.php" class="navigation__link">HOME</a></li>
          <li class="navigation__item"><a href="services.html" class="navigation__link">SERVICES</a></li>
          <li class="navigation__item"><a href="projects.php" class="navigation__link">PROJECTS</a></li>
          <li class="navigation__item"><a href="insights.php" class="navigation__link">INSIGHTS</a></li>
          <li class="navigation__item"><a href="about.html" class="navigation__link">ABOUT</a></li>
          <li class="navigation__item"><a href="#contact" class="navigation__link">CONTACT</a></li>
        </ul>
      </nav>
    </div>
    <!--End of navigation section-->

    <!-- Beginning of header section -->
    <div class="headline__image"></div>
    <header class="hero-header">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <h1 class="headline__main">Nurture Impact</h1>
        <h2 class="headline__sub">Consultancy, Training &amp; Practical Support Across the Youth &amp; Community Development Sector</h2>

        <!-- Two CTA buttons -->
      <div class="hero__buttons">
        <a href="services.html" class="btn--primary btn">OUR SERVICES</a>
        <a href="#contact" class="btn--secondary btn">GET IN TOUCH</a>
      </div>
      </div>
    </header>
      <!-- End of header section -->

    <main>
      <!-- Intro text section (including image on medium and large screens) -->
      <div class="intro__wrapper">
        <section class="intro" id="intro">
          <!-- Text Column -->
          <div class="intro__text">
            <h1 class="intro__headline">Over 30 Years of Experience Across the Youth &amp; Community Development Sector</h1>
            <p class="intro__body">
              Nurture Impact provides consultancy, training and practical support to community groups, social enterprises, Local Development Companies and voluntary boards throughout Ireland — helping organisations build capacity, strengthen governance and secure funding.
            </p>
            <p class="intro__body">
              Nurture Impact draws on over 30 years of experience across the Youth &amp; Community Development sector, including 20 years of specialist experience within Pobal — a significant advantage for organisations preparing Pobal funding applications.
            </p>
            <div class="intro__buttons">
              <a href="services.html" class="intro__btn">Explore Our Services</a>
              <a href="grant-applications.html" class="intro__btn intro__btn--secondary">Pobal Funding Applications</a>
            </div>
          </div>

          <!-- Image Column -->
          <div class="intro__image-container">
            <img src="assets/community_training.png" alt="Participants taking part in a community development session" class="intro__image">
          </div>
        </section>
      </div>

      <!-- End of intro text -->

      <!-- Credential strip: 30 years primary, Pobal secondary -->
      <section class="credential-strip" aria-label="Experience summary">
        <div class="credential-strip__wrapper">
          <div class="credential-strip__primary">
            <span class="credential-strip__number">30+</span>
            <span class="credential-strip__label">Years in Youth &amp; Community Development</span>
          </div>
          <div class="credential-strip__divider"></div>
          <div class="credential-strip__secondary">
            <strong>Including 20 years of specialist experience within Pobal</strong> — a deep, first-hand understanding of the funding environment and application process, now applied in service of client organisations across the wider sector.
          </div>
        </div>
      </section>
      <!-- End of credential strip -->

      <!-- Capabilities overview -->
      <section class="capabilities-section" id="capabilities">
        <div class="capabilities__wrapper">
          <div class="capabilities__intro">
            <h2>How Nurture Impact Can Help</h2>
            <p>A coherent set of consultancy capabilities built around three decades of sector experience — from first strategy conversations through to funding, governance and delivery.</p>
          </div>

          <div class="capability-grid">
            <div class="capability-card">
              <span class="capability-card__eyebrow">Sector Experience</span>
              <h3>Youth &amp; Community Development</h3>
              <p>Broad, practical experience across community groups, youth projects and Local Development Companies throughout Ireland.</p>
              <a href="services.html#youth-community" class="capability-card__link">Learn more</a>
            </div>
            <div class="capability-card">
              <span class="capability-card__eyebrow">Learning</span>
              <h3>Training &amp; Workshops</h3>
              <p>Tailored workshops and training programmes for teams, boards and community groups — practical, not theoretical.</p>
              <a href="services.html#training" class="capability-card__link">Learn more</a>
            </div>
            <div class="capability-card">
              <span class="capability-card__eyebrow">Oversight</span>
              <h3>Governance &amp; Board Development</h3>
              <p>Practical support for boards navigating the Charities Governance Code, compliance and effective oversight.</p>
              <a href="governance.html" class="capability-card__link">Learn more</a>
            </div>
            <div class="capability-card">
              <span class="capability-card__eyebrow">Specialist Credential</span>
              <h3>Funding &amp; Grant Applications</h3>
              <p>Particular expertise in Pobal funding applications, drawing on 20 years of experience working within Pobal.</p>
              <a href="grant-applications.html" class="capability-card__link">Learn more</a>
            </div>
            <div class="capability-card">
              <span class="capability-card__eyebrow">Capacity</span>
              <h3>Organisational Development</h3>
              <p>Support to strengthen organisational structures, systems and long-term sustainability.</p>
              <a href="services.html#organisational-development" class="capability-card__link">Learn more</a>
            </div>
            <div class="capability-card">
              <span class="capability-card__eyebrow">Direction</span>
              <h3>Strategy &amp; Planning</h3>
              <p>Helping organisations develop practical, realistic strategic plans and priorities.</p>
              <a href="services.html#strategy-planning" class="capability-card__link">Learn more</a>
            </div>
            <div class="capability-card">
              <span class="capability-card__eyebrow">Growth</span>
              <h3>Capacity Building</h3>
              <p>Long-term support to build local capacity and resilience, not just one-off assistance.</p>
              <a href="services.html#capacity-building" class="capability-card__link">Learn more</a>
            </div>
            <div class="capability-card">
              <span class="capability-card__eyebrow">Enterprise</span>
              <h3>Social &amp; Community Enterprise Support</h3>
              <p>Guidance for trading organisations balancing mission and market, including funding routes such as LEADER and Rethink Ireland.</p>
              <a href="services.html#social-enterprise" class="capability-card__link">Learn more</a>
            </div>
            <div class="capability-card">
              <span class="capability-card__eyebrow">Tailored</span>
              <h3>Bespoke Consultancy</h3>
              <p>Nurture Impact works with organisations to identify their specific requirements and develop tailored support.</p>
              <a href="services.html#bespoke" class="capability-card__link">Learn more</a>
            </div>
          </div>
        </div>
      </section>
      <!-- End of capabilities overview -->

      <!-- Training & Workshops section -->
      <section class="training-section" id="training">
        <div class="training__wrapper">
          <div class="training__text">
            <h2>Training &amp; Workshops</h2>
            <p>
              Nurture Impact provides tailored training and workshops for organisations, teams, boards and community and youth development groups. Sessions are practical and jargon-free, built on real experience rather than theory.
            </p>
            <p>
              Workshops can be delivered as one-off sessions, bespoke training days, or multi-session programmes, and can be tailored to your organisation's specific requirements.
            </p>
            <ul class="training__list">
              <li>Governance &amp; board development</li>
              <li>Funding &amp; grant applications</li>
              <li>Organisational development</li>
              <li>Strategic planning</li>
              <li>Community &amp; youth development</li>
              <li>Team development</li>
            </ul>
            <a href="services.html#training" class="intro__btn">See Training &amp; Workshops</a>
          </div>
          <div class="training__gallery">
            <img src="assets/workshop_1.jpg" alt="Team taking part in a Nurture Impact training session">
            <img src="assets/funding_app.png" alt="Participant completing a funding application exercise">
            <img src="assets/growth_stages.jpg" alt="Illustration of staged organisational growth">
          </div>
        </div>
      </section>
      <!-- End of Training & Workshops section -->

      <!-- Projects / Recent Work section -->
      <section class="content-section content-section--light" id="projects">
        <div class="content__wrapper">
          <div class="content__header">
            <div>
              <h2>Recent Work</h2>
              <p>A selection of projects Nurture Impact has supported across the sector.</p>
            </div>
            <div class="content__header-link">
              <a href="projects.php">View all projects &rarr;</a>
            </div>
          </div>

          <?php if (empty($latestProjects)): ?>
            <p class="content-placeholder-note">Project write-ups are being prepared for publication — check back soon, or see <a href="services.html">our services</a> in the meantime.</p>
          <?php else: ?>
            <div class="card-grid">
              <?php foreach ($latestProjects as $project): ?>
                <a href="project.php?slug=<?php echo urlencode($project['slug']); ?>" class="content-card">
                  <img src="<?php echo e($project['featured_image'] ?? 'assets/main_img.png'); ?>" alt="<?php echo e($project['title']); ?>" class="content-card__image">
                  <div class="content-card__body">
                    <div class="content-card__meta">
                      <span><?php echo e($project['project_type'] ?? 'Project'); ?></span>
                      <span><?php echo e(ni_format_date($project['date'] ?? '')); ?></span>
                    </div>
                    <h3><?php echo e($project['title']); ?></h3>
                    <p><?php echo e($project['summary'] ?? ''); ?></p>
                    <span class="content-card__cta">Read the full project &rarr;</span>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </section>
      <!-- End of Projects / Recent Work section -->

      <!-- Insights / Latest Articles section -->
      <section class="content-section content-section--tint" id="insights">
        <div class="content__wrapper">
          <div class="content__header">
            <div>
              <h2>Latest Insights</h2>
              <p>Articles and updates from across the Youth &amp; Community Development sector.</p>
            </div>
            <div class="content__header-link">
              <a href="insights.php">View all insights &rarr;</a>
            </div>
          </div>

          <?php if (empty($latestArticles)): ?>
            <p class="content-placeholder-note">Articles are being prepared for publication — follow Nurture Impact on <a href="https://www.linkedin.com/in/curtisalan?utm_source=share_via&amp;utm_content=profile&amp;utm_medium=member_android" target="_blank" rel="noopener">LinkedIn</a> for updates.</p>
          <?php else: ?>
            <div class="card-grid">
              <?php foreach ($latestArticles as $article): ?>
                <a href="article.php?slug=<?php echo urlencode($article['slug']); ?>" class="content-card">
                  <img src="<?php echo e($article['featured_image'] ?? 'assets/main_img.png'); ?>" alt="<?php echo e($article['title']); ?>" class="content-card__image">
                  <div class="content-card__body">
                    <div class="content-card__meta">
                      <span><?php echo e($article['category'] ?? 'Insight'); ?></span>
                      <span><?php echo e(ni_format_date($article['date'] ?? '')); ?></span>
                    </div>
                    <h3><?php echo e($article['title']); ?></h3>
                    <p><?php echo e($article['excerpt'] ?? ''); ?></p>
                    <span class="content-card__cta">Read the full article &rarr;</span>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </section>
      <!-- End of Insights / Latest Articles section -->

      <!-- How It Works section -->
      <section class="how-it-works" id="how-it-works">
        <div class="how-it-works__wrapper">
          <h2 class="how-it-works__headline">How It Works</h2>
          <p class="how-it-works__subheadline">From first conversation to lasting impact</p>

          <div class="how-it-works__steps">
            <!-- Step 1 -->
            <div class="how-it-works__step">
              <div class="how-it-works__number">1</div>
              <div class="how-it-works__connector"></div>
              <h3 class="how-it-works__step-title">Book a Discovery Call</h3>
              <p class="how-it-works__step-text">We'll discuss your organisation's needs, challenges, and goals to understand how best to support you.</p>
            </div>

            <!-- Step 2 -->
            <div class="how-it-works__step">
              <div class="how-it-works__number">2</div>
              <div class="how-it-works__connector"></div>
              <h3 class="how-it-works__step-title">Get a Tailored Programme</h3>
              <p class="how-it-works__step-text">Choose from individual masterclasses or a full programme, customised to your team's specific requirements.</p>
            </div>

            <!-- Step 3 -->
            <div class="how-it-works__step">
              <div class="how-it-works__number">3</div>
              <h3 class="how-it-works__step-title">Build Lasting Capacity</h3>
              <p class="how-it-works__step-text">Walk away with practical skills, ready-to-use templates, and the confidence to secure funding and govern effectively.</p>
            </div>
          </div>

          <div class="how-it-works__cta">
            <a href="#contact" class="why__cta-link">Start the Conversation</a>
          </div>
        </div>
      </section>
      <!-- End of How It Works section -->

      <!-- Why Nurture Impact Section -->
      <section class="why-section" id="why">
        <div class="why__wrapper">
          <h2 class="why__headline">Why Nurture Impact?</h2>
          <p class="why__subheadline">A consultancy built on decades of direct sector experience</p>

          <div class="why__grid">
            <!-- Card 1 -->
            <div class="why__card">
              <div class="why__icon">
                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <circle cx="32" cy="32" r="28" fill="none" stroke="#1e4d3a" stroke-width="4"/>
                  <path d="M32 12 v20 h16" stroke="#1e4d3a" stroke-width="4" stroke-linecap="round"/>
                </svg>
              </div>
              <h3 class="why__card-title">30+ Years Across the Sector</h3>
              <p class="why__card-text">
                Over three decades of direct experience across Youth &amp; Community Development, including 20 years of specialist experience within Pobal — understanding of community development, social enterprise, and LDC operations.
              </p>
            </div>

            <!-- Card 2 -->
            <div class="why__card">
              <div class="why__icon">
                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M32 8 a16 16 0 0 1 0 32 a16 16 0 0 1 0-32" fill="none" stroke="#1e4d3a" stroke-width="4"/>
                  <path d="M24 48 h16 v8 h-16 z" fill="none" stroke="#1e4d3a" stroke-width="4" stroke-linecap="round"/>
                  <line x1="24" y1="44" x2="40" y2="44" stroke="#1e4d3a" stroke-width="4"/>
                  <line x1="28" y1="52" x2="36" y2="52" stroke="#1e4d3a" stroke-width="4"/>
                </svg>
              </div>
              <h3 class="why__card-title">Practical, Jargon-Free Training</h3>
              <p class="why__card-text">
                Tools and templates that can be used immediately. Strong track record supporting boards, promoters, and community groups.
              </p>
            </div>

            <!-- Card 3 -->
            <div class="why__card">
              <div class="why__icon">
                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <rect x="12" y="12" width="40" height="40" rx="6" fill="none" stroke="#1e4d3a" stroke-width="4"/>
                  <polyline points="20,28 28,36 44,20" fill="none" stroke="#1e4d3a" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                  <line x1="20" y1="38" x2="28" y2="46" stroke="#1e4d3a" stroke-width="4" stroke-linecap="round"/>
                </svg>
              </div>
              <h3 class="why__card-title">Long-Term Capacity Building</h3>
              <p class="why__card-text">
                Commitment to strengthening local capacity and long-term sustainability, not just one-off support.
              </p>
            </div>
          </div>

          <!-- Understanding the Sector -->
          <div class="why__sector">
            <h3 class="why__sector-headline">We Understand the Realities of the Sector</h3>
            <div class="why__sector-grid">
              <div class="why__sector-item">
                <span class="why__sector-icon">
                  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="12" cy="12" r="10" fill="none" stroke="#1a4131" stroke-width="2"/>
                    <path d="M12 6v6l4 2" fill="none" stroke="#1a4131" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </span>
                <p>Limited resources</p>
              </div>
              <div class="why__sector-item">
                <span class="why__sector-icon">
                  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="12" cy="7" r="4" fill="none" stroke="#1a4131" stroke-width="2"/>
                    <path d="M5.5 21v-2a4.5 4.5 0 0 1 4.5-4.5h4a4.5 4.5 0 0 1 4.5 4.5v2" fill="none" stroke="#1a4131" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </span>
                <p>Volunteer-led organisations</p>
              </div>
              <div class="why__sector-item">
                <span class="why__sector-icon">
                  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect x="3" y="6" width="18" height="13" rx="2" fill="none" stroke="#1a4131" stroke-width="2"/>
                    <path d="M3 10h18" stroke="#1a4131" stroke-width="2"/>
                    <path d="M7 14h4" stroke="#1a4131" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </span>
                <p>Funding readiness challenges</p>
              </div>
              <div class="why__sector-item">
                <span class="why__sector-icon">
                  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect x="4" y="4" width="16" height="16" rx="2" fill="none" stroke="#1a4131" stroke-width="2"/>
                    <path d="M9 9h6M9 12h6M9 15h4" stroke="#1a4131" stroke-width="2" stroke-linecap="round"/>
                  </svg>
                </span>
                <p>Compliance pressures</p>
              </div>
              <div class="why__sector-item">
                <span class="why__sector-icon">
                  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" fill="none" stroke="#1a4131" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>
                <p>The need for practical, not theoretical, support</p>
              </div>
            </div>
            <p class="why__sector-closing">Our programmes are designed to meet those needs.</p>
          </div>

          <!-- CTA at the bottom -->
          <div class="why__cta">
            <a href="#contact" class="why__cta-link">Book a Discovery Call</a>
          </div>
        </div>
      </section>
    
      <!-- Contact section - backend functionality to be added -->
      <section class="contact" id="contact">
        <h2 class="contact__heading" tabindex="0">Want to get in touch?</h2>
        <p class="contact__sub">Use the form below to get in touch with Nurture Impact</p>
        <form id="contact-form" method="post" action="contact-handler.php">
          <input type="hidden" name="contact_number">
          <input type="hidden" name="source_page" value="Homepage">
          <input type="text" placeholder="Enter your name" id="name"  name="from_name" aria-label="Enter your name" required>
          <input type="email" placeholder="Enter your email" id="email" name="reply_to" aria-label="Enter your email" required>
          <input type="text" placeholder="Organisation name (optional)" id="organisation" name="organisation" aria-label="Enter your organisation name">
          <textarea cols="30" rows="10" placeholder="Enter your message" name="message" id="Message" aria-label="Enter your message" required></textarea>
          <input type="submit" class="btn"  value="SEND MESSAGE">
        </form>
      </section>
      <!-- End of contact section -->
    </main>
  <!-- Footer section -->
  <footer class="footer footer--expanded">
    <div class="footer__content">
      <div class="footer__brand">
        <h3>Nurture Impact</h3>
        <p>Youth &amp; Community Development Consultancy &bull; Training &amp; Governance &bull; Funding &amp; Grant Applications</p>
      </div>

      <div class="footer__nav">
        <h4>Explore</h4>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="services.html">Services</a></li>
          <li><a href="projects.php">Projects</a></li>
          <li><a href="insights.php">Insights</a></li>
          <li><a href="about.html">About</a></li>
        </ul>
      </div>

      <div class="footer__services">
        <h4>Services</h4>
        <ul>
          <li><a href="governance.html">Governance &amp; Board Development</a></li>
          <li><a href="grant-applications.html">Funding &amp; Grant Applications</a></li>
          <li><a href="services.html#training">Training &amp; Workshops</a></li>
          <li><a href="events-workshops.html">Events &amp; Workshops</a></li>
        </ul>
      </div>

      <div class="footer__contact">
        <h4 style="color:#fff;opacity:.9;margin:0 0 10px;font-size:1rem;">Contact</h4>
        <p><a href="mailto:info@nurtureimpact.ie">info@nurtureimpact.ie</a></p>
        <p><a href="https://www.nurtureimpact.ie" target="_blank">www.nurtureimpact.ie</a></p>
        <div class="footer__social">
          <a href="https://www.linkedin.com/in/curtisalan?utm_source=share_via&amp;utm_content=profile&amp;utm_medium=member_android" target="_blank" rel="noopener" aria-label="Nurture Impact on LinkedIn">LinkedIn</a>
        </div>
      </div>
    </div>
    <p class="footer__text">&copy; Nurture Impact 2026</p>
  </footer>
  <script src="assets/js/contact-form.js" defer></script>
</body>
</html>
