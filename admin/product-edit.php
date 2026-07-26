<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();
$categories = $db->all('SELECT * FROM categories ORDER BY sort_order, id');
$id = (int) ($_GET['id'] ?? 0);
$product = $id ? $db->row('SELECT * FROM products WHERE id = :id', ['id' => $id]) : null;
$isNew = !$product;
if ($isNew) {
    $product = [
        'category_id' => 0,
        'name_tr' => '', 'name_en' => '', 'slug_tr' => '', 'slug_en' => '',
        'summary_tr' => '', 'summary_en' => '', 'body_tr' => '', 'body_en' => '',
        'spec_table' => '', 'image' => '', 'gallery' => '', 'meta_title_tr' => '', 'meta_title_en' => '',
        'meta_desc_tr' => '', 'meta_desc_en' => '', 'sort_order' => 99, 'is_published' => 1,
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'category_id'  => (int) ($_POST['category_id'] ?? 0),
        'name_tr'      => trim($_POST['name_tr'] ?? ''),
        'name_en'      => trim($_POST['name_en'] ?? ''),
        'slug_tr'      => trim($_POST['slug_tr'] ?? '') ?: slugify($_POST['name_tr'] ?? ''),
        'slug_en'      => trim($_POST['slug_en'] ?? '') ?: slugify($_POST['name_en'] ?? ($_POST['name_tr'] ?? '')),
        'summary_tr'   => trim($_POST['summary_tr'] ?? ''),
        'summary_en'   => trim($_POST['summary_en'] ?? ''),
        'body_tr'      => trim($_POST['body_tr'] ?? ''),
        'body_en'      => trim($_POST['body_en'] ?? ''),
        'spec_table'   => trim($_POST['spec_table'] ?? ''),
        'image'        => trim($_POST['image'] ?? ''),
        // Galeri: her satırda bir görsel yolu → JSON dizi
        'gallery'      => json_encode(array_values(array_filter(array_map('trim', explode("\n", $_POST['gallery'] ?? ''))))),
        'meta_title_tr'=> trim($_POST['meta_title_tr'] ?? ''),
        'meta_title_en'=> trim($_POST['meta_title_en'] ?? ''),
        'meta_desc_tr' => trim($_POST['meta_desc_tr'] ?? ''),
        'meta_desc_en' => trim($_POST['meta_desc_en'] ?? ''),
        'sort_order'   => (int) ($_POST['sort_order'] ?? 0),
        'is_published' => isset($_POST['is_published']) ? 1 : 0,
    ];
    if ($isNew) {
        $id = $db->insert('products', $data);
    } else {
        $db->update('products', $data, 'id = :id', ['id' => $id]);
    }
    admin_flash($isNew ? 'Ürün eklendi.' : 'Ürün kaydedildi.');
    header('Location: product-edit.php?id=' . $id);
    exit;
}

admin_header($isNew ? 'Yeni Ürün' : 'Ürün Düzenle: ' . $product['name_tr']);
?>
<form method="post">
    <?= csrf_field() ?>
    <div class="panel">
        <h2>Ürün Bilgileri</h2>
        <div class="lang-tabs">
            <button type="button" class="active" data-lang="tr">Türkçe</button>
            <button type="button" data-lang="en">English</button>
        </div>
        <div class="lang-pane active" data-lang="tr">
            <div class="field-row">
                <div class="field">
                    <label>Ürün Adı (TR)</label>
                    <input type="text" name="name_tr" value="<?= e($product['name_tr']) ?>" required>
                </div>
                <div class="field">
                    <label>URL (TR)</label>
                    <input type="text" name="slug_tr" value="<?= e($product['slug_tr']) ?>" placeholder="boş bırakılırsa addan üretilir">
                </div>
            </div>
            <div class="field">
                <label>Kısa Özet (TR)</label>
                <textarea name="summary_tr" style="min-height:70px"><?= e($product['summary_tr']) ?></textarea>
            </div>
            <div class="field">
                <label>Detay Metni (TR)</label>
                <textarea class="tall" name="body_tr"><?= e($product['body_tr']) ?></textarea>
            </div>
            <div class="field">
                <label>Meta Başlık (TR)</label>
                <input type="text" name="meta_title_tr" value="<?= e($product['meta_title_tr']) ?>">
            </div>
            <div class="field">
                <label>Meta Açıklama (TR)</label>
                <textarea name="meta_desc_tr" style="min-height:60px"><?= e($product['meta_desc_tr']) ?></textarea>
            </div>
        </div>
        <div class="lang-pane" data-lang="en">
            <div class="field-row">
                <div class="field">
                    <label>Product Name (EN)</label>
                    <input type="text" name="name_en" value="<?= e($product['name_en']) ?>">
                </div>
                <div class="field">
                    <label>URL (EN)</label>
                    <input type="text" name="slug_en" value="<?= e($product['slug_en']) ?>">
                </div>
            </div>
            <div class="field">
                <label>Summary (EN)</label>
                <textarea name="summary_en" style="min-height:70px"><?= e($product['summary_en']) ?></textarea>
            </div>
            <div class="field">
                <label>Body (EN)</label>
                <textarea class="tall" name="body_en"><?= e($product['body_en']) ?></textarea>
            </div>
            <div class="field">
                <label>Meta Title (EN)</label>
                <input type="text" name="meta_title_en" value="<?= e($product['meta_title_en']) ?>">
            </div>
            <div class="field">
                <label>Meta Description (EN)</label>
                <textarea name="meta_desc_en" style="min-height:60px"><?= e($product['meta_desc_en']) ?></textarea>
            </div>
        </div>
        <div class="field">
            <label>Kategori</label>
            <select name="category_id">
                <option value="0">— Kategorisiz —</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= (int) $c['id'] ?>" <?= (int) $product['category_id'] === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['name_tr']) ?></option>
                <?php endforeach; ?>
            </select>
            <small>Kategorileri <a href="categories.php">Kategoriler</a> sayfasından yönetebilirsiniz.</small>
        </div>
        <?php admin_image_field('image', $product['image'], 'Ana Görsel (illüstrasyon)'); ?>
        <div class="field">
            <label>Galeri Görselleri (her satıra bir yol)</label>
            <textarea name="gallery" style="min-height:70px" placeholder="assets/img/products/... veya uploads/..."><?= e(implode("\n", json_decode($product['gallery'] ?: '[]', true) ?: [])) ?></textarea>
            <small>Ürün fotoğrafları. İlki, listede kartın üzerine gelince görünür; detay sayfasında küçük görseller olarak listelenir.</small>
        </div>
        <div class="field">
            <label>Üretim Ölçüleri Tablosu (HTML)</label>
            <textarea class="tall" name="spec_table" spellcheck="false" style="font-family:monospace;font-size:12.5px"><?= e($product['spec_table']) ?></textarea>
            <small>&lt;table&gt;&lt;tr&gt;&lt;td&gt; yapısında ölçü tablosu. İlk satır başlık, ilk sütun ölçü olarak vurgulanır; boş bırakılırsa ürün sayfasında tablo gösterilmez.</small>
        </div>
        <div class="field-row">
            <div class="field">
                <label>Sıra</label>
                <input type="number" name="sort_order" value="<?= (int) $product['sort_order'] ?>">
            </div>
            <div class="field" style="align-self:end">
                <label><input type="checkbox" name="is_published" <?= $product['is_published'] ? 'checked' : '' ?>> Yayında</label>
            </div>
        </div>
        <button class="btn" type="submit">Kaydet</button>
        <a class="btn btn-plain" href="products.php">Geri</a>
    </div>
</form>
<?php admin_footer(); ?>
