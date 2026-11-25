<?php
require_once __DIR__ . '/functions.php';
start_secure_session();

// Block direct GET access to process_eoi.php per assignment requirements.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: apply.php');
    exit;
}

$conn = db_connect();
create_jobs_table($conn);
create_eoi_table($conn);
$valid_jobs = get_job_options($conn);

// Tidy raw user inputs before running server-side validation.
$job_ref      = strtoupper(tidy_input($_POST['job_ref'] ?? ''));
$first_name   = tidy_input($_POST['first_name'] ?? '');
$last_name    = tidy_input($_POST['last_name'] ?? '');
$dob_raw      = tidy_input($_POST['dob'] ?? '');
$gender       = tidy_input($_POST['gender'] ?? '');
$street       = tidy_input($_POST['street'] ?? '');
$suburb       = tidy_input($_POST['suburb'] ?? '');
$state        = strtoupper(tidy_input($_POST['state'] ?? ''));
$postcode     = tidy_input($_POST['postcode'] ?? '');
$email        = tidy_input($_POST['email'] ?? '');
$phone        = tidy_input($_POST['phone'] ?? '');
$comments     = tidy_input($_POST['comments'] ?? '');
$other_skills = tidy_input($_POST['other_skills'] ?? '');

$raw_skills = isset($_POST['skills']) && is_array($_POST['skills']) ? $_POST['skills'] : [];
$allowed_skills = skill_options();
$selected_skills = [];
foreach ($raw_skills as $skill_value) {
    $cleaned_skill = tidy_input($skill_value);
    if (isset($allowed_skills[$cleaned_skill]) && !in_array($cleaned_skill, $selected_skills, true)) {
        $selected_skills[] = $cleaned_skill;
    }
}

$errors = [];

// Job reference: exactly 5 alphanumeric characters and must match an existing job.
if (!preg_match('/^[A-Za-z0-9]{5}$/', $job_ref)) {
    $errors[] = 'Job reference number must be exactly 5 alphanumeric characters (for example, RM101).';
} elseif (!isset($valid_jobs[$job_ref])) {
    $errors[] = 'Job reference number does not match a current open position.';
}

// First and last names: max 20 alphabetic characters.
if (!preg_match('/^[A-Za-z]{1,20}$/', $first_name)) {
    $errors[] = 'First name is required and must contain at most 20 alphabetic characters.';
}
if (!preg_match('/^[A-Za-z]{1,20}$/', $last_name)) {
    $errors[] = 'Last name is required and must contain at most 20 alphabetic characters.';
}

// Date of birth: accept YYYY-MM-DD (HTML date input) or dd/mm/yyyy; age must be 15 to 80.
$dob_sql = null;
$dob_obj = null;
if ($dob_raw === '') {
    $errors[] = 'Date of birth is required.';
} else {
    $formats = ['Y-m-d', 'd/m/Y'];
    foreach ($formats as $format) {
        $parsed = DateTimeImmutable::createFromFormat('!' . $format, $dob_raw);
        if ($parsed !== false && $parsed->format($format) === $dob_raw) {
            $dob_obj = $parsed;
            break;
        }
    }

    if ($dob_obj === null) {
        $errors[] = 'Date of birth must be a valid date (dd/mm/yyyy or yyyy-mm-dd).';
    } else {
        $today = new DateTimeImmutable('today');
        $age = $dob_obj->diff($today)->y;
        if ($dob_obj > $today || $age < 15 || $age > 80) {
            $errors[] = 'Applicant age must be between 15 and 80 years old.';
        } else {
            $dob_sql = $dob_obj->format('Y-m-d');
        }
    }
}

// Gender
if (!in_array($gender, gender_options(), true)) {
    $errors[] = 'Please select your gender.';
}

// Street and suburb: required, max 40 characters.
if ($street === '' || mb_strlen($street) > 40) {
    $errors[] = 'Street address is required and must be at most 40 characters.';
}
if ($suburb === '' || mb_strlen($suburb) > 40) {
    $errors[] = 'Suburb/town is required and must be at most 40 characters.';
}

// State and matching 4-digit Australian postcode.
if (!in_array($state, state_options(), true)) {
    $errors[] = 'Please select a valid Australian state or territory.';
}
if (!preg_match('/^\d{4}$/', $postcode)) {
    $errors[] = 'Postcode must be exactly 4 digits.';
} elseif (in_array($state, state_options(), true) && !postcode_matches_state($state, $postcode)) {
    $errors[] = 'Postcode does not match the selected state (' . $state . ').';
}

