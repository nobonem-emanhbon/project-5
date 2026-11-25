# G05 Retail — Applied Web Project (Part 2)

Group 5 dynamic recruitment web application built with modular PHP and MySQL (XAMPP / MariaDB).

## Group Members & Contributions
- **Cù Nguyễn Huy Minh** — Team leader; modular PHP templates (`header.php`, `footer.php`), `index.php`, and database-driven `jobs.php` with search.
- **Nguyễn Đình An** — Database-driven `aboutus.php`, `benefits.php`, and HR manager authentication & management portal (`login.php`, `logout.php`, `manage.php`).
- **Lê Đắc Phúc** — Database-backed `apply.php`, `eoi` table, server-side validation & sanitisation (`process_eoi.php`), and final GUI polish.

## Local Setup (XAMPP)
1. Start **Apache** and **MySQL** in XAMPP Control Panel.
2. Connection settings are configured in `settings.php` (`host: localhost`, `user: root`, `pwd: ""`, `sql_db: g05_retail`).
3. Visit `http://localhost/project-5/index.php` — the database and all four tables (`jobs`, `about`, `eoi`, `users`) are created and seeded automatically on first load.

## Key Pages
- `index.php` — Home page, store hours, and job search bar.
- `jobs.php` — Dynamic job openings loaded from MySQL (`jobs` table) with live keyword search.
- `benefits.php` — Employee benefits and career pathway.
- `apply.php` & `process_eoi.php` — Job application form with full server-side validation, sanitisation, prepared statements, and direct-access protection.
- `aboutus.php` — Group member profiles, Part 1 & Part 2 contributions, and Fun Facts loaded from MySQL (`about` table).
- `login.php`, `logout.php`, `manage.php` — HR Manager portal (default login: `admin` / `admin`) to list, filter, sort, update status, and delete EOIs by job reference.
