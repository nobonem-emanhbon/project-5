<?php
require_once __DIR__ . '/functions.php';
start_secure_session();
require_login();

$conn = db_connect();
create_jobs_table($conn);
create_eoi_table($conn);

$job_options    = get_job_options($conn);
$status_options = eoi_status_options();

// Handle POST actions (status update or bulk delete by job reference) with CSRF check.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted_token = $_POST['csrf_token'] ?? '';
    if (!csrf_is_valid($submitted_token)) {
        flash_set('error', 'Invalid security token. Please try again.');
        $conn->close();
        header('Location: manage.php');
        exit;
    }

    $action = tidy_input($_POST['action'] ?? '');

    if ($action === 'update_status') {
        $eoi_number = isset($_POST['eoi_number']) ? (int) $_POST['eoi_number'] : 0;
        $new_status = tidy_input($_POST['status'] ?? '');

        if ($eoi_number > 0 && in_array($new_status, $status_options, true)) {
            $stmt = $conn->prepare('UPDATE eoi SET status = ? WHERE EOInumber = ?');
            $stmt->bind_param('si', $new_status, $eoi_number);
            $stmt->execute();
            $stmt->close();
            flash_set('success', "Updated EOI #{$eoi_number} status to {$new_status}.");
        } else {
            flash_set('error', 'Could not update EOI status: invalid EOI number or status.');
        }
    } elseif ($action === 'delete_by_job') {
        $delete_job_ref = strtoupper(tidy_input($_POST['delete_job_ref'] ?? ''));
        if ($delete_job_ref !== '' && preg_match('/^[A-Za-z0-9]{5}$/', $delete_job_ref)) {
            $stmt = $conn->prepare('DELETE FROM eoi WHERE job_ref = ?');
            $stmt->bind_param('s', $delete_job_ref);
            $stmt->execute();
            $deleted_count = $stmt->affected_rows;
            $stmt->close();
            flash_set('success', "Deleted {$deleted_count} EOI record(s) for job reference {$delete_job_ref}.");
        } else {
            flash_set('error', 'Please choose a valid 5-character job reference to delete.');
        }
    }

    $conn->close();
    $redirect_query = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== ''
        ? '?' . $_SERVER['QUERY_STRING']
        : '';
    header('Location: manage.php' . $redirect_query);
    exit;
}

// Read GET filter & sorting parameters.
$filter_job_ref    = strtoupper(tidy_input($_GET['job_ref'] ?? ''));
$filter_first_name = tidy_input($_GET['first_name'] ?? '');
$filter_last_name  = tidy_input($_GET['last_name'] ?? '');
$sort_by           = tidy_input($_GET['sort_by'] ?? 'EOInumber');
$sort_dir          = strtoupper(tidy_input($_GET['sort_dir'] ?? 'DESC'));

$allowed_sort_columns = [
    'EOInumber'  => 'EOInumber',
    'job_ref'    => 'job_ref',
    'first_name' => 'first_name',
    'last_name'  => 'last_name',
    'status'     => 'status',
    'created_at' => 'created_at',
];
$order_column = $allowed_sort_columns[$sort_by] ?? 'EOInumber';
$order_dir    = $sort_dir === 'ASC' ? 'ASC' : 'DESC';

// Build dynamic prepared query safely from active filters.
$where_clauses = [];
$params        = [];
$types         = '';

if ($filter_job_ref !== '') {
    $where_clauses[] = 'job_ref = ?';
    $params[]        = $filter_job_ref;
    $types          .= 's';
}
if ($filter_first_name !== '') {
    $where_clauses[] = 'first_name LIKE ?';
    $params[]        = '%' . $filter_first_name . '%';
    $types          .= 's';
}
if ($filter_last_name !== '') {
    $where_clauses[] = 'last_name LIKE ?';
    $params[]        = '%' . $filter_last_name . '%';
    $types          .= 's';
}

