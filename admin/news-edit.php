<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();
$id = (int) ($_GET['id'] ?? 0);
$article = $id ? $db->row('SELECT * FROM news WHERE id = :id', ['id' => $id]) : null;
$isNew = !$article;
if ($isNew) {
    $article = [
        'title_tr' => '', 'title_en' => '', 'slug_tr' => '', 'slug_en' => '',
        'summary_tr' => '', 'summary_en' => '', 'body_tr' => '', 'body_en' => '',
        'image' => '', 'meta_title_tr' => '', 'meta_title_en' => '',
        'meta_desc_tr' => '', 'meta_desc_en' => '',
        'published_at' => date('Y-m-d'), 'is_published' => 1,
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title_tr'     => trim($_POST['title_tr'] ?? ''),
        'title_en'     => trim($_POST['title_en'] ?? ''),
        'slug_tr'      => trim($_POST['slug_tr'] ?? '') ?: slugify($_POST['title_tr'] ?? ''),
        'slug_en'      => trim($_POST['slug_en'] ?? '') ?: slugify($_POST['title_en'] ?? ($_POST['title_tr'] ?? '')),
        'summary_tr'   => trim($_POST['summary_tr'] ?? ''),
        'summary_en'   => trim($_POST['summary_en'] ?? ''),
        'body_tr'      => trim($_POST['body_tr'] ?? ''),
        'body_en'      => trim($_POST['body_en'] ?? ''),
        'image'        => trim($_POST['image'] ?? ''),
        'meta_title_tr'=> trim($_POST['meta_title_tr'] ?? ''),
        'meta_title_en'=> trim($_POST['meta_title_en'] ?? ''),
        'meta_desc_tr' => trim($_POST['meta_desc_tr'] ?? ''),
        'meta_desc_en' => trim($_POST['meta_desc_en'] ?? ''),
        'published_at' => trim($_POST['published_at'] ?? date('Y-m-d')),
        'is_published' => isset($_POST['is_published']) ? 1 : 0,
    ];
    if ($isNew) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $id = $db->insert('news', $data);
    } else {
        $db->update('news', $data, 'id = :id', ['id' => $id]);
    }
    admin_flash($isNew ? 'Haber eklendi.' : 'Haber kaydedildi.');
    header('Location: news-edit.php?id=' . $id);
    exit;
}

admin_header($isNew ? 'Yeni Haber' : 'Haber Düzenle');
?>
<form method="post">
    <?= csrf_field() ?>
    <div class="panel">
        <div class="lang-tabs">
            <button type="button" class="active" data-lang="tr">Türkçe</button>
            <button type="button" data-lang="en">English</button>
        </div>
        <div class="lang-pane active" data-lang="tr">
            <div class="field">
                <label>Başlık (TR)</label>
                <input type="text" name="title_tr" value="<?= e($article['title_tr']) ?>" required>
            </div>
            <div class="field">
                <label>URL (TR)</label>
                <input type="text" name="slug_tr" value="<?= e($article['slug_tr']) ?>" placeholder="boş bırakılırsa başlıktan üretilir">
            </div>
            <div class="field">
                <label>Özet (TR)</label>
                <textarea name="summary_tr" style="min-height:70px"><?= e($article['summary_tr']) ?></textarea>
            </div>
            <div class="field">
                <label>İçerik (TR)</label>
                <textarea class="tall" name="body_tr"><?= e($article['body_tr']) ?></textarea>
                <small>Paragraf ayırmak için boş satır bırakın.</small>
            </div>
            <div class="field">
                <label>Meta Başlık (TR)</label>
                <input type="text" name="meta_title_tr" value="<?= e($article['meta_title_tr']) ?>">
            </div>
            <div class="field">
                <label>Meta Açıklama (TR)</label>
                <textarea name="meta_desc_tr" style="min-height:60px"><?= e($article['meta_desc_tr']) ?></textarea>
            </div>
        </div>
        <div class="lang-pane" data-lang="en">
            <div class="field">
                <label>Title (EN)</label>
                <input type="text" name="title_en" value="<?= e($article['title_en']) ?>">
            </div>
            <div class="field">
                <label>URL (EN)</label>
                <input type="text" name="slug_en" value="<?= e($article['slug_en']) ?>">
            </div>
            <div class="field">
                <label>Summary (EN)</label>
                <textarea name="summary_en" style="min-height:70px"><?= e($article['summary_en']) ?></textarea>
            </div>
            <div class="field">
                <label>Body (EN)</label>
                <textarea class="tall" name="body_en"><?= e($article['body_en']) ?></textarea>
            </div>
            <div class="field">
                <label>Meta Title (EN)</label>
                <input type="text" name="meta_title_en" value="<?= e($article['meta_title_en']) ?>">
            </div>
            <div class="field">
                <label>Meta Description (EN)</label>
                <textarea name="meta_desc_en" style="min-height:60px"><?= e($article['meta_desc_en']) ?></textarea>
            </div>
        </div>
        <?php admin_image_field('image', $article['image'], 'Kapak Görseli'); ?>
        <div class="field-row">
            <div class="field">
                <label>Yayın Tarihi</label>
                <input type="date" name="published_at" value="<?= e($article['published_at']) ?>">
            </div>
            <div class="field" style="align-self:end">
                <label><input type="checkbox" name="is_published" <?= $article['is_published'] ? 'checked' : '' ?>> Yayında</label>
            </div>
        </div>
        <button class="btn" type="submit">Kaydet</button>
        <a class="btn btn-plain" href="news.php">Geri</a>
    </div>
</form>
<?php admin_footer(); ?>
