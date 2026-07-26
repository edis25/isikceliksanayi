<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $db->delete('sectors', 'id = :id', ['id' => (int) $_POST['delete_id']]);
    admin_flash('Sektör silindi.');
    header('Location: sectors.php');
    exit;
}

$sectors = $db->all('SELECT * FROM sectors ORDER BY sort_order, id');

admin_header('Sektörler');
?>
<div class="toolbar">
    <span class="muted"><?= count($sectors) ?> sektör</span>
    <a class="btn" href="sector-edit.php">+ Yeni Sektör</a>
</div>
<div class="panel">
    <table>
        <tr><th>Sıra</th><th>Ad (TR)</th><th>Ad (EN)</th><th>İkon</th><th>Durum</th><th></th></tr>
        <?php foreach ($sectors as $s): ?>
        <tr>
            <td><?= (int) $s['sort_order'] ?></td>
            <td><strong><?= e($s['name_tr']) ?></strong></td>
            <td class="muted"><?= e($s['name_en']) ?></td>
            <td class="muted"><?= e($s['icon']) ?></td>
            <td><?= $s['is_published'] ? 'Yayında' : 'Gizli' ?></td>
            <td style="white-space:nowrap">
                <a class="btn btn-small btn-plain" href="sector-edit.php?id=<?= (int) $s['id'] ?>">Düzenle</a>
                <form method="post" style="display:inline" onsubmit="return confirm('Silinsin mi?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="delete_id" value="<?= (int) $s['id'] ?>">
                    <button class="btn btn-small btn-danger" type="submit">Sil</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php admin_footer(); ?>
