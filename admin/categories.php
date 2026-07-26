<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $delId = (int) $_POST['delete_id'];
        $inUse = (int) $db->value('SELECT COUNT(*) FROM products WHERE category_id = :id', ['id' => $delId]);
        if ($inUse > 0) {
            $error = "Bu kategoride $inUse ürün var. Önce ürünleri başka kategoriye taşıyın.";
        } else {
            $db->delete('categories', 'id = :id', ['id' => $delId]);
            admin_flash('Kategori silindi.');
            header('Location: categories.php');
            exit;
        }
    } elseif (isset($_POST['save_id'])) {
        $sid = (int) $_POST['save_id'];
        $nameTr = trim($_POST['name_tr'] ?? '');
        $nameEn = trim($_POST['name_en'] ?? '');
        if ($nameTr === '') {
            $error = 'Kategori adı boş olamaz.';
        } else {
            $data = [
                'name_tr'      => $nameTr,
                'name_en'      => $nameEn,
                'slug_tr'      => trim($_POST['slug_tr'] ?? '') ?: slugify($nameTr),
                'slug_en'      => trim($_POST['slug_en'] ?? '') ?: slugify($nameEn ?: $nameTr),
                'sort_order'   => (int) ($_POST['sort_order'] ?? 0),
                'is_published' => isset($_POST['is_published']) ? 1 : 0,
            ];
            if ($sid === 0) {
                $db->insert('categories', $data);
                admin_flash('Kategori eklendi.');
            } else {
                $db->update('categories', $data, 'id = :id', ['id' => $sid]);
                admin_flash('Kategori kaydedildi.');
            }
            header('Location: categories.php');
            exit;
        }
    }
}

$cats = $db->all('SELECT * FROM categories ORDER BY sort_order, id');
$counts = [];
foreach ($db->all('SELECT category_id, COUNT(*) AS c FROM products GROUP BY category_id') as $r) {
    $counts[(int) $r['category_id']] = (int) $r['c'];
}

admin_header('Ürün Kategorileri');
?>
<?php if ($error): ?>
<div class="error-box"><?= e($error) ?></div>
<?php endif; ?>

<div class="panel">
    <h2>Kategoriler</h2>
    <table>
        <tr><th>Sıra</th><th>Ad (TR)</th><th>Ad (EN)</th><th>TR URL</th><th>EN URL</th><th>Ürün</th><th>Yayın</th><th></th></tr>
        <?php $inputStyle = 'width:100%;padding:6px 8px;border:1px solid var(--line);border-radius:5px'; ?>
        <?php foreach ($cats as $c): $fid = 'catform-' . (int) $c['id']; ?>
        <tr>
            <td style="width:70px"><input form="<?= $fid ?>" type="number" name="sort_order" value="<?= (int) $c['sort_order'] ?>" style="width:60px;padding:6px;border:1px solid var(--line);border-radius:5px"></td>
            <td><input form="<?= $fid ?>" type="text" name="name_tr" value="<?= e($c['name_tr']) ?>" style="<?= $inputStyle ?>"></td>
            <td><input form="<?= $fid ?>" type="text" name="name_en" value="<?= e($c['name_en']) ?>" style="<?= $inputStyle ?>"></td>
            <td><input form="<?= $fid ?>" type="text" name="slug_tr" value="<?= e($c['slug_tr']) ?>" style="<?= $inputStyle ?>"></td>
            <td><input form="<?= $fid ?>" type="text" name="slug_en" value="<?= e($c['slug_en']) ?>" style="<?= $inputStyle ?>"></td>
            <td class="muted"><?= $counts[(int) $c['id']] ?? 0 ?></td>
            <td><input form="<?= $fid ?>" type="checkbox" name="is_published" <?= $c['is_published'] ? 'checked' : '' ?>></td>
            <td style="white-space:nowrap">
                <button form="<?= $fid ?>" class="btn btn-small btn-secondary" type="submit">Kaydet</button>
                <form method="post" style="display:inline" onsubmit="return confirm('Kategori silinsin mi?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="delete_id" value="<?= (int) $c['id'] ?>">
                    <button class="btn btn-small btn-danger" type="submit">Sil</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php foreach ($cats as $c): ?>
    <form id="catform-<?= (int) $c['id'] ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="save_id" value="<?= (int) $c['id'] ?>">
    </form>
    <?php endforeach; ?>
</div>

<div class="panel">
    <h2>Yeni Kategori Ekle</h2>
    <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="save_id" value="0">
        <div class="field-row">
            <div class="field">
                <label>Ad (TR)</label>
                <input type="text" name="name_tr" required>
            </div>
            <div class="field">
                <label>Ad (EN)</label>
                <input type="text" name="name_en">
            </div>
        </div>
        <div class="field-row">
            <div class="field">
                <label>TR URL (boşsa addan üretilir)</label>
                <input type="text" name="slug_tr">
            </div>
            <div class="field">
                <label>EN URL</label>
                <input type="text" name="slug_en">
            </div>
        </div>
        <div class="field">
            <label>Sıra</label>
            <input type="number" name="sort_order" value="99">
        </div>
        <label style="display:block;margin-bottom:14px"><input type="checkbox" name="is_published" checked> Yayında</label>
        <button class="btn" type="submit">Ekle</button>
    </form>
</div>
<?php admin_footer(); ?>
