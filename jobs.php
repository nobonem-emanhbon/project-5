<?php
// Stage 1 modular PHP version: renders static job listings using shared templates.
// Database-driven job loading and search filtering are deferred to the next stage.
$page_title       = 'G05 Retail | Current Job Openings';
$page_description = 'Explore current job openings at G05 Retail, including Store Manager and Cashier positions.';
$active_page      = 'jobs';
$page_stylesheets = ['style.css', 'jobs.css'];

require __DIR__ . '/header.php';
?>

  <main class="home-main jobs-main">
    <section class="page-intro" aria-labelledby="page-title">
      <p class="eyebrow">Careers at G05 Retail</p>
      <h1 class="page-intro__title" id="page-title">Current Job Openings</h1>
      <p class="page-intro__copy">Join a team focused on helpful service, dependable store operations, and a positive experience for every customer. Review the roles below and follow the application instructions to be considered.</p>
    </section>

    <div class="jobs-layout">
      <div class="job-listings">
        <section class="job-card card" id="job-RM101" aria-labelledby="store-manager-title">
          <article class="job-card__article">
            <img class="job-card__image" src="storemanager.jpg" alt="Illustration of a store manager holding a clipboard outside a retail store" width="500" height="320" style="max-width: 100%; height: auto; border-radius: 8px; margin-bottom: 16px;">
            <div class="job-card__heading">
              <div class="job-card__badges">
                <span class="badge badge--reference">Ref: RM101</span>
                <span class="badge badge--type">Full-time</span>
              </div>
              <h2 class="job-card__title" id="store-manager-title">Store Manager</h2>
              <p class="job-card__summary">Lead day-to-day operations at our retail store and support a team committed to friendly, reliable customer service. You will help meet store targets while maintaining safe, well-presented departments.</p>
            </div>

            <dl class="job-card__details">
              <div class="job-card__detail">
                <dt class="job-card__term">Salary</dt>
                <dd class="job-card__value">AUD $68,000–$78,000 per year plus superannuation</dd>
              </div>
              <div class="job-card__detail">
                <dt class="job-card__term">Reports to</dt>
                <dd class="job-card__value">Regional Operations Manager</dd>
              </div>
            </dl>

            <section class="job-card__section" aria-labelledby="manager-responsibilities">
              <h3 class="job-card__subheading" id="manager-responsibilities">Key responsibilities</h3>
              <ol class="job-card__list job-card__list--ordered">
                <li>Coordinate daily store operations, opening and closing procedures, and floor coverage.</li>
                <li>Coach, roster, and support team members to deliver consistent customer service.</li>
                <li>Monitor sales, stock levels, and operating costs; report results to the regional manager.</li>
                <li>Maintain merchandising, cleanliness, and workplace health and safety standards.</li>
                <li>Resolve customer enquiries and escalate complex concerns appropriately.</li>
              </ol>
            </section>

            <div class="requirements-grid">
              <section class="requirements-group requirements-group--essential" aria-labelledby="manager-essential">
                <h3 class="job-card__subheading" id="manager-essential">Essential requirements</h3>
                <ul class="job-card__list requirements-group__list">
                  <li>At least two years’ retail supervisory or management experience.</li>
                  <li>Strong communication, organisation, and customer service skills.</li>
                  <li>Availability to work a rotating roster, including some weekends.</li>
                  <li>Australian working rights for full-time employment.</li>
                </ul>
              </section>
              <section class="requirements-group requirements-group--preferred" aria-labelledby="manager-preferred">
                <h3 class="job-card__subheading" id="manager-preferred">Preferable requirements</h3>
                <ul class="job-card__list requirements-group__list">
                  <li>Experience using point-of-sale and inventory systems.</li>
                  <li>Certificate IV in Retail Management or equivalent training.</li>
                  <li>Experience supporting staff development and performance reviews.</li>
                </ul>
              </section>
            </div>
          </article>
        </section>

        <section class="job-card card" id="job-CS204" aria-labelledby="cashier-title">
          <article class="job-card__article">
            <img class="job-card__image" src="cashier.jpg" alt="Illustration of a cashier serving a customer at a checkout counter" width="500" height="320" style="max-width: 100%; height: auto; border-radius: 8px; margin-bottom: 16px;">
            <div class="job-card__heading">
              <div class="job-card__badges">
                <span class="badge badge--reference">Ref: CS204</span>
                <span class="badge badge--type">Casual</span>
              </div>
              <h2 class="job-card__title" id="cashier-title">Cashier</h2>
              <p class="job-card__summary">Help customers complete their purchases accurately and make each checkout interaction welcoming. This casual role works across a roster that may include evenings and weekends.</p>
            </div>

            <dl class="job-card__details">
              <div class="job-card__detail">
                <dt class="job-card__term">Salary</dt>
                <dd class="job-card__value">AUD $31.20–$35.00 per hour plus superannuation, depending on experience and applicable award rates</dd>
              </div>
              <div class="job-card__detail">
                <dt class="job-card__term">Reports to</dt>
                <dd class="job-card__value">Customer Service Supervisor</dd>
              </div>
            </dl>

            <section class="job-card__section" aria-labelledby="cashier-responsibilities">
              <h3 class="job-card__subheading" id="cashier-responsibilities">Key responsibilities</h3>
              <ol class="job-card__list job-card__list--ordered">
                <li>Process purchases, refunds, and exchanges accurately using the point-of-sale system.</li>
                <li>Greet customers and respond to routine questions about products and services.</li>
                <li>Handle cash and electronic payments in line with store procedures.</li>
                <li>Keep the checkout area clean, stocked, and ready for customers.</li>
                <li>Contact a supervisor when an enquiry or transaction needs further assistance.</li>
              </ol>
            </section>

            <div class="requirements-grid">
              <section class="requirements-group requirements-group--essential" aria-labelledby="cashier-essential">
                <h3 class="job-card__subheading" id="cashier-essential">Essential requirements</h3>
                <ul class="job-card__list requirements-group__list">
                  <li>Clear communication and a polite, customer-focused approach.</li>
                  <li>Care and accuracy when handling transactions.</li>
                  <li>Availability for a flexible casual roster, including weekends.</li>
                  <li>Australian working rights for casual employment.</li>
                </ul>
              </section>
              <section class="requirements-group requirements-group--preferred" aria-labelledby="cashier-preferred">
                <h3 class="job-card__subheading" id="cashier-preferred">Preferable requirements</h3>
                <ul class="job-card__list requirements-group__list">
                  <li>Previous retail, hospitality, or cashier experience.</li>
                  <li>Familiarity with point-of-sale systems and electronic payments.</li>
                  <li>First aid training or willingness to complete relevant training.</li>
                </ul>
              </section>
            </div>
          </article>
        </section>
      </div>

      <aside class="application-aside notice" id="about" aria-labelledby="apply-help-title" style="width: 100%; margin-top: 0;">
        <h2 class="application-aside__title" id="apply-help-title">How to apply</h2>
        <p class="application-aside__copy">Submit the application form and enter the matching five-character reference number in its job reference number field.</p>
        <ul class="application-aside__list">
          <li>Staff discount on eligible purchases</li>
          <li>Flexible rosters for suitable roles</li>
          <li>On-the-job learning and training</li>
        </ul>
        <a class="button button--primary" href="apply.php">Apply for a role</a>
      </aside>
    </div>
  </main>

<?php require __DIR__ . '/footer.php'; ?>
