<?php
require __DIR__ . '/_bootstrap.php';
admin_require();

$db = Database::get();
$pages = $db->all('SELECT * FROM pages ORDER BY sort_order, id');

admin_header('Sayfalar');
?>
<div class="panel">
    <table>
        <tr><th>Sayfa</th><th>TR URL</th><th>EN URL</th><th>Durum</th><th></th></tr>
        <?php foreach ($pages as $p): ?>
        <tr>
            <td><strong><?= e($p['title_tr']) ?></strong></td>
            <td class="muted">/<?= e($p['slug_tr']) ?></td>
            <td class="muted">/en/<?= e($p['slug_en']) ?></td>
            <td><?= $p['is_published'] ? 'Yayında' : 'Gizli' ?></td>
            <td><a class="btn btn-small btn-plain" href="page-edit.php?id=<?= (int) $p['id'] ?>">Düzenle</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<p class="muted">Sayfa içerikleri bölümlerden oluşur; düzenleme ekranından her bölümün TR/EN metnini ve görselini değiştirebilirsiniz.</p>
<?php admin_footer(); ?>
