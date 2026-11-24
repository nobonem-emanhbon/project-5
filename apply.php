<?php
/*
 * apply.php - the expression of interest form (was apply.html in Part 1).
 *
 * What changed from Part 1:
 *  - posts to process_eoi.php on our own server (it used to post to a lab test URL)
 *  - client-side HTML validation is switched off (novalidate, no required/pattern/maxlength);
 *    process_eoi.php checks everything on the server instead, as the brief asks
 *  - field names are readable (the old ones came from a lecture template: "tutor" held the state,
 *    "unit" held the gender, "topic" held the skills, and both text boxes were called "comments")
 *  - skill checkbox values match their labels (they were HTML, CSS, JS, PHP and MSQL)
 *  - if the server finds a mistake, you come back here with what you typed still filled in
 */
require_once 'functions.php';
start_secure_session();

$conn = db_connect();
create_jobs_table($conn);
$jobs = get_job_options($conn);

// Errors and previously typed values come back from process_eoi.php through the session
$errors = isset($_SESSION['eoi_errors']) && is_array($_SESSION['eoi_errors']) ? $_SESSION['eoi_errors'] : [];
$old    = isset($_SESSION['eoi_old']) && is_array($_SESSION['eoi_old']) ? $_SESSION['eoi_old'] : [];
unset($_SESSION['eoi_errors'], $_SESSION['eoi_old']);

// "Apply for this role" on jobs.php links here with ?job=RM101
if (!isset($old['job_ref']) && isset($_GET['job']) && is_string($_GET['job'])) {
    $wanted = strtoupper(trim($_GET['job']));
    if (isset($jobs[$wanted])) {
        $old['job_ref'] = $wanted;
    }
}

/** Value to put back into a text box. Escaped here because it's the person's raw input. */
function old_value(array $old, string $key): string
{
    return isset($old[$key]) && is_string($old[$key])
        ? htmlspecialchars($old[$key], ENT_QUOTES, 'UTF-8')
        : '';
}

$old_skills = isset($old['skills']) && is_array($old['skills']) ? $old['skills'] : [];

$page_title  = 'G05 Retail | Apply';
$active_page = 'apply';
require 'header.inc';
?>

  <main>
    <h1 class="application-page__title">G05 Application form</h1>
    <p class="notice">The Help Desk is located in HQ at the topmost floor. Open 10:30 to 19:00 Monday to Friday. Check Blackboard for when our evaluators will be there.</p>

<?php if ($errors): ?>
    <div class="message message--error" role="alert">
      <p><strong>Your application hasn&rsquo;t been sent yet.</strong> Please fix the following and submit again:</p>
      <ul>
<?php foreach ($errors as $error): ?>
        <li><?= e($error) ?></li>
<?php endforeach; ?>
      </ul>
    </div>
<?php endif; ?>

    <!-- novalidate = the browser doesn't check anything; the server does it all -->
    <form class="application-form" method="post" action="process_eoi.php" novalidate>
      <fieldset class="form-card">
        <legend class="form-card__legend">Personal info</legend>
        <p class="form-field">
          <label class="form-field__label" for="job_ref">Specify your job reference number: </label>
          <input class="form-control" type="text" name="job_ref" id="job_ref" list="job-refs" value="<?= old_value($old, 'job_ref') ?>" placeholder="e.g. RM101" autocomplete="off">
          <datalist id="job-refs">
<?php foreach ($jobs as $reference => $title): ?>
            <option value="<?= e($reference) ?>"><?= e($title) ?></option>
