<?php
require __DIR__ . '/_bootstrap.php';
admin_require();

$db = Database::get();
$counts = [
    'Sayfa'          => $db->value('SELECT COUNT(*) FROM pages'),
    'Ürün'           => $db->value('SELECT COUNT(*) FROM products WHERE is_published = 1'),
    'Haber'          => $db->value('SELECT COUNT(*) FROM news WHERE is_published = 1'),
    'Okunmamış Mesaj'=> $db->value('SELECT COUNT(*) FROM messages WHERE is_read = 0'),
];
$latestMessages = $db->all('SELECT * FROM messages ORDER BY id DESC LIMIT 5');
$latestNews = $db->all('SELECT * FROM news ORDER BY published_at DESC LIMIT 5');

admin_header('Gösterge Paneli');
?>
<div class="stats-cards">
    <?php $i = 0; foreach ($counts as $label => $num): ?>
    <div class="stat-card<?= $label === 'Okunmamış Mesaj' && $num > 0 ? ' hot' : '' ?>">
        <div class="num"><?= (int) $num ?></div>
        <div class="lbl"><?= e($label) ?></div>
    </div>
    <?php $i++; endforeach; ?>
</div>

<div class="panel">
    <h2>Son Mesajlar</h2>
    <?php if (!$latestMessages): ?>
    <p class="muted">Henüz mesaj yok.</p>
    <?php else: ?>
    <table>
        <tr><th>Tarih</th><th>Gönderen</th><th>Konu</th><th></th></tr>
        <?php foreach ($latestMessages as $m): ?>
        <tr>
            <td class="muted"><?= e($m['created_at']) ?></td>
            <td><?= $m['is_read'] ? '' : '<strong>' ?><?= e($m['name']) ?><?= $m['is_read'] ? '' : '</strong>' ?></td>
            <td><?= e(excerpt($m['subject'] ?: $m['message'], 50)) ?></td>
            <td><a class="btn btn-small btn-plain" href="messages.php?view=<?= (int) $m['id'] ?>">Görüntüle</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>

<div class="panel">
    <h2>Son Haberler</h2>
    <table>
        <tr><th>Tarih</th><th>Başlık</th><th>Durum</th><th></th></tr>
        <?php foreach ($latestNews as $n): ?>
        <tr>
            <td class="muted"><?= e($n['published_at']) ?></td>
            <td><?= e($n['title_tr']) ?></td>
            <td><?= $n['is_published'] ? 'Yayında' : 'Taslak' ?></td>
            <td><a class="btn btn-small btn-plain" href="news-edit.php?id=<?= (int) $n['id'] ?>">Düzenle</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p style="margin-top:14px"><a class="btn btn-small" href="news-edit.php">+ Yeni Haber</a></p>
</div>
<?php admin_footer(); ?>
