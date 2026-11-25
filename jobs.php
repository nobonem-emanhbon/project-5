<?php
require_once __DIR__ . '/functions.php';

$conn = db_connect();
create_jobs_table($conn);

// Read and tidy optional search query from either index.php or the jobs.php search bar.
$search_query = isset($_GET['q']) ? tidy_input($_GET['q']) : '';
$jobs = [];

if ($search_query !== '') {
    $like = '%' . $search_query . '%';
    $stmt = $conn->prepare(
        'SELECT reference, title, image, image_alt, employment_type, summary, salary, reports_to, responsibilities, essential, preferred
         FROM jobs
         WHERE reference LIKE ?
            OR title LIKE ?
            OR employment_type LIKE ?
            OR summary LIKE ?
            OR responsibilities LIKE ?
            OR essential LIKE ?
         ORDER BY reference'
    );
    $stmt->bind_param('ssssss', $like, $like, $like, $like, $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
    $stmt->close();
} else {
    $result = $conn->query(
        'SELECT reference, title, image, image_alt, employment_type, summary, salary, reports_to, responsibilities, essential, preferred
         FROM jobs
         ORDER BY reference'
    );
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

$conn->close();

$page_title       = 'G05 Retail | Current Job Openings';
$page_description = 'Explore current job openings at G05 Retail loaded dynamically from our database, with search filtering.';
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

    <form class="job-search card" role="search" action="jobs.php" method="get">
      <label class="job-search__label" for="jobs-search-input">Search current openings</label>
      <div class="job-search__controls">
        <input class="form-control job-search__input" id="jobs-search-input" name="q" type="search" value="<?= e($search_query) ?>" placeholder="Search by title, reference (e.g. RM101), or type..." autocomplete="off">
        <button class="button button--primary" type="submit">Search</button>
<?php if ($search_query !== ''): ?>
        <a class="button button--secondary" href="jobs.php">Clear</a>
<?php endif; ?>
      </div>
    </form>

    <div class="jobs-layout">
      <div class="job-listings">
<?php if (!$jobs): ?>
        <section class="card" aria-live="polite">
          <h2 class="card__title">No matching roles found</h2>
          <p class="home-card__copy">No open positions matched &ldquo;<?= e($search_query) ?>&rdquo;. Try another keyword or <a href="jobs.php">view all openings</a>.</p>
        </section>
<?php else: ?>
<?php foreach ($jobs as $job): ?>
<?php
        $ref_id = strtolower(preg_replace('/[^A-Za-z0-9_-]/', '', $job['reference']));
        $responsibilities = split_lines($job['responsibilities']);
        $essential_items  = split_lines($job['essential']);
        $preferred_items  = split_lines($job['preferred']);
?>
        <section class="job-card card" id="job-<?= e($ref_id) ?>" aria-labelledby="job-title-<?= e($ref_id) ?>">
          <article class="job-card__article">
            <img class="job-card__image" src="<?= e($job['image']) ?>" alt="<?= e($job['image_alt']) ?>" width="500" height="320" style="max-width: 100%; height: auto; border-radius: 8px; margin-bottom: 16px;">
            <div class="job-card__heading">
              <div class="job-card__badges">
                <span class="badge badge--reference">Ref: <?= e($job['reference']) ?></span>
                <span class="badge badge--type"><?= e($job['employment_type']) ?></span>
              </div>
              <h2 class="job-card__title" id="job-title-<?= e($ref_id) ?>"><?= e($job['title']) ?></h2>
              <p class="job-card__summary"><?= e($job['summary']) ?></p>
            </div>

            <dl class="job-card__details">
              <div class="job-card__detail">
                <dt class="job-card__term">Salary</dt>
                <dd class="job-card__value"><?= e($job['salary']) ?></dd>
              </div>
              <div class="job-card__detail">
                <dt class="job-card__term">Reports to</dt>
                <dd class="job-card__value"><?= e($job['reports_to']) ?></dd>
              </div>
            </dl>

            <section class="job-card__section" aria-labelledby="resp-<?= e($ref_id) ?>">
              <h3 class="job-card__subheading" id="resp-<?= e($ref_id) ?>">Key responsibilities</h3>
              <ol class="job-card__list job-card__list--ordered">
<?php foreach ($responsibilities as $item): ?>
                <li><?= e($item) ?></li>
<?php endforeach; ?>
              </ol>
            </section>

            <div class="requirements-grid">
              <section class="requirements-group requirements-group--essential" aria-labelledby="ess-<?= e($ref_id) ?>">
                <h3 class="job-card__subheading" id="ess-<?= e($ref_id) ?>">Essential requirements</h3>
                <ul class="job-card__list requirements-group__list">
<?php foreach ($essential_items as $item): ?>
                  <li><?= e($item) ?></li>
<?php endforeach; ?>
                </ul>
              </section>
              <section class="requirements-group requirements-group--preferred" aria-labelledby="pref-<?= e($ref_id) ?>">
                <h3 class="job-card__subheading" id="pref-<?= e($ref_id) ?>">Preferable requirements</h3>
                <ul class="job-card__list requirements-group__list">
<?php foreach ($preferred_items as $item): ?>
                  <li><?= e($item) ?></li>
<?php endforeach; ?>
                </ul>
              </section>
            </div>

            <p style="margin-top: 20px; margin-bottom: 0;">
              <a class="button button--primary" href="apply.php?job=<?= rawurlencode($job['reference']) ?>">Apply for <?= e($job['title']) ?></a>
            </p>
          </article>
        </section>
<?php endforeach; ?>
<?php endif; ?>
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
