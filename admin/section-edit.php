<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();
$id = (int) ($_GET['id'] ?? 0);
$section = $db->row('SELECT * FROM sections WHERE id = :id', ['id' => $id]);
if (!$section) {
    header('Location: pages.php');
    exit;
}
$parentPage = $db->row('SELECT * FROM pages WHERE id = :id', ['id' => $section['page_id']]);

$jsonError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dataJson = trim($_POST['data_json'] ?? '');
    if ($dataJson !== '') {
        $decoded = json_decode($dataJson, true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            $jsonError = 'Liste verisi (JSON) hatalı: ' . json_last_error_msg();
        } else {
            $dataJson = json_encode($decoded, JSON_UNESCAPED_UNICODE);
        }
    }
    if ($jsonError === '') {
        $db->update('sections', [
            'title_tr'    => trim($_POST['title_tr'] ?? ''),
            'title_en'    => trim($_POST['title_en'] ?? ''),
            'subtitle_tr' => trim($_POST['subtitle_tr'] ?? ''),
            'subtitle_en' => trim($_POST['subtitle_en'] ?? ''),
            'body_tr'     => trim($_POST['body_tr'] ?? ''),
            'body_en'     => trim($_POST['body_en'] ?? ''),
            'data_json'   => $dataJson,
            'image'       => trim($_POST['image'] ?? ''),
            'sort_order'  => (int) ($_POST['sort_order'] ?? 0),
            'is_published'=> isset($_POST['is_published']) ? 1 : 0,
        ], 'id = :id', ['id' => $id]);
        admin_flash('Bölüm kaydedildi.');
        header('Location: section-edit.php?id=' . $id);
        exit;
    }
    // Hata varsa formu gönderilen değerlerle tekrar göster
    $section = array_merge($section, [
        'title_tr' => $_POST['title_tr'] ?? '', 'title_en' => $_POST['title_en'] ?? '',
        'subtitle_tr' => $_POST['subtitle_tr'] ?? '', 'subtitle_en' => $_POST['subtitle_en'] ?? '',
        'body_tr' => $_POST['body_tr'] ?? '', 'body_en' => $_POST['body_en'] ?? '',
        'data_json' => $_POST['data_json'] ?? '', 'image' => $_POST['image'] ?? '',
    ]);
}

$prettyJson = '';
if ($section['data_json']) {
    $tmp = json_decode($section['data_json'], true);
    $prettyJson = $tmp !== null
        ? json_encode($tmp, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        : $section['data_json'];
}

admin_header('Bölüm Düzenle: ' . $section['skey'] . ' (' . ($parentPage['title_tr'] ?? '') . ')');
?>
<?php if ($jsonError): ?>
<div class="error-box"><?= e($jsonError) ?></div>
<?php endif; ?>
<form method="post">
    <?= csrf_field() ?>
    <div class="panel">
        <h2>İçerik</h2>
        <div class="lang-tabs">
            <button type="button" class="active" data-lang="tr">Türkçe</button>
            <button type="button" data-lang="en">English</button>
        </div>
        <div class="lang-pane active" data-lang="tr">
            <div class="field">
                <label>Başlık (TR)</label>
                <input type="text" name="title_tr" value="<?= e($section['title_tr']) ?>">
            </div>
            <div class="field">
                <label>Alt Başlık (TR)</label>
                <input type="text" name="subtitle_tr" value="<?= e($section['subtitle_tr']) ?>">
            </div>
            <div class="field">
                <label>Metin (TR)</label>
                <textarea class="tall" name="body_tr"><?= e($section['body_tr']) ?></textarea>
                <small>Paragraf ayırmak için boş satır bırakın.</small>
            </div>
        </div>
        <div class="lang-pane" data-lang="en">
            <div class="field">
                <label>Title (EN)</label>
                <input type="text" name="title_en" value="<?= e($section['title_en']) ?>">
            </div>
            <div class="field">
                <label>Subtitle (EN)</label>
                <input type="text" name="subtitle_en" value="<?= e($section['subtitle_en']) ?>">
            </div>
            <div class="field">
                <label>Body (EN)</label>
                <textarea class="tall" name="body_en"><?= e($section['body_en']) ?></textarea>
            </div>
        </div>
        <?php admin_image_field('image', $section['image']); ?>
        <?php if ($prettyJson !== '' || in_array($section['type'], ['stats', 'icon-cards', 'feature-list', 'gallery', 'region', 'hero', 'split', 'split-reverse', 'dark-feature', 'cta', 'global'], true)): ?>
        <div class="field">
            <label>Liste Verisi — Gelişmiş (JSON)</label>
            <textarea class="tall" name="data_json" spellcheck="false" style="font-family:monospace;font-size:13px"><?= e($prettyJson) ?></textarea>
            <small>İstatistik, kart ve madde listeleri burada tutulur (items_tr / items_en). Yapıyı bozmadan yalnızca metinleri değiştirmeniz önerilir.</small>
        </div>
        <?php endif; ?>
        <div class="field-row">
            <div class="field">
                <label>Sıra</label>
                <input type="number" name="sort_order" value="<?= (int) $section['sort_order'] ?>">
            </div>
            <div class="field" style="align-self:end">
                <label><input type="checkbox" name="is_published" <?= $section['is_published'] ? 'checked' : '' ?>> Yayında</label>
            </div>
        </div>
        <button class="btn" type="submit">Kaydet</button>
        <a class="btn btn-plain" href="page-edit.php?id=<?= (int) $section['page_id'] ?>">Geri</a>
    </div>
</form>
<?php admin_footer(); ?>
