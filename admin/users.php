<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $delId = (int) $_POST['delete_id'];
        if ($delId === (int) admin_user()['id']) {
            $error = 'Kendi hesabınızı silemezsiniz.';
        } elseif ((int) $db->value('SELECT COUNT(*) FROM users') <= 1) {
            $error = 'Son kullanıcı silinemez.';
        } else {
            $db->delete('users', 'id = :id', ['id' => $delId]);
            admin_flash('Kullanıcı silindi.');
            header('Location: users.php');
            exit;
        }
    } elseif (isset($_POST['new_username'])) {
        $u = trim($_POST['new_username']);
        $p = $_POST['new_password'] ?? '';
        $n = trim($_POST['new_name'] ?? '');
        if ($u === '' || strlen($p) < 8) {
            $error = 'Kullanıcı adı boş olamaz; parola en az 8 karakter olmalı.';
        } elseif ($db->row('SELECT id FROM users WHERE username = :u', ['u' => $u])) {
            $error = 'Bu kullanıcı adı zaten mevcut.';
        } else {
            $db->insert('users', [
                'username'      => $u,
                'password_hash' => password_hash($p, PASSWORD_BCRYPT),
                'name'          => $n,
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
            admin_flash('Kullanıcı eklendi.');
            header('Location: users.php');
            exit;
        }
    } elseif (isset($_POST['pw_user_id'])) {
        $p = $_POST['pw_new'] ?? '';
        if (strlen($p) < 8) {
            $error = 'Yeni parola en az 8 karakter olmalı.';
        } else {
            $db->update('users', ['password_hash' => password_hash($p, PASSWORD_BCRYPT)], 'id = :id', ['id' => (int) $_POST['pw_user_id']]);
            admin_flash('Parola güncellendi.');
            header('Location: users.php');
            exit;
        }
    }
}

$users = $db->all('SELECT * FROM users ORDER BY id');

admin_header('Kullanıcılar');
?>
<?php if ($error): ?>
<div class="error-box"><?= e($error) ?></div>
<?php endif; ?>

<div class="panel">
    <h2>Mevcut Kullanıcılar</h2>
    <table>
        <tr><th>Kullanıcı Adı</th><th>Ad</th><th>Oluşturma</th><th>Parola</th><th></th></tr>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><strong><?= e($u['username']) ?></strong></td>
            <td><?= e($u['name']) ?></td>
            <td class="muted"><?= e($u['created_at']) ?></td>
            <td>
                <form method="post" style="display:flex;gap:8px">
                    <?= csrf_field() ?>
                    <input type="hidden" name="pw_user_id" value="<?= (int) $u['id'] ?>">
                    <input type="password" name="pw_new" placeholder="Yeni parola (min 8)" style="padding:6px 10px;border:1px solid var(--line);border-radius:5px">
                    <button class="btn btn-small btn-secondary" type="submit">Değiştir</button>
                </form>
            </td>
            <td>
                <?php if ((int) $u['id'] !== (int) admin_user()['id']): ?>
                <form method="post" style="display:inline" onsubmit="return confirm('Kullanıcı silinsin mi?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="delete_id" value="<?= (int) $u['id'] ?>">
                    <button class="btn btn-small btn-danger" type="submit">Sil</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="panel">
    <h2>Yeni Kullanıcı Ekle</h2>
    <form method="post">
        <?= csrf_field() ?>
        <div class="field-row">
            <div class="field">
                <label>Kullanıcı Adı</label>
                <input type="text" name="new_username" required>
            </div>
            <div class="field">
                <label>Ad Soyad</label>
                <input type="text" name="new_name">
            </div>
        </div>
        <div class="field">
            <label>Parola (en az 8 karakter)</label>
            <input type="password" name="new_password" required minlength="8">
        </div>
        <button class="btn" type="submit">Ekle</button>
    </form>
</div>
<?php admin_footer(); ?>
