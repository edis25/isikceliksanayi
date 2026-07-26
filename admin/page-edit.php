<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();
$id = (int) ($_GET['id'] ?? 0);
$pageRow = $db->row('SELECT * FROM pages WHERE id = :id', ['id' => $id]);
if (!$pageRow) {
    header('Location: pages.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->update('pages', [
        'title_tr'      => trim($_POST['title_tr'] ?? ''),
        'title_en'      => trim($_POST['title_en'] ?? ''),
        'slug_tr'       => trim($_POST['slug_tr'] ?? $pageRow['slug_tr']),
        'slug_en'       => trim($_POST['slug_en'] ?? $pageRow['slug_en']),
        'meta_title_tr' => trim($_POST['meta_title_tr'] ?? ''),
        'meta_title_en' => trim($_POST['meta_title_en'] ?? ''),
        'meta_desc_tr'  => trim($_POST['meta_desc_tr'] ?? ''),
        'meta_desc_en'  => trim($_POST['meta_desc_en'] ?? ''),
        'image'         => trim($_POST['image'] ?? ''),
        'is_published'  => isset($_POST['is_published']) ? 1 : 0,
    ], 'id = :id', ['id' => $id]);
    admin_flash('Sayfa kaydedildi.');
    header('Location: page-edit.php?id=' . $id);
    exit;
}

$sectionsList = $db->all('SELECT * FROM sections WHERE page_id = :p ORDER BY sort_order, id', ['p' => $id]);

admin_header('Sayfa Düzenle: ' . $pageRow['title_tr']);
?>
<form method="post">
    <?= csrf_field() ?>
    <div class="panel">
        <h2>Sayfa Bilgileri &amp; SEO</h2>
        <div class="lang-tabs">
            <button type="button" class="active" data-lang="tr">Türkçe</button>
            <button type="button" data-lang="en">English</button>
        </div>
        <div class="lang-pane active" data-lang="tr">
            <div class="field-row">
                <div class="field">
                    <label>Sayfa Başlığı (TR)</label>
                    <input type="text" name="title_tr" value="<?= e($pageRow['title_tr']) ?>">
                </div>
                <div class="field">
                    <label>URL (TR)</label>
                    <input type="text" name="slug_tr" value="<?= e($pageRow['slug_tr']) ?>" <?= $pageRow['pkey'] === 'home' ? 'readonly' : '' ?>>
                    <small>Değiştirirseniz eski adres çalışmaz; arama motoru sıralamasını etkileyebilir.</small>
                </div>
            </div>
            <div class="field">
                <label>Meta Başlık (TR)</label>
                <input type="text" name="meta_title_tr" value="<?= e($pageRow['meta_title_tr']) ?>" maxlength="190">
            </div>
            <div class="field">
                <label>Meta Açıklama (TR)</label>
                <textarea name="meta_desc_tr" style="min-height:70px" maxlength="300"><?= e($pageRow['meta_desc_tr']) ?></textarea>
                <small>Arama sonuçlarında görünen açıklama. İdeal uzunluk 150–160 karakter.</small>
            </div>
        </div>
        <div class="lang-pane" data-lang="en">
            <div class="field-row">
                <div class="field">
                    <label>Page Title (EN)</label>
                    <input type="text" name="title_en" value="<?= e($pageRow['title_en']) ?>">
                </div>
                <div class="field">
                    <label>URL (EN)</label>
                    <input type="text" name="slug_en" value="<?= e($pageRow['slug_en']) ?>" <?= $pageRow['pkey'] === 'home' ? 'readonly' : '' ?>>
                </div>
            </div>
            <div class="field">
                <label>Meta Title (EN)</label>
                <input type="text" name="meta_title_en" value="<?= e($pageRow['meta_title_en']) ?>" maxlength="190">
            </div>
            <div class="field">
                <label>Meta Description (EN)</label>
                <textarea name="meta_desc_en" style="min-height:70px" maxlength="300"><?= e($pageRow['meta_desc_en']) ?></textarea>
            </div>
        </div>
        <?php admin_image_field('image', $pageRow['image'], 'Sayfa Görseli (başlık bandı + paylaşım görseli)'); ?>
        <div class="field">
            <label><input type="checkbox" name="is_published" <?= $pageRow['is_published'] ? 'checked' : '' ?>> Yayında</label>
        </div>
        <button class="btn" type="submit">Kaydet</button>
        <a class="btn btn-plain" href="pages.php">Geri</a>
    </div>
</form>

<div class="panel">
    <h2>Sayfa Bölümleri</h2>
    <?php if (!$sectionsList): ?>
    <p class="muted">Bu sayfa bölüm içermiyor (içeriği otomatik listelenir: ürünler, haberler vb.).</p>
    <?php else: ?>
    <table>
        <tr><th>Sıra</th><th>Bölüm</th><th>Tip</th><th>Başlık (TR)</th><th>Durum</th><th></th></tr>
        <?php foreach ($sectionsList as $s): ?>
        <tr>
            <td><?= (int) $s['sort_order'] ?></td>
            <td class="muted"><?= e($s['skey']) ?></td>
            <td class="muted"><?= e($s['type']) ?></td>
            <td><?= e(excerpt($s['title_tr'], 45)) ?></td>
            <td><?= $s['is_published'] ? 'Yayında' : 'Gizli' ?></td>
            <td><a class="btn btn-small btn-plain" href="section-edit.php?id=<?= (int) $s['id'] ?>">Düzenle</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
<?php admin_footer(); ?>