$sql = 'SELECT EOInumber, job_ref, first_name, last_name, dob, gender, street, suburb, state, postcode, email, phone, skills, other_skills, comments, status, created_at FROM eoi';
if ($where_clauses) {
    $sql .= ' WHERE ' . implode(' AND ', $where_clauses);
}
$sql .= " ORDER BY {$order_column} {$order_dir}";

$eois = [];
$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $eois[] = $row;
}
$stmt->close();
$conn->close();

$flash = flash_take();
$token = csrf_token();
$current_query_string = http_build_query([
    'job_ref'    => $filter_job_ref,
    'first_name' => $filter_first_name,
    'last_name'  => $filter_last_name,
    'sort_by'    => $order_column,
    'sort_dir'   => $order_dir,
]);

$page_title       = 'G05 Retail | HR Manager';
$page_description = 'Manage, filter, sort, update, and delete G05 Retail Expressions of Interest.';
$active_page      = '';

require __DIR__ . '/header.php';
?>

  <main class="home-main">
    <div class="manage-toolbar">
      <div>
        <p class="eyebrow" style="margin-bottom: 4px;">HR Administration</p>
        <h1 style="margin-bottom: 0;">Manage Expressions of Interest</h1>
      </div>
      <div class="manage-inline-form">
        <span>Signed in as <strong><?= e($_SESSION['username'] ?? 'admin') ?></strong></span>
        <a class="button button--secondary button--small" href="logout.php">Log out</a>
      </div>
    </div>

<?php if ($flash): ?>
    <div class="message message--<?= e($flash['type']) ?>" role="status">
      <?= e($flash['text']) ?>
    </div>
<?php endif; ?>

    <div class="manage-grid">
      <section class="card" aria-labelledby="filter-title">
        <h2 class="card__title" id="filter-title">Filter &amp; Sort EOIs</h2>
        <form method="get" action="manage.php">
          <p class="form-field" style="grid-template-columns: 1fr; gap: 6px;">
            <label class="form-field__label" for="filter_job_ref">Position (Job Reference)</label>
            <select class="form-control" id="filter_job_ref" name="job_ref">
              <option value="">All positions</option>
<?php foreach ($job_options as $ref => $title): ?>
              <option value="<?= e($ref) ?>"<?= ($filter_job_ref === $ref) ? ' selected' : '' ?>><?= e($ref) ?> &ndash; <?= e($title) ?></option>
<?php endforeach; ?>
            </select>
          </p>
          <p class="form-field" style="grid-template-columns: 1fr 1fr; gap: 8px;">
            <span>
              <label class="form-field__label" for="filter_first_name">First name</label>
              <input class="form-control" type="text" id="filter_first_name" name="first_name" value="<?= e($filter_first_name) ?>" placeholder="Any">
            </span>
            <span>
              <label class="form-field__label" for="filter_last_name">Last name</label>
              <input class="form-control" type="text" id="filter_last_name" name="last_name" value="<?= e($filter_last_name) ?>" placeholder="Any">
            </span>
          </p>
          <p class="form-field" style="grid-template-columns: 1fr 1fr; gap: 8px;">
            <span>
              <label class="form-field__label" for="sort_by">Sort by</label>
              <select class="form-control" id="sort_by" name="sort_by">
<?php foreach ($allowed_sort_columns as $col_key => $_col_sql): ?>
                <option value="<?= e($col_key) ?>"<?= ($order_column === $col_key) ? ' selected' : '' ?>><?= e($col_key) ?></option>
