<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $db->delete('news', 'id = :id', ['id' => (int) $_POST['delete_id']]);
    admin_flash('Haber silindi.');
    header('Location: news.php');
    exit;
}

$newsList = $db->all('SELECT * FROM news ORDER BY published_at DESC, id DESC');

admin_header('Haberler');
?>
<div class="toolbar">
    <span class="muted"><?= count($newsList) ?> haber</span>
    <a class="btn" href="news-edit.php">+ Yeni Haber</a>
</div>
<div class="panel">
    <table>
        <tr><th>Görsel</th><th>Tarih</th><th>Başlık (TR)</th><th>Durum</th><th></th></tr>
        <?php foreach ($newsList as $n): ?>
        <tr>
            <td><?php if ($n['image']): ?><img class="thumb" src="../<?= e($n['image']) ?>" alt=""><?php endif; ?></td>
            <td class="muted"><?= e($n['published_at']) ?></td>
            <td><strong><?= e(excerpt($n['title_tr'], 60)) ?></strong></td>
            <td><?= $n['is_published'] ? 'Yayında' : 'Taslak' ?></td>
            <td style="white-space:nowrap">
                <a class="btn btn-small btn-plain" href="news-edit.php?id=<?= (int) $n['id'] ?>">Düzenle</a>
                <form method="post" style="display:inline" onsubmit="return confirm('Bu haberi silmek istediğinize emin misiniz?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="delete_id" value="<?= (int) $n['id'] ?>">
                    <button class="btn btn-small btn-danger" type="submit">Sil</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php admin_footer(); ?>
