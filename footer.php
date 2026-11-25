<?php
/**
 * footer.php - Shared site footer and closing HTML tags.
 *
 * Uses $contact_email defined in header.php so contact links stay consistent.
 */
$contact_email = $contact_email ?? 'booking@G05.com';
?>
  <footer class="site-footer" id="contact">
    <div class="footer-content">
      <div class="footer-brand">
        <h2 class="footer-brand__name">G05 Retail</h2>
        <p class="footer-brand__copyright">&copy; <?= htmlspecialchars(date('Y'), ENT_QUOTES, 'UTF-8') ?> G05 Retail. All rights reserved.</p>
      </div>
      <nav class="footer-nav" aria-label="Project and contact links">
        <ul class="footer-links">
          <li class="footer-links__item"><a class="footer-links__link" href="https://ngdinhan08-1790740529378.atlassian.net/browse/SCRUM-5?atlOrigin=eyJpIjoiNzU4MTgwY2Y4MGVkNDBhZGIyZGRjOTU4ZDQ2NzgzZjYiLCJwIjoiaiJ9" target="_blank" rel="noopener">Jira project</a></li>
          <li class="footer-links__item"><a class="footer-links__link" href="https://github.com/nobonem-emanhbon/project-5.git" target="_blank" rel="noopener">GitHub repository</a></li>
          <li class="footer-links__item"><a class="footer-links__link" href="mailto:<?= htmlspecialchars($contact_email, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($contact_email, ENT_QUOTES, 'UTF-8') ?></a></li>
        </ul>
      </nav>
    </div>
  </footer>
</body>
</html>