<?php endforeach; ?>
              </select>
            </span>
            <span>
              <label class="form-field__label" for="sort_dir">Order</label>
              <select class="form-control" id="sort_dir" name="sort_dir">
                <option value="DESC"<?= ($order_dir === 'DESC') ? ' selected' : '' ?>>Descending</option>
                <option value="ASC"<?= ($order_dir === 'ASC') ? ' selected' : '' ?>>Ascending</option>
              </select>
            </span>
          </p>
          <div class="manage-inline-form">
            <button class="button button--primary button--small" type="submit">Apply filters</button>
            <a class="button button--secondary button--small" href="manage.php">List all EOIs</a>
          </div>
        </form>
      </section>

      <section class="card" aria-labelledby="delete-title">
        <h2 class="card__title" id="delete-title">Delete EOIs by Job Reference</h2>
        <p class="home-card__copy" style="margin-bottom: 16px;">Remove all submitted applications for a specific position reference number.</p>
        <form method="post" action="manage.php?<?= e($current_query_string) ?>" onsubmit="return confirm('Delete ALL EOI records for the selected job reference?');">
          <input type="hidden" name="csrf_token" value="<?= e($token) ?>">
          <input type="hidden" name="action" value="delete_by_job">
          <p class="form-field" style="grid-template-columns: 1fr; gap: 6px;">
            <label class="form-field__label" for="delete_job_ref">Job Reference</label>
            <select class="form-control" id="delete_job_ref" name="delete_job_ref" required>
              <option value="">Select job reference...</option>
<?php foreach ($job_options as $ref => $title): ?>
              <option value="<?= e($ref) ?>"><?= e($ref) ?> &ndash; <?= e($title) ?></option>
<?php endforeach; ?>
            </select>
          </p>
          <button class="button button--danger button--small" type="submit">Delete matching EOIs</button>
        </form>
      </section>
    </div>

    <section class="card" aria-labelledby="results-title">
      <h2 class="card__title" id="results-title">Expressions of Interest (<?= count($eois) ?>)</h2>
<?php if (!$eois): ?>
      <p class="home-card__copy">No EOI records match the current filter.</p>
<?php else: ?>
      <div class="table-scroll">
        <table class="hours-table">
          <thead>
            <tr>
              <th scope="col">EOI #</th>
              <th scope="col">Job Ref</th>
              <th scope="col">Applicant</th>
              <th scope="col">Contact</th>
              <th scope="col">Address</th>
              <th scope="col">Skills</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($eois as $eoi): ?>
            <tr>
              <td><strong>#<?= e((string) $eoi['EOInumber']) ?></strong></td>
              <td><?= e($eoi['job_ref']) ?></td>
              <td>
                <strong><?= e($eoi['first_name'] . ' ' . $eoi['last_name']) ?></strong><br>
                <small><?= e($eoi['gender']) ?> &middot; DOB <?= e($eoi['dob']) ?></small>
              </td>
              <td>
                <?= e($eoi['email']) ?><br>
                <small><?= e($eoi['phone']) ?></small>
              </td>
              <td><?= e($eoi['street'] . ', ' . $eoi['suburb'] . ' ' . $eoi['state'] . ' ' . $eoi['postcode']) ?></td>
              <td>
                <?= $eoi['skills'] !== '' ? e($eoi['skills']) : '<em>None</em>' ?>
<?php if ($eoi['other_skills'] !== ''): ?>
                <br><small>Other: <?= e($eoi['other_skills']) ?></small>
<?php endif; ?>
              </td>
              <td>
                <form class="manage-inline-form" method="post" action="manage.php?<?= e($current_query_string) ?>">
                  <input type="hidden" name="csrf_token" value="<?= e($token) ?>">
                  <input type="hidden" name="action" value="update_status">
                  <input type="hidden" name="eoi_number" value="<?= e((string) $eoi['EOInumber']) ?>">
                  <select class="form-control" name="status" aria-label="Status for EOI #<?= e((string) $eoi['EOInumber']) ?>">
<?php foreach ($status_options as $st): ?>
                    <option value="<?= e($st) ?>"<?= ($eoi['status'] === $st) ? ' selected' : '' ?>><?= e($st) ?></option>
<?php endforeach; ?>
                  </select>
                  <button class="button button--primary button--small" type="submit">Save</button>
                </form>
              </td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>
<?php endif; ?>
    </section>
  </main>

<?php require __DIR__ . '/footer.php'; ?>
