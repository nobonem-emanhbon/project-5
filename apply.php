<?php
// Stage 1 modular PHP version: renders the application form without database calls.
// Server-side validation and EOI database storage (process_eoi.php) are deferred to the next stage.
$jobs = [
    'RM101' => 'Store Manager',
    'CS204' => 'Cashier',
];

$states  = ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'];
$genders = ['Male', 'Female', 'Non-binary'];
$skills  = [
    'cashier'       => 'Cashier',
    'store_manager' => 'Store Manager',
    'marketing'     => 'Advertising & Marketing',
    'janitor'       => 'Janitor',
    'stocker'       => 'Stocker',
    'other'         => 'Other',
];

// Allow links such as apply.php?job=RM101 to pre-fill a known job reference safely.
$selected_job_ref = '';
if (isset($_GET['job']) && is_string($_GET['job'])) {
    $requested_job = strtoupper(trim($_GET['job']));
    if (isset($jobs[$requested_job])) {
        $selected_job_ref = $requested_job;
    }
}

$page_title       = 'G05 Retail | Apply';
$page_description = 'Submit your job application to join the G05 Retail team.';
$active_page      = 'apply';

require __DIR__ . '/header.php';
?>

  <main>
    <h1 class="application-page__title">G05 Application form</h1>
    <p class="notice">The Help Desk is located in HQ at the topmost floor. Open 10:30 to 19:00 Monday to Friday. Check Blackboard for when our evaluators will be there.</p>

    <form class="application-form" method="post" action="process_eoi.php" novalidate>
      <fieldset class="form-card">
        <legend class="form-card__legend">Personal info</legend>
        <p class="form-field">
          <label class="form-field__label" for="job_ref">Specify your job reference number: </label>
          <input class="form-control" type="text" name="job_ref" id="job_ref" list="job-refs" value="<?= htmlspecialchars($selected_job_ref, ENT_QUOTES, 'UTF-8') ?>" placeholder="e.g. RM101" autocomplete="off">
          <datalist id="job-refs">
<?php foreach ($jobs as $reference => $title): ?>
            <option value="<?= htmlspecialchars($reference, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></option>
<?php endforeach; ?>
          </datalist>
        </p>

        <p class="form-field form-field--name">
          <label class="form-field__label" for="first_name">First Name: </label>
          <input class="form-control" type="text" name="first_name" id="first_name" autocomplete="given-name">
          <label class="form-field__label" for="last_name">Last Name: </label>
          <input class="form-control" type="text" name="last_name" id="last_name" autocomplete="family-name">
        </p>

        <p class="form-field">
          <label class="form-field__label" for="email">Your email: </label>
          <input class="form-control" type="email" name="email" id="email" autocomplete="email">
        </p>

        <p class="form-field">
          <label class="form-field__label" for="phone">Your Phone number: </label>
          <input class="form-control" type="text" name="phone" id="phone" placeholder="8 to 12 digits" autocomplete="tel">
        </p>

        <p class="form-field form-field--address">
          <label class="form-field__label" for="street">Street address: </label>
          <input class="form-control" type="text" name="street" id="street" autocomplete="street-address">
          <label class="form-field__label" for="suburb">Suburb/town: </label>
          <input class="form-control" type="text" name="suburb" id="suburb" autocomplete="address-level2">
          <label class="form-field__label" for="postcode">Postcode: </label>
          <input class="form-control" type="text" name="postcode" id="postcode" inputmode="numeric" autocomplete="postal-code">
        </p>

        <p class="form-field">
          <label class="form-field__label" for="state">Your state: </label>
          <select class="form-control" name="state" id="state">
            <option value="">Please Select</option>
<?php foreach ($states as $state): ?>
            <option value="<?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($state, ENT_QUOTES, 'UTF-8') ?></option>
<?php endforeach; ?>
          </select>
        </p>

        <p class="form-field">
          <label class="form-field__label" for="dob">Date of birth:</label>
          <input class="form-control" type="date" name="dob" id="dob" placeholder="dd/mm/yyyy">
        </p>
      </fieldset>

      <hr class="application-form__divider">

      <fieldset class="form-card">
        <legend class="form-card__legend">Your gender</legend>
        <p class="form-field form-field--options">
<?php foreach ($genders as $index => $gender): ?>
          <label class="form-option" for="gender<?= htmlspecialchars((string) $index, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($gender, ENT_QUOTES, 'UTF-8') ?>
            <input class="form-option__control" type="radio" id="gender<?= htmlspecialchars((string) $index, ENT_QUOTES, 'UTF-8') ?>" name="gender" value="<?= htmlspecialchars($gender, ENT_QUOTES, 'UTF-8') ?>">
          </label>
<?php endforeach; ?>
        </p>
      </fieldset>

      <hr class="application-form__divider">

      <fieldset class="form-card">
        <legend class="form-card__legend">Skill lists</legend>
        <p class="form-field form-field--options">
<?php foreach ($skills as $value => $label): ?>
          <label class="form-option" for="skill-<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></label>
          <input class="form-option__control" type="checkbox" name="skills[]" id="skill-<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>">
<?php endforeach; ?>
        </p>
        <p class="form-field form-field--textarea-label">
          <label class="form-field__label" for="comments">Explain your position:</label>
        </p>
        <textarea class="form-control form-control--textarea" id="comments" name="comments" placeholder="Write description of your submission here."></textarea>
        <p class="form-field form-field--textarea-label">
          <label class="form-field__label" for="other_skills">Other skills</label>
        </p>
        <textarea class="form-control form-control--textarea" id="other_skills" name="other_skills" placeholder="Write description of your other potential here."></textarea>
      </fieldset>

      <hr class="application-form__divider">

      <input class="button button--primary" type="submit" value="Submit application">
      <input class="button button--secondary" type="reset" value="Reset Form">
    </form>
  </main>

<?php require __DIR__ . '/footer.php'; ?>