<?php endforeach; ?>
          </datalist>
        </p>

        <p class="form-field form-field--name">
          <label class="form-field__label" for="first_name">First Name: </label>
          <input class="form-control" type="text" name="first_name" id="first_name" value="<?= old_value($old, 'first_name') ?>" autocomplete="given-name">
          <label class="form-field__label" for="last_name">Last Name: </label>
          <input class="form-control" type="text" name="last_name" id="last_name" value="<?= old_value($old, 'last_name') ?>" autocomplete="family-name">
        </p>

        <p class="form-field">
          <label class="form-field__label" for="email">Your email: </label>
          <input class="form-control" type="email" name="email" id="email" value="<?= old_value($old, 'email') ?>" autocomplete="email">
        </p>

        <p class="form-field">
          <label class="form-field__label" for="phone">Your Phone number: </label>
          <input class="form-control" type="text" name="phone" id="phone" value="<?= old_value($old, 'phone') ?>" placeholder="8 to 12 digits" autocomplete="tel">
        </p>

        <p class="form-field form-field--address">
          <label class="form-field__label" for="street">Street address: </label>
          <input class="form-control" type="text" name="street" id="street" value="<?= old_value($old, 'street') ?>" autocomplete="street-address">
          <label class="form-field__label" for="suburb">Suburb/town: </label>
          <input class="form-control" type="text" name="suburb" id="suburb" value="<?= old_value($old, 'suburb') ?>" autocomplete="address-level2">
          <label class="form-field__label" for="postcode">Postcode: </label>
          <input class="form-control" type="text" name="postcode" id="postcode" value="<?= old_value($old, 'postcode') ?>" inputmode="numeric" autocomplete="postal-code">
        </p>

        <p class="form-field">
          <label class="form-field__label" for="state">Your state: </label>
          <select class="form-control" name="state" id="state">
            <option value="">Please Select</option>
<?php foreach (state_options() as $state): ?>
            <option value="<?= e($state) ?>"<?= (isset($old['state']) && $old['state'] === $state) ? ' selected' : '' ?>><?= e($state) ?></option>
<?php endforeach; ?>
          </select>
        </p>

        <p class="form-field">
          <label class="form-field__label" for="dob">Date of birth:</label>
          <input class="form-control" type="date" name="dob" id="dob" value="<?= old_value($old, 'dob') ?>" placeholder="dd/mm/yyyy">
        </p>
      </fieldset>

      <hr class="application-form__divider">

      <fieldset class="form-card">
        <legend class="form-card__legend">Your gender</legend>
        <p class="form-field form-field--options">
<?php foreach (gender_options() as $i => $gender): ?>
          <label class="form-option" for="gender<?= $i ?>"><?= e($gender) ?>
            <input class="form-option__control" type="radio" id="gender<?= $i ?>" name="gender" value="<?= e($gender) ?>"<?= (isset($old['gender']) && $old['gender'] === $gender) ? ' checked' : '' ?>>
          </label>
<?php endforeach; ?>
        </p>
      </fieldset>

      <hr class="application-form__divider">

      <fieldset class="form-card">
        <legend class="form-card__legend">Skill lists</legend>
        <p class="form-field form-field--options">
<?php foreach (skill_options() as $value => $label): ?>
          <label class="form-option" for="skill-<?= e($value) ?>"><?= e($label) ?></label>
          <input class="form-option__control" type="checkbox" name="skills[]" id="skill-<?= e($value) ?>" value="<?= e($value) ?>"<?= in_array($value, $old_skills, true) ? ' checked' : '' ?>>
<?php endforeach; ?>
        </p>
        <p class="form-field form-field--textarea-label">
          <label class="form-field__label" for="comments">Explain your position:</label>
        </p>
        <textarea class="form-control form-control--textarea" id="comments" name="comments" placeholder="Write description of your submission here."><?= old_value($old, 'comments') ?></textarea>
        <p class="form-field form-field--textarea-label">
          <label class="form-field__label" for="other_skills">Other skills</label>
        </p>
        <textarea class="form-control form-control--textarea" id="other_skills" name="other_skills" placeholder="Write description of your other potential here."><?= old_value($old, 'other_skills') ?></textarea>
      </fieldset>

      <hr class="application-form__divider">

      <input class="button button--primary" type="submit" value="Submit application">
      <input class="button button--secondary" type="reset" value="Reset Form">
    </form>
  </main>

<?php include 'footer.inc'; ?>
