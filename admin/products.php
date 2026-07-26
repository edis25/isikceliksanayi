<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $db->delete('products', 'id = :id', ['id' => (int) $_POST['delete_id']]);
    admin_flash('Ürün silindi.');
    header('Location: products.php');
    exit;
}

$products = $db->all('SELECT * FROM products ORDER BY sort_order, id');

admin_header('Ürünler');
?>
<div class="toolbar">
    <span class="muted"><?= count($products) ?> ürün</span>
    <a class="btn" href="product-edit.php">+ Yeni Ürün</a>
</div>
<div class="panel">
    <table>
        <tr><th>Görsel</th><th>Sıra</th><th>Ürün Adı</th><th>TR URL</th><th>Durum</th><th></th></tr>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?php if ($p['image']): ?><img class="thumb" src="../<?= e($p['image']) ?>" alt=""><?php endif; ?></td>
            <td><?= (int) $p['sort_order'] ?></td>
            <td><strong><?= e($p['name_tr']) ?></strong></td>
            <td class="muted"><?= e($p['slug_tr']) ?></td>
            <td><?= $p['is_published'] ? 'Yayında' : 'Gizli' ?></td>
            <td style="white-space:nowrap">
                <a class="btn btn-small btn-plain" href="product-edit.php?id=<?= (int) $p['id'] ?>">Düzenle</a>
                <form method="post" style="display:inline" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="delete_id" value="<?= (int) $p['id'] ?>">
                    <button class="btn btn-small btn-danger" type="submit">Sil</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php admin_footer(); ?>
