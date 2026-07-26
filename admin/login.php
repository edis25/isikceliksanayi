<?php
require __DIR__ . '/_bootstrap.php';

if (admin_user()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $error = 'Oturum doğrulaması başarısız, lütfen tekrar deneyin.';
    } else {
        // Basit brute-force önlemi: art arda hatalı denemelerde bekletme
        $attempts = $_SESSION['login_attempts'] ?? 0;
        if ($attempts >= 3) {
            sleep(min($attempts, 8));
        }
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $user = Database::get()->row('SELECT * FROM users WHERE username = :u', ['u' => $username]);
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_user'] = ['id' => $user['id'], 'username' => $user['username'], 'name' => $user['name']];
            $_SESSION['login_attempts'] = 0;
            header('Location: index.php');
            exit;
        }
        $_SESSION['login_attempts'] = $attempts + 1;
        $error = 'Kullanıcı adı veya parola hatalı.';
    }
}

admin_header('Giriş');
?>
<div class="login-wrap">
    <div class="login-card">
        <div class="login-brand">
            <strong>IŞIK ÇELİK</strong>
            <span>YÖNETİM PANELİ</span>
        </div>
        <?php if ($error): ?>
        <div class="error-box"><?= e($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <?= csrf_field() ?>
            <div class="field">
                <label>Kullanıcı Adı</label>
                <input type="text" name="username" required autofocus autocomplete="username">
            </div>
            <div class="field">
                <label>Parola</label>
                <input type="password" name="password" required autocomplete="current-password">
            </div>
            <button class="btn" type="submit">Giriş Yap</button>
        </form>
    </div>
</div>
<?php admin_footer(); ?>
