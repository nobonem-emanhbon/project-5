<?php
/**
 * header.php - Shared document <head>, brand header, and primary navigation bar.
 *
 * Expected optional variables set by each page before requiring this file:
 *   $page_title       (string)   Browser tab <title>
 *   $page_description (string)   Meta description for SEO and accessibility
 *   $active_page      (string)   Key for active nav item ('home', 'jobs', 'benefits', 'apply', 'about')
 *   $page_stylesheets (string[]) Ordered list of CSS files to load in <head>
 */
$page_title       = $page_title ?? 'G05 Retail';
$page_description = $page_description ?? 'Shop thoughtfully at G05 Retail and explore careers built around great customer service.';
$active_page      = $active_page ?? '';
$page_stylesheets = $page_stylesheets ?? ['style.css'];

// Single contact address shared between header.php and footer.php so links never drift apart.
$contact_email = 'booking@G05.com';

// Primary navigation links defined in one place so menu updates apply across every page.
$nav_links = [
    'jobs'     => ['Jobs',     'jobs.php'],
    'benefits' => ['Benefits', 'benefits.php'],
    'apply'    => ['Apply',    'apply.php'],
    'about'    => ['About',    'aboutus.php'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8') ?>">
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
<?php foreach ($page_stylesheets as $stylesheet): ?>
  <link rel="stylesheet" href="<?= htmlspecialchars($stylesheet, ENT_QUOTES, 'UTF-8') ?>">
<?php endforeach; ?>
</head>
<body>
  <header class="site-header">
    <a class="site-brand" href="index.php" aria-label="G05 Retail home"<?= ($active_page === 'home') ? ' aria-current="page"' : '' ?>>
      <svg class="site-brand__mark" role="img" aria-labelledby="brand-title" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
        <title id="brand-title">G05 Retail logo</title>
        <path class="site-brand__mark-bg" d="M12 0h16a12 12 0 0 1 12 12v16a12 12 0 0 1-12 12H12A12 12 0 0 1 0 28V12A12 12 0 0 1 12 0Z"/>
        <path class="site-brand__mark-g" d="M27.5 13.3A11 11 0 1 0 29 25h-9v-5h15v2.5A15 15 0 1 1 30.5 10z"/>
        <circle class="site-brand__mark-accent" cx="29" cy="28" r="3"/>
      </svg>
      <span class="site-brand__name">G05 Retail</span>
    </a>
    <nav class="site-nav" aria-label="Main navigation">
      <ul class="site-nav__list">
<?php foreach ($nav_links as $page_key => [$label, $href]): ?>
<?php if ($page_key === $active_page): ?>
        <li class="site-nav__item"><a class="site-nav__link site-nav__link--active" href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>" aria-current="page"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a></li>
<?php else: ?>
        <li class="site-nav__item"><a class="site-nav__link" href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></a></li>
<?php endif; ?>
<?php endforeach; ?>
        <li class="site-nav__item"><a class="site-nav__link" href="mailto:<?= htmlspecialchars($contact_email, ENT_QUOTES, 'UTF-8') ?>">Contact</a></li>
      </ul>
    </nav>
  </header>
