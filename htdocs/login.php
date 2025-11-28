<?php
session_start();
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    header('Location: view.php?page=home');
    exit();
}
if (!isset($_SESSION['terms_of_use']) || $_SESSION['terms_of_use'] !== true) {
    header('Location: index.php');
    exit();
}

ob_start();

include 'libs/database.php';
include 'config/database.php';

$db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);

$errors = [];
$errors = array_merge($errors, handleLoginForm($db));
$errors = array_merge($errors, handleRegisterForm($db));

$csrfToken = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Andromeda — Sign in</title>
        <link rel="stylesheet" type="text/css" href="styles/default.css" />
        <link rel="stylesheet" type="text/css" href="styles/login.css" />
    </head>
    <body class="auth-body">
        <main class="auth-container">
            <section class="auth-intro">
                <img src="img/logo.png" alt="Andromeda" class="auth-logo" />
                <h1>Welcome back, pilot</h1>
                <p>Access your hangar, manage your clan, and keep track of the latest activity across the Andromeda universe.</p>
                <ul class="intro-highlights">
                    <li>Responsive command center built for every device.</li>
                    <li>Secure authentication powered by modern encryption.</li>
                    <li>Streamlined workflows for clans, upgrades, and trading.</li>
                </ul>
                <p class="intro-support">Need help? <a href="view.php?page=contact">Contact the team</a> or revisit the <a href="index.php">terms of use</a>.</p>
            </section>
            <section class="auth-content">
                <?php if (!empty($errors)) { ?>
                <div class="auth-alert" role="alert">
                    <h2>We couldn't complete your request</h2>
                    <ul>
                        <?php foreach ($errors as $message) { ?>
                            <li><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
                <div class="form-grid">
                    <form id="login_form" class="auth-card" action="login.php" method="post" novalidate>
                        <h2>Sign in</h2>
                        <p class="form-description">Enter your credentials to access your account dashboard.</p>
                        <div class="form-field">
                            <label for="login-username">Username</label>
                            <input id="login-username" name="loginForm_login" type="text" class="form-control" maxlength="32" autocomplete="username" required value="<?php echo isset($_POST['loginForm_login']) ? htmlspecialchars(trim($_POST['loginForm_login']), ENT_QUOTES, 'UTF-8') : ''; ?>" />
                        </div>
                        <div class="form-field">
                            <label for="login-password">Password</label>
                            <input id="login-password" name="loginForm_password" type="password" class="form-control" maxlength="64" autocomplete="current-password" required />
                            <p class="field-feedback" id="login-password-feedback" aria-live="polite"></p>
                        </div>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>" />
                        <button class="button button-primary" type="submit" name="connect" value="1">Sign in</button>
                    </form>
                    <form id="signup" class="auth-card" action="login.php" method="post" novalidate>
                        <h2>Create an account</h2>
                        <p class="form-description">Join Andromeda in a few steps and start exploring the galaxy.</p>
                        <div class="form-field">
                            <label for="signup-login">Login</label>
                            <input id="signup-login" name="signup_login" type="text" class="form-control" maxlength="32" autocomplete="off" required value="<?php echo isset($_POST['signup_login']) ? htmlspecialchars(trim($_POST['signup_login']), ENT_QUOTES, 'UTF-8') : ''; ?>" />
                        </div>
                        <div class="form-field">
                            <label for="signup-password">Password</label>
                            <input id="signup-password" name="signup_password" type="password" class="form-control" minlength="8" maxlength="72" autocomplete="new-password" required />
                            <p class="field-feedback" id="signup-password-feedback" aria-live="polite"></p>
                        </div>
                        <div class="form-field">
                            <label for="signup-password-repeat">Confirm password</label>
                            <input id="signup-password-repeat" name="signup_passwordRepeat" type="password" class="form-control" minlength="8" maxlength="72" autocomplete="new-password" required />
                            <p class="field-feedback" id="signup-password-repeat-feedback" aria-live="polite"></p>
                        </div>
                        <div class="form-field">
                            <label for="signup-email">Email</label>
                            <input id="signup-email" name="signup_email" type="email" class="form-control" maxlength="120" autocomplete="email" required value="<?php echo isset($_POST['signup_email']) ? htmlspecialchars(trim($_POST['signup_email']), ENT_QUOTES, 'UTF-8') : ''; ?>" />
                        </div>
                        <div class="form-field">
                            <label for="signup-pseudo">In-game name</label>
                            <input id="signup-pseudo" name="signup_pseudo" type="text" class="form-control" maxlength="32" autocomplete="nickname" required value="<?php echo isset($_POST['signup_pseudo']) ? htmlspecialchars(trim($_POST['signup_pseudo']), ENT_QUOTES, 'UTF-8') : ''; ?>" />
                        </div>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>" />
                        <button class="button button-secondary" type="submit" name="signup_submit" value="1">Create account</button>
                    </form>
                </div>
                <div class="ad-banner">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <ins class="adsbygoogle"
                        style="display:inline-block;width:728px;height:90px"
                        data-ad-client="ca-pub-5037168492183176"
                        data-ad-slot="3196835078"></ins>
                    <script>
                        (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
            </section>
        </main>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var MIN_LENGTH = 8;
                var loginPassword = document.getElementById('login-password');
                var loginFeedback = document.getElementById('login-password-feedback');
                var signupPassword = document.getElementById('signup-password');
                var signupRepeat = document.getElementById('signup-password-repeat');
                var signupFeedback = document.getElementById('signup-password-feedback');
                var signupRepeatFeedback = document.getElementById('signup-password-repeat-feedback');

                var updateLoginFeedback = function () {
                    if (!loginPassword || !loginFeedback) {
                        return;
                    }
                    var value = loginPassword.value.trim();
                    if (value.length > 0 && value.length < MIN_LENGTH) {
                        loginFeedback.textContent = 'Use at least ' + MIN_LENGTH + ' characters.';
                        loginFeedback.classList.add('field-feedback--error');
                    } else {
                        loginFeedback.textContent = '';
                        loginFeedback.classList.remove('field-feedback--error');
                    }
                };

                var updateSignupFeedback = function () {
                    if (!signupPassword || !signupFeedback) {
                        return;
                    }
                    var value = signupPassword.value.trim();
                    if (value.length === 0) {
                        signupPassword.setCustomValidity('');
                        signupFeedback.textContent = '';
                        signupFeedback.classList.remove('field-feedback--error');
                    } else if (value.length < MIN_LENGTH) {
                        var message = 'Passwords must contain at least ' + MIN_LENGTH + ' characters.';
                        signupPassword.setCustomValidity(message);
                        signupFeedback.textContent = message;
                        signupFeedback.classList.add('field-feedback--error');
                    } else {
                        signupPassword.setCustomValidity('');
                        signupFeedback.textContent = '';
                        signupFeedback.classList.remove('field-feedback--error');
                    }
                };

                var updateRepeatFeedback = function () {
                    if (!signupPassword || !signupRepeat || !signupRepeatFeedback) {
                        return;
                    }
                    if (signupRepeat.value.length === 0) {
                        signupRepeat.setCustomValidity('');
                        signupRepeatFeedback.textContent = '';
                        signupRepeatFeedback.classList.remove('field-feedback--error');
                        return;
                    }
                    if (signupRepeat.value !== signupPassword.value) {
                        var mismatch = 'Passwords do not match yet.';
                        signupRepeat.setCustomValidity(mismatch);
                        signupRepeatFeedback.textContent = mismatch;
                        signupRepeatFeedback.classList.add('field-feedback--error');
                    } else {
                        signupRepeat.setCustomValidity('');
                        signupRepeatFeedback.textContent = '';
                        signupRepeatFeedback.classList.remove('field-feedback--error');
                    }
                };

                if (loginPassword) {
                    loginPassword.addEventListener('input', updateLoginFeedback);
                    loginPassword.addEventListener('blur', updateLoginFeedback);
                }
                if (signupPassword) {
                    signupPassword.addEventListener('input', function () {
                        updateSignupFeedback();
                        updateRepeatFeedback();
                    });
                    signupPassword.addEventListener('blur', updateSignupFeedback);
                }
                if (signupRepeat) {
                    signupRepeat.addEventListener('input', updateRepeatFeedback);
                    signupRepeat.addEventListener('blur', updateRepeatFeedback);
                }
            });
        </script>
    </body>
</html>
<?php
ob_end_flush();

function convertToNumericEntities($string)
{
    $convmap = [0x80, 0x10FFFF, 0, 0xFFFFFF];
    return mb_encode_numericentity($string, $convmap, 'UTF-8');
}

function handleLoginForm($db)
{
    $errors = [];
    if (empty($_POST['connect'])) {
        return $errors;
    }

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Security token mismatch. Please refresh the page and try again.';
        rotateCsrfToken();
        return $errors;
    }

    $loginInput = isset($_POST['loginForm_login']) ? trim($_POST['loginForm_login']) : '';
    $passwordInput = $_POST['loginForm_password'] ?? '';

    if ($loginInput === '' || $passwordInput === '') {
        $errors[] = 'Login and password are required.';
        return $errors;
    }

    $login = convertToNumericEntities(htmlentities($loginInput, ENT_QUOTES, 'UTF-8'));
    $password = $passwordInput;

    $sth = $db->prepare('SELECT id, is_verified, is_admin, password FROM users_infos WHERE login = :login LIMIT 1');
    $sth->execute([
        ':login' => $login,
    ]);
    $user = $sth->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $errors[] = 'Invalid credentials. Please try again.';
        return $errors;
    }

    $storedHash = $user['password'];
    $isValid = false;

    if (!empty($storedHash)) {
        if (password_verify($password, $storedHash)) {
            $isValid = true;
        } elseif (strlen($storedHash) === 32 && ctype_xdigit($storedHash) && hash_equals($storedHash, md5($password))) {
            $isValid = true;
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $update = $db->prepare('UPDATE users_infos SET password = :password WHERE id = :id');
            $update->execute([
                ':password' => $newHash,
                ':id' => $user['id'],
            ]);
        }
    }

    if (!$isValid) {
        $errors[] = 'Invalid credentials. Please try again.';
        return $errors;
    }

    rotateCsrfToken();

    if ($user['is_admin'] > 0) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['is_admin'] = true;
        $_SESSION['player_id'] = $user['id'];
        header('Location: view.php?page=home');
        exit();
    }

    if ($user['is_verified'] > 0) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['player_id'] = $user['id'];
        header('Location: view.php?page=home');
        exit();
    }

    $_SESSION['player_id'] = $user['id'];
    header('Location: verify.php');
    exit();
}

