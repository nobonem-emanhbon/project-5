<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Demonstrates some basic HTML form elements">
  <meta name="keywords" content="HTML, Form, tags">
  <meta name="author" content="I require therapy this instant.">
  <title>G05 Retail | Apply</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'header.php'; ?>

  <h1 class="application-page__title">G05 Application form</h1>
  <p class="notice">The Help Desk is located in HQ at the topmost floor. Open 10:30 to 19:00 Monday to Friday. Check Blackboard for when our evaluators will be there.</p>

  <form class="application-form" method="post" action="http://mercury.swin.edu.au/it000000/formtest.php">
    <!--Note we have to use a special escape character to print an apostrophe on the Web page -->
    <fieldset class="form-card">
      <legend class="form-card__legend">Personal info</legend>
      <p class="form-field">
        <label class="form-field__label" for="JobID">Specify your job reference number: </label>
        <input class="form-control" type="text" name="JobID" id="JobID" maxlength="5" required pattern="([A-Z]{2}[\d]{3})">
      </p>

      <p class="form-field form-field--name">
        <label class="form-field__label" for="Fname">First Name: </label>
        <input class="form-control" type="text" name="Fname" id="Fname" maxlength="20" required pattern="([A-Z]{1}[a-z]{0,19})">
        <label class="form-field__label" for="Lname">Last Name: </label>
        <input class="form-control" type="text" name="Lname" id="Lname" maxlength="20" required pattern="([A-Z]{1}[a-z]{0,19})">
      </p>

      <p class="form-field">
        <label class="form-field__label" for="Mail">Your email: </label>
        <input class="form-control" type="email" name="Mail" id="Mail" required>
      </p>

      <p class="form-field">
        <label class="form-field__label" for="PN">Your Phone number: </label>
        <input class="form-control" type="text" name="PN" id="PN" maxlength="12" required pattern="^[0-9]*">
      </p>

      <p class="form-field form-field--address">
        <label class="form-field__label" for="addr">Street address: </label>
        <input class="form-control" type="text" name="addr" id="addr" maxlength="40" required>
        <label class="form-field__label" for="town">Suburb/town: </label>
        <input class="form-control" type="text" name="town" id="town" maxlength="40" required>
        <label class="form-field__label" for="code">Postcode: </label>
        <input class="form-control" type="text" name="code" id="code" maxlength="4" required pattern="^[0-9]*">
      </p>

      <p class="form-field">
        <label class="form-field__label" for="tutor">Your state: </label>
        <select class="form-control" name="tutor" id="tutor" required>
          <option value="">Please Select</option>
          <option value="VIC" required>VIC</option>
          <option value="NSW" required>NSW</option>
          <option value="QLD" required>QLD</option>
          <option value="NT" required>NT</option>
          <option value="WA" required>WA</option>
          <option value="SA" required>SA</option>
          <option value="TAS" required>TAS</option>
          <option value="ACT" required>ACT</option>
        </select>
      </p>

      <p class="form-field">
        <label class="form-field__label" for="date">Date of birth:</label>
        <input class="form-control" type="date" name="date" id="date" placeholder="dd-mm-yyyy" required>
      </p>
    </fieldset>

    <hr class="application-form__divider">

    <fieldset class="form-card">
      <legend class="form-card__legend">Your gender</legend>
      <p class="form-field form-field--options">
        <label class="form-option" for="sub1">Male
          <input class="form-option__control" type="radio" id="sub1" name="unit" value="sub1" required>
        </label>
        <label class="form-option" for="sub2">Female
          <input class="form-option__control" type="radio" id="sub2" name="unit" value="sub2" required>
        </label>
        <label class="form-option" for="sub3">Non-binary
          <input class="form-option__control" type="radio" id="sub3" name="unit" value="sub3" required>
        </label>
      </p>
    </fieldset>

    <hr class="application-form__divider">

    <fieldset class="form-card">
      <legend class="form-card__legend">Skill lists</legend>
      <p class="form-field form-field--options">
        <label class="form-option" for="HTML">Cashier</label>
        <input class="form-option__control" type="checkbox" name="topic" id="HTML" value="HTML">
        <label class="form-option" for="CSS">Store Manager</label>
        <input class="form-option__control" type="checkbox" name="topic" id="CSS" value="CSS">
        <label class="form-option" for="JS">Advertising &amp; Marketing</label>
        <input class="form-option__control" type="checkbox" name="topic" id="JS" value="JS">
        <label class="form-option" for="PHP">Janitor</label>
        <input class="form-option__control" type="checkbox" name="topic" id="PHP" value="PHP">
        <label class="form-option" for="MSQL">Stocker</label>
        <input class="form-option__control" type="checkbox" name="topic" id="MSQL" value="MSQL">
        <label class="form-option" for="M">Other</label>
        <input class="form-option__control" type="checkbox" name="topic" id="M" value="M">
      </p>
      <p class="form-field form-field--textarea-label">
        <label class="form-field__label" for="comments">Explain your position:</label>
      </p>
      <textarea class="form-control form-control--textarea" id="comments" name="comments" placeholder="Write description of your submission here." required></textarea>
      <p class="form-field form-field--textarea-label">
        <label class="form-field__label" for="other-skills">Other skills</label>
      </p>
      <textarea class="form-control form-control--textarea" id="other-skills" name="comments" placeholder="Write description of your other potential here."></textarea>
    </fieldset>

    <hr class="application-form__divider">

    <input class="button button--primary" type="submit" value="Book">
    <input class="button button--secondary" type="reset" value="Reset Form">
  </form>

  <?php include 'footer.php'; ?>
</body>
</html>
