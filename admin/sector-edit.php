<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();
$id = (int) ($_GET['id'] ?? 0);
$sector = $id ? $db->row('SELECT * FROM sectors WHERE id = :id', ['id' => $id]) : null;
$isNew = !$sector;
if ($isNew) {
    $sector = ['name_tr' => '', 'name_en' => '', 'desc_tr' => '', 'desc_en' => '', 'icon' => 'industry', 'image' => '', 'sort_order' => 99, 'is_published' => 1];
}

$iconOptions = ['construction', 'automotive', 'machinery', 'agriculture', 'furniture', 'industry', 'energy', 'globe', 'precision', 'digital', 'automation', 'leaf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name_tr'     => trim($_POST['name_tr'] ?? ''),
        'name_en'     => trim($_POST['name_en'] ?? ''),
        'desc_tr'     => trim($_POST['desc_tr'] ?? ''),
        'desc_en'     => trim($_POST['desc_en'] ?? ''),
        'icon'        => in_array($_POST['icon'] ?? '', $iconOptions, true) ? $_POST['icon'] : 'industry',
        'image'       => trim($_POST['image'] ?? ''),
        'sort_order'  => (int) ($_POST['sort_order'] ?? 0),
        'is_published'=> isset($_POST['is_published']) ? 1 : 0,
    ];
    if ($isNew) {
        $id = $db->insert('sectors', $data);
    } else {
        $db->update('sectors', $data, 'id = :id', ['id' => $id]);
    }
    admin_flash($isNew ? 'Sektör eklendi.' : 'Sektör kaydedildi.');
    header('Location: sector-edit.php?id=' . $id);
    exit;
}

admin_header($isNew ? 'Yeni Sektör' : 'Sektör Düzenle: ' . $sector['name_tr']);
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
                <label>Sektör Adı (TR)</label>
                <input type="text" name="name_tr" value="<?= e($sector['name_tr']) ?>" required>
            </div>
            <div class="field">
                <label>Açıklama (TR)</label>
                <textarea name="desc_tr"><?= e($sector['desc_tr']) ?></textarea>
            </div>
        </div>
        <div class="lang-pane" data-lang="en">
            <div class="field">
                <label>Name (EN)</label>
                <input type="text" name="name_en" value="<?= e($sector['name_en']) ?>">
            </div>
            <div class="field">
                <label>Description (EN)</label>
                <textarea name="desc_en"><?= e($sector['desc_en']) ?></textarea>
            </div>
        </div>
        <div class="field-row">
            <div class="field">
                <label>İkon</label>
                <select name="icon">
                    <?php foreach ($iconOptions as $opt): ?>
                    <option value="<?= e($opt) ?>" <?= $sector['icon'] === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Sıra</label>
                <input type="number" name="sort_order" value="<?= (int) $sector['sort_order'] ?>">
            </div>
        </div>
        <div class="field">
            <label><input type="checkbox" name="is_published" <?= $sector['is_published'] ? 'checked' : '' ?>> Yayında</label>
        </div>
        <button class="btn" type="submit">Kaydet</button>
        <a class="btn btn-plain" href="sectors.php">Geri</a>
    </div>
</form>
<?php admin_footer(); ?>
