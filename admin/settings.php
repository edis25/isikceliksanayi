<?php
require __DIR__ . '/_bootstrap.php';
admin_require();
admin_csrf_guard();

$db = Database::get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['s'] ?? [] as $sid => $vals) {
        $db->update('settings', [
            'value_tr' => trim($vals['tr'] ?? ''),
            'value_en' => trim($vals['en'] ?? ''),
        ], 'id = :id', ['id' => (int) $sid]);
    }
    admin_flash('Ayarlar kaydedildi.');
    header('Location: settings.php');
    exit;
}

$settings = $db->all('SELECT * FROM settings ORDER BY id');

/* Tek değerli ayarlar (dilden bağımsız): her iki alana aynı değer yazılması yeterli */
$singleValue = ['phone', 'whatsapp', 'email', 'map_embed', 'linkedin', 'instagram', 'youtube', 'ga_code', 'site_name'];

admin_header('Site Ayarları');
?>
<form method="post">
    <?= csrf_field() ?>
    <div class="panel">
        <?php foreach ($settings as $s): $isSingle = in_array($s['skey'], $singleValue, true); ?>
        <div class="field">
            <label><?= e($s['label'] ?: $s['skey']) ?></label>
            <?php if ($isSingle): ?>
            <input type="text" name="s[<?= (int) $s['id'] ?>][tr]" value="<?= e($s['value_tr']) ?>"
                   oninput="this.form.querySelector('[name=\'s[<?= (int) $s['id'] ?>][en]\']').value = this.value">
            <input type="hidden" name="s[<?= (int) $s['id'] ?>][en]" value="<?= e($s['value_en'] ?: $s['value_tr']) ?>">
            <?php else: ?>
            <div class="field-row">
                <div>
                    <small>Türkçe</small>
                    <textarea name="s[<?= (int) $s['id'] ?>][tr]" style="min-height:60px"><?= e($s['value_tr']) ?></textarea>
                </div>
                <div>
                    <small>English</small>
                    <textarea name="s[<?= (int) $s['id'] ?>][en]" style="min-height:60px"><?= e($s['value_en']) ?></textarea>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <button class="btn" type="submit">Kaydet</button>
    </div>
</form>
<p class="muted">Google Maps için: Haritalar &gt; Paylaş &gt; Harita yerleştir &gt; iframe içindeki <code>src</code> adresini "Google Maps Embed URL" alanına yapıştırın.</p>
<?php admin_footer(); ?>
