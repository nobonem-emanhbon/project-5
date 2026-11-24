<?php
/*
 * nav.inc - main navigation. Included by header.inc, so $active_page and $contact_email
 * are already defined. Adding a page to the menu is now a one-line change here.
 */
$nav_links = [
    'jobs'     => ['Jobs',     'jobs.php'],
    'benefits' => ['Benefits', 'benefits.php'],
    'apply'    => ['Apply',    'apply.php'],
    'about'    => ['About',    'about.php'],
];
?>
    <nav class="site-nav" aria-label="Main navigation">
      <ul class="site-nav__list">
<?php foreach ($nav_links as $key => [$label, $href]): ?>
<?php if ($key === $active_page): ?>
        <li class="site-nav__item"><a class="site-nav__link site-nav__link--active" href="<?= $href ?>" aria-current="page"><?= $label ?></a></li>
<?php else: ?>
        <li class="site-nav__item"><a class="site-nav__link" href="<?= $href ?>"><?= $label ?></a></li>
<?php endif; ?>
<?php endforeach; ?>
        <li class="site-nav__item"><a class="site-nav__link" href="mailto:<?= $contact_email ?>">Contact</a></li>
      </ul>
    </nav>