// Email format
if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $errors[] = 'Please enter a valid email address.';
}

// Phone: 8 to 12 digits (spaces allowed)
$phone_digits = preg_replace('/\s+/', '', $phone);
if (!preg_match('/^[0-9 ]+$/', $phone) || strlen($phone_digits) < 8 || strlen($phone_digits) > 12) {
    $errors[] = 'Phone number must contain 8 to 12 digits (spaces allowed).';
}

// If "Other" skill is ticked, other_skills must be filled in.
if (in_array('other', $selected_skills, true) && $other_skills === '') {
    $errors[] = 'Please describe your other skills when the "Other" checkbox is selected.';
}

if ($errors) {
    $_SESSION['eoi_errors'] = $errors;
    $_SESSION['eoi_old'] = [
        'job_ref'      => $job_ref,
        'first_name'   => $first_name,
        'last_name'    => $last_name,
        'dob'          => $dob_raw,
        'gender'       => $gender,
        'street'       => $street,
        'suburb'       => $suburb,
        'state'        => $state,
        'postcode'     => $postcode,
        'email'        => $email,
        'phone'        => $phone,
        'skills'       => $selected_skills,
        'comments'     => $comments,
        'other_skills' => $other_skills,
    ];
    $conn->close();
    header('Location: apply.php');
    exit;
}

// Sanitise values before inserting into MySQL using a prepared statement.
$skills_labels = [];
foreach ($selected_skills as $skill_key) {
    $skills_labels[] = $allowed_skills[$skill_key];
}
$skills_text = implode(', ', $skills_labels);

$clean_job_ref      = html_clean($job_ref);
$clean_first_name   = html_clean($first_name);
$clean_last_name    = html_clean($last_name);
$clean_gender       = html_clean($gender);
$clean_street       = html_clean($street);
$clean_suburb       = html_clean($suburb);
$clean_state        = html_clean($state);
$clean_postcode     = html_clean($postcode);
$clean_email        = html_clean($email);
$clean_phone        = html_clean($phone);
$clean_skills       = html_clean($skills_text);
$clean_other_skills = html_clean($other_skills);
$clean_comments     = html_clean($comments);

$stmt = $conn->prepare(
    'INSERT INTO eoi (
        job_ref, first_name, last_name, dob, gender, street, suburb, state, postcode,
        email, phone, skills, other_skills, comments
     ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param(
    'ssssssssssssss',
    $clean_job_ref,
    $clean_first_name,
    $clean_last_name,
    $dob_sql,
    $clean_gender,
    $clean_street,
    $clean_suburb,
    $clean_state,
    $clean_postcode,
    $clean_email,
    $clean_phone,
    $clean_skills,
    $clean_other_skills,
    $clean_comments
);
$stmt->execute();
$eoi_number = (int) $conn->insert_id;
$stmt->close();

$job_title = $valid_jobs[$job_ref] ?? $job_ref;
$conn->close();

$page_title       = 'G05 Retail | Application Received';
$page_description = 'Confirmation of your G05 Retail Expression of Interest submission.';
$active_page      = 'apply';

require __DIR__ . '/header.php';
?>

  <main class="home-main">
    <section class="card" aria-labelledby="confirm-title">
      <h1 class="card__title" id="confirm-title">Application Submitted</h1>
      <div class="message message--success" role="status">
        <p><strong>Thank you, <?= e($first_name . ' ' . $last_name) ?>!</strong> Your Expression of Interest has been recorded.</p>
        <p style="margin-bottom: 0;">Your unique EOI Reference Number is <strong>#<?= e((string) $eoi_number) ?></strong>.</p>
      </div>
      <dl class="job-card__details" style="margin-top: 16px;">
        <div>
          <dt><strong>Position</strong></dt>
          <dd style="margin-left: 0;"><?= e($job_title) ?> (<?= e($job_ref) ?>)</dd>
        </div>
        <div>
          <dt><strong>Status</strong></dt>
          <dd style="margin-left: 0;">New</dd>
        </div>
        <div>
          <dt><strong>Email</strong></dt>
          <dd style="margin-left: 0;"><?= e($email) ?></dd>
        </div>
        <div>
          <dt><strong>Phone</strong></dt>
          <dd style="margin-left: 0;"><?= e($phone) ?></dd>
        </div>
      </dl>
      <p style="margin-top: 20px; margin-bottom: 0;">
        <a class="button button--primary" href="jobs.php">Back to Job Openings</a>
        <a class="button button--secondary" href="index.php">Return Home</a>
      </p>
    </section>
  </main>

<?php require __DIR__ . '/footer.php'; ?>
