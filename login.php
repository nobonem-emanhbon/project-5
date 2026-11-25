<?php
require_once __DIR__ . '/functions.php';
start_secure_session();

if (is_logged_in()) {
    header('Location: manage.php');
    exit;
}

$conn = db_connect();
create_users_table($conn);

$error = '';
$username_value = '';
$flash = flash_take();

const MAX_FAILED_LOGINS = 5;
const LOCKOUT_MINUTES   = 5;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted_token = $_POST['csrf_token'] ?? '';
    $username_value  = tidy_input($_POST['username'] ?? '');
    $password        = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

    if (!csrf_is_valid($submitted_token)) {
        $error = 'Your session expired. Please submit the login form again.';
    } elseif ($username_value === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $conn->prepare(
            'SELECT id, username, password_hash, failed_attempts, locked_until
             FROM users
             WHERE username = ?'
        );
        $stmt->bind_param('s', $username_value);
        $stmt->execute();
        $user_row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $now = new DateTimeImmutable();
        $is_locked = false;
        if ($user_row && !empty($user_row['locked_until'])) {
            $locked_until = new DateTimeImmutable($user_row['locked_until']);
            if ($locked_until > $now) {
                $is_locked = true;
                $error = 'Too many failed attempts. Please try again after ' . $locked_until->format('H:i:s') . '.';
            }
        }

        if (!$is_locked) {
            if ($user_row && password_verify($password, $user_row['password_hash'])) {
                $reset_stmt = $conn->prepare('UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?');
                $reset_stmt->bind_param('i', $user_row['id']);
                $reset_stmt->execute();
                $reset_stmt->close();

                session_regenerate_id(true);
                $_SESSION['user_id']       = (int) $user_row['id'];
                $_SESSION['username']      = $user_row['username'];
                $_SESSION['last_activity'] = time();

                $conn->close();
                header('Location: manage.php');
                exit;
            }

            if ($user_row) {
                $attempts = (int) $user_row['failed_attempts'] + 1;
                if ($attempts >= MAX_FAILED_LOGINS) {
                    $lock_time = $now->modify('+' . LOCKOUT_MINUTES . ' minutes')->format('Y-m-d H:i:s');
                    $upd = $conn->prepare('UPDATE users SET failed_attempts = ?, locked_until = ? WHERE id = ?');
                    $upd->bind_param('isi', $attempts, $lock_time, $user_row['id']);
                } else {
                    $upd = $conn->prepare('UPDATE users SET failed_attempts = ?, locked_until = NULL WHERE id = ?');
                    $upd->bind_param('ii', $attempts, $user_row['id']);
                }
                $upd->execute();
                $upd->close();
            }

            $error = 'Invalid username or password.';
        }
    }
}

$conn->close();
$token = csrf_token();

$page_title       = 'G05 Retail | HR Manager Login';
$page_description = 'Sign in to access the G05 Retail HR manager EOI management portal.';
$active_page      = '';

require __DIR__ . '/header.php';
?>

  <main>
    <section class="card login-card" aria-labelledby="login-title">
      <h1 class="card__title" id="login-title">HR Manager Login</h1>
      <p class="application-page__intro" style="width: 100%; margin-bottom: 16px;">Sign in to review, filter, update, or delete Expressions of Interest.</p>

<?php if ($flash): ?>
      <div class="message message--<?= e($flash['type']) ?>" style="width: 100%;" role="status">
        <?= e($flash['text']) ?>
      </div>
<?php endif; ?>

<?php if ($error !== ''): ?>
      <div class="message message--error" style="width: 100%;" role="alert">
        <?= e($error) ?>
      </div>
<?php endif; ?>

      <form method="post" action="login.php">
        <input type="hidden" name="csrf_token" value="<?= e($token) ?>">
        <p class="form-field" style="grid-template-columns: 1fr; gap: 6px;">
          <label class="form-field__label" for="username">Username</label>
          <input class="form-control" type="text" id="username" name="username" value="<?= e($username_value) ?>" autocomplete="username" required>
        </p>
        <p class="form-field" style="grid-template-columns: 1fr; gap: 6px;">
          <label class="form-field__label" for="password">Password</label>
          <input class="form-control" type="password" id="password" name="password" autocomplete="current-password" required>
        </p>
        <button class="button button--primary" type="submit" style="width: 100%; margin-top: 8px;">Sign in</button>
      </form>
    </section>
  </main>

<?php require __DIR__ . '/footer.php'; ?>
