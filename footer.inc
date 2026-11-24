<?php
/*
 * footer.inc - footer and closing tags for every public page.
 * $contact_email comes from header.inc.
 */
?>
  <footer class="site-footer" id="contact">
    <div class="footer-content">
      <div class="footer-brand">
        <h2 class="footer-brand__name">G05 Retail</h2>
        <p class="footer-brand__copyright">&copy; <?= date('Y') ?> G05 Retail. All rights reserved.</p>
      </div>
      <nav class="footer-nav" aria-label="Project and contact links">
        <ul class="footer-links">
          <li class="footer-links__item"><a class="footer-links__link" href="https://ngdinhan08-1790740529378.atlassian.net/browse/SCRUM-5?atlOrigin=eyJpIjoiNzU4MTgwY2Y4MGVkNDBhZGIyZGRjOTU4ZDQ2NzgzZjYiLCJwIjoiaiJ9" target="_blank" rel="noopener">Jira project</a></li>
          <li class="footer-links__item"><a class="footer-links__link" href="https://github.com/nobonem-emanhbon/project-5.git" target="_blank" rel="noopener">GitHub repository</a></li>
          <li class="footer-links__item"><a class="footer-links__link" href="mailto:<?= $contact_email ?>"><?= $contact_email ?></a></li>
          <li class="footer-links__item"><a class="footer-links__link" href="manage.php">HR manager login</a></li>
        </ul>
      </nav>
    </div>
  </footer>
</body>
</html>
