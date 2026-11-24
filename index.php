<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Shop thoughtfully at G05 Retail and explore careers built around great customer service.">
  <title>G05 Retail | Everyday shopping, made better</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php include 'header.php'; ?>

  <main class="home-main">
    <section class="home-hero" id="hero" aria-labelledby="home-title">
      <div class="home-hero__copy">
        <p class="eyebrow">A better everyday starts here</p>
        <h1 class="home-hero__title" id="home-title">G05 Retail</h1>
        <p class="home-hero__slogan">Good finds. Great people. Every day.</p>
        <p class="home-hero__description">We’re a neighborhood-minded retail store bringing useful products and thoughtful service together. Our team makes every visit welcoming, and we’re always looking for people who care about helping customers. Find your next opportunity and grow with us.</p>
      </div>
      <div class="home-hero__art">
        <svg class="home-hero__illustration" role="img" aria-labelledby="hero-art-title" viewBox="0 0 520 390" xmlns="http://www.w3.org/2000/svg">
          <title id="hero-art-title">Illustration of a welcoming retail storefront and shopping bags</title>
          <path class="home-hero__scene" d="M48 18h424a32 32 0 0 1 32 32v290a32 32 0 0 1-32 32H48a32 32 0 0 1-32-32V50a32 32 0 0 1 32-32Z"/>
          <circle class="home-hero__sun" cx="412" cy="91" r="34"/>
          <path class="home-hero__store" d="M74 146h372v174H74z"/>
          <path class="home-hero__awning" d="M61 146 92 82h336l31 64H61z"/>
          <path class="home-hero__awning-light" d="M100 91h42v55h-68l26-55Zm84 0h42v55h-42V91Zm84 0h42v55h-42V91Zm84 0h42l26 55h-68V91Z"/>
          <path class="home-hero__window" d="M92 185h105v135H92z"/>
          <path class="home-hero__door" d="M222 184h174v136H222z"/>
          <path class="home-hero__display" d="M244 206h53v55h-53zM318 206h54v55h-54z"/>
          <path class="home-hero__door-panel" d="M128 215h33v105h-33z"/>
          <circle class="home-hero__handle" cx="153" cy="270" r="3"/>
          <path class="home-hero__bag" d="M235 277h62v43h-62zM308 277h62v43h-62z"/>
          <path class="home-hero__bag-handle" d="M248 277c0-13 8-21 18-21s18 8 18 21M321 277c0-13 8-21 18-21s18 8 18 21"/>
          <path class="home-hero__ground-line" d="M68 320h385"/>
          <path class="home-hero__accent-bag" d="M391 260h62l-7 68h-48z"/>
          <path class="home-hero__accent-handle" d="M403 260c0-13 7-21 19-21s19 8 19 21"/>
          <path class="home-hero__accent-mark" d="M420 285v29m-13-14h26"/>
          <path class="home-hero__shadow" d="M58 329h404"/>
        </svg>
      </div>
    </section>

    <section class="card home-card" id="about" aria-labelledby="about-title">
        <div class="home-card__text">
          <h2 class="card__title" id="about-title">A great place to shop—and work</h2>
          <p class="home-card__copy">We believe the best retail experiences are built by helpful people, quality essentials, and a store that feels easy to explore. At G05 Retail, every team member plays a part in making customers feel valued.</p>
        </div>
        <div class="home-card__image">
          <img src="Illustration-people-shop.jpg" alt="Retail Illustration">
        </div>
    </section>
    <section class="card home-card" aria-labelledby="careers-title">
      <h2 class="card__title" id="careers-title">Join our team</h2>
      <p class="home-card__copy">Bring your curiosity, reliability, and care for customers. Explore our selection and take the first step towards a rewarding role at G05 Retail.</p>
      <a class="button button--primary" href="jobs.php">Explore careers</a>  <a class="button button--primary" href="apply.php">Apply now</a>
    </section>
    <form class="job-search card" id="search" role="search" action="jobs.php" method="get">
      <label class="job-search__label" for="job-search-input">Find your next opportunity</label>
      <div class="job-search__controls">
        <input class="form-control job-search__input" id="job-search-input" name="q" type="search" placeholder="Search jobs, departments..." autocomplete="off">
        <button class="button button--primary" type="submit">Search</button>
      </div>
    </form>

    <section class="card home-card" id="hours-table" aria-labelledby="hours-title">
      <h2 class="card__title" id="hours-title">Opening hours and departments</h2>
      <div class="table-scroll">
        <table class="hours-table">
          <caption><b>Store hours by department</b></caption>
          <thead>
            <tr>
              <th scope="col">Department</th>
              <th scope="col">Monday–Friday</th>
              <th scope="col">Weekend</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th class="hours-table__merged" colspan="3" scope="row">Visit us in store</th>
            </tr>
            <tr>
              <th class="hours-table__category" rowspan="2" scope="rowgroup">Retail floor</th>
              <td>9:00 am–7:00 pm</td>
              <td>10:00 am–5:00 pm</td>
            </tr>
            <tr>
              <td>Customer service available all day</td>
              <td>Customer service available all day</td>
            </tr>
            <tr>
              <th scope="row">Help desk</th>
              <td>10:30 am–7:00 pm</td>
              <td>Closed</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </main>

  <?php include 'footer.php'; ?>
</body>
</html>
