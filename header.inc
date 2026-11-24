<?php
/*
 * header.inc - the top of every public page: <head>, site header and navigation.
 *
 * Set these before including it (all optional):
 *   $page_title       text for <title>
 *   $page_description meta description
 *   $active_page      'jobs', 'benefits', 'apply', 'about' ... highlights the nav link
 *   $extra_css        list of extra stylesheets, e.g. ['styles/jobs.css']
 *
 * Part 1 repeated this block by hand in five files, which is how the About page ended up
 * with a different header and the Benefits page with different class names.
 */
$page_title       = $page_title ?? 'G05 Retail';
$page_description = $page_description ?? 'Shop thoughtfully at G05 Retail and explore careers built around great customer service.';
$active_page      = $active_page ?? '';
$extra_css        = $extra_css ?? [];

// One contact address for the nav and the footer. Part 1 showed booking@G05.com
// but the link actually opened a mail to info@companyname.com.
$contact_email = 'booking@G05.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= htmlspecialchars($page_description, ENT_QUOTES, 'UTF-8') ?>">
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="styles/style.css">
<?php foreach ($extra_css as $css_file): ?>
  <link rel="stylesheet" href="<?= htmlspecialchars($css_file, ENT_QUOTES, 'UTF-8') ?>">
<?php endforeach; ?>
</head>
<body>
  <header class="site-header">
    <a class="site-brand" href="index.php" aria-label="G05 Retail home">
      <svg class="site-brand__mark" role="img" aria-labelledby="brand-title" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
        <title id="brand-title">G05 Retail logo</title>
        <path class="site-brand__mark-bg" d="M12 0h16a12 12 0 0 1 12 12v16a12 12 0 0 1-12 12H12A12 12 0 0 1 0 28V12A12 12 0 0 1 12 0Z"/>
        <path class="site-brand__mark-g" d="M27.5 13.3A11 11 0 1 0 29 25h-9v-5h15v2.5A15 15 0 1 1 30.5 10z"/>
        <circle class="site-brand__mark-accent" cx="29" cy="28" r="3"/>
      </svg>
      <span class="site-brand__name">G05 Retail</span>
    </a>
<?php include 'nav.inc'; ?>
  </header>
