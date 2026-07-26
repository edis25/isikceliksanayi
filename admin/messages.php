<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $db->delete('messages', 'id = :id', ['id' => (int) $_POST['delete_id']]);
    admin_flash('Mesaj silindi.');
    header('Location: messages.php');
    exit;
}

$viewId = (int) ($_GET['view'] ?? 0);
$viewMsg = null;
if ($viewId) {
    $viewMsg = $db->row('SELECT * FROM messages WHERE id = :id', ['id' => $viewId]);
    if ($viewMsg && !$viewMsg['is_read']) {
        $db->update('messages', ['is_read' => 1], 'id = :id', ['id' => $viewId]);
    }
}

$messages = $db->all('SELECT * FROM messages ORDER BY id DESC LIMIT 200');

admin_header('İletişim Mesajları');
?>
<?php if ($viewMsg): ?>
<div class="panel">
    <h2><?= e($viewMsg['subject'] ?: 'Konusuz mesaj') ?></h2>
    <p class="muted">
        <strong><?= e($viewMsg['name']) ?></strong>
        · <a href="mailto:<?= e($viewMsg['email']) ?>"><?= e($viewMsg['email']) ?></a>
        <?= $viewMsg['phone'] ? '· ' . e($viewMsg['phone']) : '' ?>
        · <?= e($viewMsg['created_at']) ?> · <?= $viewMsg['lang'] === 'en' ? 'İngilizce form' : 'Türkçe form' ?>
    </p>
    <div class="msg-body"><?= e($viewMsg['message']) ?></div>
    <p style="margin-top:16px">
        <a class="btn btn-small btn-secondary" href="mailto:<?= e($viewMsg['email']) ?>">Yanıtla</a>
        <a class="btn btn-small btn-plain" href="messages.php">Listeye Dön</a>
    </p>
</div>
<?php endif; ?>

<div class="panel">
    <table>
        <tr><th>Tarih</th><th>Gönderen</th><th>E-posta</th><th>Konu</th><th></th></tr>
        <?php foreach ($messages as $m): ?>
        <tr>
            <td class="muted" style="white-space:nowrap"><?= e($m['created_at']) ?></td>
            <td><?= $m['is_read'] ? e($m['name']) : '<strong>' . e($m['name']) . '</strong>' ?></td>
            <td class="muted"><?= e($m['email']) ?></td>
            <td><?= e(excerpt($m['subject'] ?: $m['message'], 40)) ?></td>
            <td style="white-space:nowrap">
                <a class="btn btn-small btn-plain" href="messages.php?view=<?= (int) $m['id'] ?>">Görüntüle</a>
                <form method="post" style="display:inline" onsubmit="return confirm('Mesaj silinsin mi?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="delete_id" value="<?= (int) $m['id'] ?>">
                    <button class="btn btn-small btn-danger" type="submit">Sil</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php if (!$messages): ?>
    <p class="muted">Henüz mesaj yok.</p>
    <?php endif; ?>
</div>
<?php admin_footer(); ?>
