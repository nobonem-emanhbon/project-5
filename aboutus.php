<?php
require_once __DIR__ . '/functions.php';

$conn = db_connect();
create_about_table($conn);

$members = [];
$result = $conn->query(
    'SELECT member_name, photo, part1_work, part2_work, quote_vi, quote_en, dream_job, coding_snack
     FROM about
     ORDER BY sort_order ASC, id ASC'
);
while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}
$conn->close();

// Keep styles.css before style.css so About-specific classes load without overriding the shared site header/footer variables.
$page_title       = 'G05 Retail | About Us';
$page_description = 'Meet Group 5, the team behind the G05 Retail digital platform, and view each member\'s Part 1 and Part 2 project contributions.';
$active_page      = 'about';
$page_stylesheets = ['styles.css', 'style.css'];

require __DIR__ . '/header.php';
?>

  <main>
    <h1 class="page-title">About Us</h1>
    <p class="page-sub"><u>Meet the team behind the G05 retail platform.</u></p>

    <section class="section">
      <div class="car">
        <h3>Our Group</h3>
        <ul class="group-info">
          <li>Group 5
            <ul>
              <li>Project: G05 Digital Retail Platform (very obvious wordplay)</li>
            </ul>
          </li>
        </ul>
      </div>

      <div>
        <h3>Members and contributions</h3>
        <dl class="members">
<?php foreach ($members as $member): ?>
          <dt class="profile-header">
            <img src="<?= e($member['photo']) ?>" alt="<?= e($member['member_name']) ?>" class="profile-img">
            <span class="profile-name"><?= e($member['member_name']) ?></span>
          </dt>
          <dd><strong>Part 1 Contribution:</strong> <?= e($member['part1_work']) ?></dd>
          <dd><strong>Part 2 Contribution:</strong> <?= e($member['part2_work']) ?></dd>
          <dd class="quote">
            <span class="lang">Vietnamese:</span> &ldquo;<?= e($member['quote_vi']) ?>&rdquo;<br>
            <span class="lang">English:</span> &ldquo;<?= e($member['quote_en']) ?>&rdquo;
          </dd>
<?php endforeach; ?>
        </dl>
      </div>

      <div class="card">
        <h3>Fun Facts</h3>
        <table class="fun-facts">
          <caption><b>Fun facts about each team member</b></caption>
          <thead>
            <tr>
              <th scope="col">Member</th>
              <th scope="col">Dream Job</th>
              <th scope="col">Coding Snack</th>
            </tr>
          </thead>
          <tbody>
<?php foreach ($members as $member): ?>
            <tr>
              <td><?= e($member['member_name']) ?></td>
              <td><?= e($member['dream_job']) ?></td>
              <td><?= e($member['coding_snack']) ?></td>
            </tr>
<?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <h1 class="page-title">Acknowledgement of Country</h1>
    <p class="page-sub"><u>Our acknowledgement towards the Aboriginal and Torres Strait Islander peoples.</u></p>
    <section class="section">
      <div class="card">
        <p><b>We respectfully acknowledge the Wurundjeri People of the Kulin Nation, who are the Traditional Owners of the land on which Swinburne’s Australian campuses are located in Melbourne’s east and outer-east, and pay our respect to their Elders past, present and emerging. We are honoured to recognise our connection to Wurundjeri Country, history, culture and spirituality through these locations, and strive to ensure that we operate in a manner that respects and honours the Elders and Ancestors of these lands. We also respectfully acknowledge Swinburne’s Aboriginal and Torres Strait Islander staff, students, alumni, partners and visitors. We also acknowledge and respect the Traditional Owners of lands across Australia, their Elders, Ancestors, cultures and heritage, and recognise the continuing sovereignties of all Aboriginal and Torres Strait Islander Nations.</b></p>
      </div>
    </section>
  </main>

<?php require __DIR__ . '/footer.php'; ?>
