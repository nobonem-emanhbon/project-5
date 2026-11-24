<?php
/*
 * functions.php - shared helpers: database connection, table setup, sessions and login checks.
 *
 * This is a .php file (not .inc) on purpose. Web servers usually show .inc files as plain text,
 * and this one contains the seed data. A .php file that is opened directly just runs and prints nothing.
 *
 * Needs PHP 7.3 or newer.
 */

require_once __DIR__ . '/settings.php';

/* ------------------------------------------------------------------
 * Small output / input helpers
 * ------------------------------------------------------------------ */

/**
 * Escape text for HTML output.
 * double_encode is false because values saved by process_eoi.php are already escaped,
 * and we don't want "O&#039;Brien" to show up on screen.
 */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false);
}

/** Trim and strip slashes. Used first so validation looks at what the person actually typed. */
function tidy_input($value): string
{
    if (!is_string($value)) {
        return '';
    }
    return trim(stripslashes($value));
}

/** Escape HTML for storage (the "sanitising" step the brief asks for). */
function html_clean(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Split a multi-line database text field into a clean array (one item per line). */
function split_lines(string $text): array
{
    $lines = preg_split('/\R/u', $text);
    $lines = array_map('trim', $lines === false ? [] : $lines);
    return array_values(array_filter($lines, function ($line) {
        return $line !== '';
    }));
}

/* ------------------------------------------------------------------
 * Database connection
 * ------------------------------------------------------------------ */

/**
 * Open a connection using settings.php. If the database doesn't exist yet it is created,
 * so the site works on a fresh local server without importing anything by hand.
 * Real error details go to the server log, never to the visitor.
 */
function db_connect(): mysqli
{
    global $host, $user, $pwd, $sql_db;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        try {
            $conn = new mysqli($host, $user, $pwd, $sql_db);
        } catch (mysqli_sql_exception $ex) {
            if ((int) $ex->getCode() !== 1049) {   // 1049 = unknown database
                throw $ex;
            }
            $conn = new mysqli($host, $user, $pwd);
            $safe_name = str_replace('`', '', $sql_db);
            $conn->query("CREATE DATABASE IF NOT EXISTS `$safe_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $conn->select_db($safe_name);
        }
        $conn->set_charset('utf8mb4');
        return $conn;
    } catch (mysqli_sql_exception $ex) {
        error_log('G05 database connection failed: ' . $ex->getMessage());
        http_response_code(500);
        exit('<p>Sorry, we could not reach the database right now. Please try again in a moment.</p>');
    }
}

/* ------------------------------------------------------------------
 * Tables. Each one is created only if it is missing, then seeded if empty.
 * ------------------------------------------------------------------ */

function create_jobs_table(mysqli $conn): void
{
    // Same fields as the Part 1 jobs page. The two bullet lists and the responsibilities
    // are stored one item per line so the page can turn them back into <li> elements.
    $conn->query("CREATE TABLE IF NOT EXISTS jobs (
        reference        CHAR(5)       NOT NULL PRIMARY KEY,
        title            VARCHAR(100)  NOT NULL,
        image            VARCHAR(100)  NOT NULL,
        image_alt        VARCHAR(255)  NOT NULL,
        employment_type  VARCHAR(20)   NOT NULL,
        summary          TEXT          NOT NULL,
        salary           VARCHAR(255)  NOT NULL,
        reports_to       VARCHAR(100)  NOT NULL,
        responsibilities TEXT          NOT NULL,
        essential        TEXT          NOT NULL,
        preferred        TEXT          NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $count = $conn->query('SELECT COUNT(*) AS total FROM jobs')->fetch_assoc();
    if ((int) $count['total'] > 0) {
        return;
    }

    $jobs = [
        [
            'RM101', 'Store Manager', 'images/storemanager.jpg',
            'Illustration of a store manager holding a clipboard outside a retail store',
            'Full-time',
            'Lead day-to-day operations at our retail store and support a team committed to friendly, reliable customer service. You will help meet store targets while maintaining safe, well-presented departments.',
            'AUD $68,000–$78,000 per year plus superannuation',
            'Regional Operations Manager',
            "Coordinate daily store operations, opening and closing procedures, and floor coverage.\n"
            . "Coach, roster, and support team members to deliver consistent customer service.\n"
            . "Monitor sales, stock levels, and operating costs; report results to the regional manager.\n"
            . "Maintain merchandising, cleanliness, and workplace health and safety standards.\n"
            . "Resolve customer enquiries and escalate complex concerns appropriately.",
            "At least two years’ retail supervisory or management experience.\n"
            . "Strong communication, organisation, and customer service skills.\n"
            . "Availability to work a rotating roster, including some weekends.\n"
            . "Australian working rights for full-time employment.",
            "Experience using point-of-sale and inventory systems.\n"
            . "Certificate IV in Retail Management or equivalent training.\n"
            . "Experience supporting staff development and performance reviews.",
        ],
        [
            'CS204', 'Cashier', 'images/cashier.jpg',
            'Illustration of a cashier serving a customer at a checkout counter',
            'Casual',
            'Help customers complete their purchases accurately and make each checkout interaction welcoming. This casual role works across a roster that may include evenings and weekends.',
            'AUD $31.20–$35.00 per hour plus superannuation, depending on experience and applicable award rates',
            'Customer Service Supervisor',
            "Process purchases, refunds, and exchanges accurately using the point-of-sale system.\n"
            . "Greet customers and respond to routine questions about products and services.\n"
            . "Handle cash and electronic payments in line with store procedures.\n"
            . "Keep the checkout area clean, stocked, and ready for customers.\n"
            . "Contact a supervisor when an enquiry or transaction needs further assistance.",
            "Clear communication and a polite, customer-focused approach.\n"
            . "Care and accuracy when handling transactions.\n"
            . "Availability for a flexible casual roster, including weekends.\n"
            . "Australian working rights for casual employment.",
            "Previous retail, hospitality, or cashier experience.\n"
            . "Familiarity with point-of-sale systems and electronic payments.\n"
            . "First aid training or willingness to complete relevant training.",
        ],
    ];

    $stmt = $conn->prepare('INSERT INTO jobs (reference, title, image, image_alt, employment_type, summary, salary, reports_to, responsibilities, essential, preferred)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    foreach ($jobs as $job) {
        $stmt->bind_param(str_repeat('s', count($job)), ...$job);
        $stmt->execute();
    }
    $stmt->close();
}

function create_eoi_table(mysqli $conn): void
{
    // One column per field on the apply form, plus status. The text columns are wider than the
    // form limits because values are HTML-escaped before saving (an apostrophe becomes &#039;).
    $conn->query("CREATE TABLE IF NOT EXISTS eoi (
        EOInumber   INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
        job_ref     CHAR(5)       NOT NULL,
        first_name  VARCHAR(120)  NOT NULL,
        last_name   VARCHAR(120)  NOT NULL,
        dob         DATE          NOT NULL,
        gender      VARCHAR(20)   NOT NULL,
        street      VARCHAR(255)  NOT NULL,
        suburb      VARCHAR(255)  NOT NULL,
        state       VARCHAR(3)    NOT NULL,
        postcode    CHAR(4)       NOT NULL,
        email       VARCHAR(320)  NOT NULL,
        phone       VARCHAR(20)   NOT NULL,
        skills      VARCHAR(255)  NOT NULL DEFAULT '',
        other_skills TEXT         NOT NULL,
        comments    TEXT          NOT NULL,
        status      ENUM('New', 'Current', 'Final') NOT NULL DEFAULT 'New',
        created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_job_ref (job_ref)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

function create_users_table(mysqli $conn): void
{
    // Passwords are stored as hashes, never as plain text. The two extra columns support
    // the "too many wrong passwords" lock-out in login.php.
    $conn->query("CREATE TABLE IF NOT EXISTS users (
        id              INT UNSIGNED  NOT NULL AUTO_INCREMENT PRIMARY KEY,
        username        VARCHAR(50)   NOT NULL UNIQUE,
        password_hash   VARCHAR(255)  NOT NULL,
        failed_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
        locked_until    DATETIME      NULL DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $stmt = $conn->prepare('SELECT COUNT(*) AS total FROM users WHERE username = ?');
    $name = 'admin';
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $found = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ((int) $found['total'] === 0) {
        // admin / admin is only here because the marker needs a way in.
        // On a real site you would change this password straight away.
        $hash = password_hash('admin', PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
        $stmt->bind_param('ss', $name, $hash);
        $stmt->execute();
        $stmt->close();
    }
}

function create_about_table(mysqli $conn): void
{
    $conn->query("CREATE TABLE IF NOT EXISTS about (
        id            INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        member_name   VARCHAR(100) NOT NULL,
        photo         VARCHAR(100) NOT NULL,
        part1_work    TEXT         NOT NULL,
        part2_work    TEXT         NOT NULL,
        quote_vi      VARCHAR(255) NOT NULL,
        quote_en      VARCHAR(255) NOT NULL,
        dream_job     VARCHAR(100) NOT NULL,
        coding_snack  VARCHAR(100) NOT NULL,
        sort_order    TINYINT UNSIGNED NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $count = $conn->query('SELECT COUNT(*) AS total FROM about')->fetch_assoc();
    if ((int) $count['total'] > 0) {
        return;
    }

    /*
     * EDIT THESE ROWS to match who really did what.
     * Part 1 contributions come from the Part 1 About page. The Part 2 lines are a
     * draft that follows each person's Part 1 area, so please check them with the group.
     * Each line (one per line) becomes a bullet on the About page.
     */
    $members = [
        [
            'Cù Nguyễn Huy Minh', 'images/CuNguyenHuyMinh.jpg',
            "Index page and layout",
            "Moved the shared header, navigation and footer into header.inc, nav.inc and footer.inc\n"
            . "Converted index.html and benefits.html to PHP pages and merged the stylesheets",
            'Một cây làm chẳng nên non, ba cây chụm lại nên hòn núi cao.',
            'One tree alone cannot make a hill, but three trees together make a high mountain.',
            'Game developer', 'Energy drink', 1,
        ],
        [
            'Nguyễn Đình An', 'images/NguyenDinhAn.jpg',
            "About page",
            "Built the about table and loads the member details from the database\n"
            . "Built the login page and the HR manager page (manage.php)",
            'Không có gì quý hơn độc lập, tự do.',
            'Nothing is more precious than independence and freedom.',
            'Game & Web developer', 'Mint candy', 2,
        ],
        [
            'Lê Đắc Phúc', 'images/Ledacphuc.jpg',
            "Jobs and Apply pages",
            "Created the jobs table and the search on jobs.php\n"
            . "Created the eoi table and process_eoi.php with server-side validation",
            'Học đi đôi với hành.',
            'Learning goes hand in hand with practice.',
            'Data scientist', 'Water', 3,
        ],
    ];

    $stmt = $conn->prepare('INSERT INTO about (member_name, photo, part1_work, part2_work, quote_vi, quote_en, dream_job, coding_snack, sort_order)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    foreach ($members as $m) {
        $stmt->bind_param('ssssssssi', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8]);
        $stmt->execute();
    }
    $stmt->close();
}

/** Job references as [reference => title], for dropdowns and lookups. */
function get_job_options(mysqli $conn): array
{
    $options = [];
    $result = $conn->query('SELECT reference, title FROM jobs ORDER BY reference');
    while ($row = $result->fetch_assoc()) {
        $options[$row['reference']] = $row['title'];
    }
    return $options;
}

/* ------------------------------------------------------------------
 * Sessions, flash messages, CSRF and login checks
 * ------------------------------------------------------------------ */

const SESSION_IDLE_LIMIT = 1800;   // log the manager out after 30 minutes of doing nothing

function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    // A custom name stops this site fighting with other projects on the same localhost
    // that also use the default PHPSESSID cookie.
    session_name('g05_session');
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']),
        'httponly' => true,       // cookie can't be read by scripts
        'samesite' => 'Lax',
    ]);
    session_start();
}

/** Store a one-time message to show on the next page. */
function flash_set(string $type, string $text): void
{
    $_SESSION['flash'] = ['type' => $type, 'text' => $text];
}

/** Read and clear the one-time message (returns null if there is none). */
function flash_take(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

/** Token that proves a form came from our own page (protects against cross-site requests). */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_is_valid($submitted): bool
{
    return is_string($submitted)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $submitted);
}

function is_logged_in(): bool
{
    if (empty($_SESSION['user_id'])) {
        return false;
    }
    if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > SESSION_IDLE_LIMIT) {
        return false;
    }
    $_SESSION['last_activity'] = time();
    return true;
}

/** Send visitors who aren't logged in to the login page. Call this before any output. */
function require_login(): void
{
    if (!is_logged_in()) {
        session_unset();
        header('Location: login.php');
        exit;
    }
}

/* ------------------------------------------------------------------
 * Option lists shared by apply.php (draws the form) and process_eoi.php (checks it)
 * Keeping them in one place means the form and the validation can't drift apart.
 * ------------------------------------------------------------------ */

function state_options(): array
{
    return ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'];
}

function gender_options(): array
{
    return ['Male', 'Female', 'Non-binary'];
}

/** value => label for the skills checkboxes. */
function skill_options(): array
{
    return [
        'cashier'       => 'Cashier',
        'store_manager' => 'Store Manager',
        'marketing'     => 'Advertising & Marketing',
        'janitor'       => 'Janitor',
        'stocker'       => 'Stocker',
        'other'         => 'Other',
    ];
}
