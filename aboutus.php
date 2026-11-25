<?php
// Keep styles.css before style.css so About-specific classes load without overriding the shared site header/footer variables.
$page_title       = 'G05 Retail | About Us';
$page_description = 'Meet Group 5, the team behind the G05 Retail digital platform, and view each member\'s project contributions.';
$active_page      = 'about';
$page_stylesheets = ['styles.css', 'style.css'];

require __DIR__ . '/header.php';
?>

  <main>
    <h1 class="page-title">About Us</h1>
    <p class="page-sub"><u>Meet the team behin the G05 retail platform.</u></p>

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
          <dt class="profile-header">
            <img src="CuNguyenHuyMinh.jpg" alt="Cù Nguyễn Huy Minh" class="profile-img">
            <span class="profile-name">Cù Nguyễn Huy Minh</span>
          </dt>
          <dd><strong>Part 1 Contribution:</strong> Team leader, Index page, job page and Layout</dd>
          <dd><strong>Part 2 Contribution:</strong> Team leader, modular PHP conversion of Index page (<code>index.php</code>), Jobs page (<code>jobs.php</code>), and shared layout templates (<code>header.php</code>, <code>footer.php</code>)</dd>
          <dd class="quote">
            <span class="lang">Vietnamese:</span> "Một cây làm chẳng nên non, ba cây chụm lại nên hòn núi cao."<br>
            <span class="lang">English:</span> One tree alone cannot make a hill, but three trees together make a high mountain
          </dd>

          <dt class="profile-header">
            <img src="NguyenDinhAn.jpg" alt="Nguyễn Đình An" class="profile-img">
            <span class="profile-name">Nguyễn Đình An</span>
          </dt>
          <dd><strong>Part 1 Contribution:</strong> About page, benefits page</dd>
          <dd><strong>Part 2 Contribution:</strong> Modular PHP conversion of About page (<code>aboutus.php</code>) and Benefits page (<code>benefits.php</code>), and updating group member contributions</dd>
          <dd class="quote">
            <span class="lang">Vietnamese:</span> "Không có gì quý hơn độc lập, tự do"<br>
            <span class="lang">English:</span> "Nothing is more precious than independence and freedom."
          </dd>

          <dt class="profile-header">
            <img src="Ledacphuc.jpg" alt="Lê Đắc Phúc" class="profile-img">
            <span class="profile-name">Lê Đắc Phúc</span>
          </dt>
          <dd><strong>Part 1 Contribution:</strong> Apply page, final touches of the GUI</dd>
          <dd><strong>Part 2 Contribution:</strong> Modular PHP conversion of Apply page (<code>apply.php</code>) and final touches of the GUI</dd>
          <dd class="quote">
            <span class="lang">Vietnamese:</span> "Học đi đôi với hành  "<br>
            <span class="lang">English:</span> "Learning goes hand in hand with practice."
          </dd>
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
            <tr><td>Cù Nguyễn Huy Minh</td><td>Game developer</td><td>Energy drink</td></tr>
            <tr><td>Nguyễn Đình An</td><td>Game &amp; Web developer</td><td>Mint candy</td></tr>
            <tr><td>Lê Đắc Phúc</td><td>Data scientist</td><td>Water</td></tr>
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
