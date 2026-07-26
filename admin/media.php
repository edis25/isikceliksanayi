<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();
$uploadError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $m = $db->row('SELECT * FROM media WHERE id = :id', ['id' => (int) $_POST['delete_id']]);
        if ($m) {
            $abs = __DIR__ . '/../' . $m['path'];
            if (is_file($abs)) {
                unlink($abs);
            }
            $db->delete('media', 'id = :id', ['id' => $m['id']]);
        }
        admin_flash('Dosya silindi.');
        header('Location: media.php');
        exit;
    }
    if (!empty($_FILES['file']['name'])) {
        $path = admin_handle_upload($_FILES['file'], $uploadError);
        if ($path) {
            admin_flash('Dosya yüklendi: ' . $path);
            header('Location: media.php');
            exit;
        }
    }
}

$items = $db->all('SELECT * FROM media ORDER BY id DESC');

admin_header('Medya Kütüphanesi');
?>
<?php if ($uploadError): ?>
<div class="error-box"><?= e($uploadError) ?></div>
<?php endif; ?>
<div class="panel">
    <h2>Yeni Dosya Yükle</h2>
    <form method="post" enctype="multipart/form-data" style="display:flex;gap:14px;align-items:center;flex-wrap:wrap">
        <?= csrf_field() ?>
        <input type="file" name="file" accept="image/jpeg,image/png,image/webp,image/svg+xml" required>
        <button class="btn" type="submit">Yükle</button>
        <span class="muted">JPG, PNG, WebP, SVG — en fazla 15MB. Büyük görseller otomatik 1920px'e küçültülür.</span>
    </form>
</div>

<div class="media-grid">
    <?php foreach ($items as $m): ?>
    <div class="media-item">
        <img src="../<?= e($m['path']) ?>" alt="<?= e($m['filename']) ?>" loading="lazy">
        <div class="meta">
            <div class="muted"><?= e($m['filename']) ?> · <?= round($m['size'] / 1024) ?> KB</div>
            <input type="text" value="<?= e($m['path']) ?>" readonly onclick="this.select();document.execCommand('copy')" title="Kopyalamak için tıklayın">
            <form method="post" onsubmit="return confirm('Bu dosyayı silmek istediğinize emin misiniz?')" style="margin-top:8px">
                <?= csrf_field() ?>
                <input type="hidden" name="delete_id" value="<?= (int) $m['id'] ?>">
                <button class="btn btn-small btn-danger" type="submit">Sil</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php if (!$items): ?>
<p class="muted">Henüz yüklenmiş dosya yok. Site kurulumuyla gelen görseller <code>assets/img/</code> klasöründedir ve doğrudan kullanılabilir.</p>
<?php endif; ?>
<?php admin_footer(); ?>