function handleRegisterForm($db)
{
    $errors = [];

    if (empty($_POST['signup_submit'])) {
        return $errors;
    }

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Security token mismatch. Please refresh the page and try again.';
        rotateCsrfToken();
        return $errors;
    }

    $login = isset($_POST['signup_login']) ? trim($_POST['signup_login']) : '';
    $password = $_POST['signup_password'] ?? '';
    $passwordRepeat = $_POST['signup_passwordRepeat'] ?? '';
    $email = isset($_POST['signup_email']) ? trim($_POST['signup_email']) : '';
    $pseudo = isset($_POST['signup_pseudo']) ? trim($_POST['signup_pseudo']) : '';

    if ($login === '') {
        $errors[] = 'Login required.';
    }
    if (!preg_match('/^[A-Za-z][A-Za-z0-9]{5,15}$/', $login)) {
        $errors[] = 'Invalid login (6-16 characters, letters and numbers only, must start with a letter).';
    }

    if ($password === '') {
        $errors[] = 'Password required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Invalid password (8 characters minimum).';
    }

    if ($passwordRepeat === '' || $passwordRepeat !== $password) {
        $errors[] = 'Invalid password confirmation.';
    }

    if ($email === '') {
        $errors[] = 'Email required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email.';
    }

    if ($pseudo === '') {
        $errors[] = 'Pseudo required.';
    } elseif (strlen($pseudo) < 3 || strlen($pseudo) > 16) {
        $errors[] = 'Invalid In-Game Name (3-16 characters).';
    }

    if (!empty($errors)) {
        return $errors;
    }

    $formLogin = htmlentities($login, ENT_QUOTES, 'UTF-8');

    $sth = $db->prepare('SELECT id FROM users_infos WHERE login = :login');
    $sth->execute([
        ':login' => $formLogin,
    ]);

    if ($sth->rowCount() > 0) {
        $errors[] = 'Login already used.';
        return $errors;
    }

    $formPseudo = convertToNumericEntities(htmlentities($pseudo, ENT_QUOTES, 'UTF-8'));

    $sth = $db->prepare('SELECT id FROM users WHERE username = :username');
    $sth->execute([
        ':username' => $formPseudo,
    ]);

    if ($sth->rowCount() > 0) {
        $errors[] = 'In-Game Name already used.';
        return $errors;
    }

    $db->insert('users', [
        'username' => $formPseudo,
    ]);

    $sth = $db->prepare('SELECT id FROM users WHERE username = :username');
    $sth->execute([
        ':username' => $formPseudo,
    ]);
    $result = $sth->fetchAll();

    $formPassword = password_hash($password, PASSWORD_DEFAULT);
    $formEmail = htmlentities(strtolower($email), ENT_QUOTES, 'UTF-8');

    $db->insert('users_infos', [
        'id' => $result[0]['id'],
        'login' => $formLogin,
        'password' => $formPassword,
        'email' => $formEmail,
    ]);

    $db->insert('users_settings', [
        'playerid' => $result[0]['id'],
    ]);

    $db->insert('player_config', [
        'player_id' => $result[0]['id'],
        'damage1' => 5,
        'shield1' => 5,
        'speed1' => 5,
        'damage2' => 5,
        'shield2' => 5,
        'speed2' => 5,
    ]);

    $db->insert('users_npc_counts', [
        'id' => $result[0]['id'],
    ]);

    $db->insert('users_npc_lvl', [
        'id' => $result[0]['id'],
    ]);

    $db->insert('player_reff', [
        'id' => $result[0]['id'],
    ]);

    $db->insert('player_cargo', [
        'id' => $result[0]['id'],
    ]);

    $db->insert('users_log', [
        'playerid' => $result[0]['id'],
        'message' => "<b>Welcome on <font color='#0080FF'>Andromeda</font></b> (beta)<br/>Your firm gave you 10.000 U.<br/>Have fun !<br/>",
    ]);

    rotateCsrfToken();

    $_SESSION['player_id'] = $result[0]['id'];
    header('Location: verify.php?action=send');
    exit();
}

function getCsrfToken()
{
    if (empty($_SESSION['csrf_token'])) {
        rotateCsrfToken();
    }

    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token)
{
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

function rotateCsrfToken()
{
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
