<?php
require_once 'includes.php';

$db = getDB();
$email = trim($_POST['email'] ?? '');
$msg = '';
$type = 'error';

if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $safe = $db->real_escape_string($email);
    $exists = $db->query("SELECT id FROM subscribers WHERE email = '$safe'")->num_rows;
    if ($exists) {
        $msg = 'You are already subscribed!';
        $type = 'success';
    } else {
        $db->query("INSERT INTO subscribers (email) VALUES ('$safe')");
        $msg = 'Thank you for subscribing to LankaTimes!';
        $type = 'success';
    }
} else {
    $msg = 'Please enter a valid email address.';
}

$db->close();

renderHeader('Subscribe');
?>
<div class="container" style="padding:60px 20px;text-align:center;">
    <div style="max-width:480px;margin:0 auto;background:#fff;padding:40px;">
        <div style="font-size:40px;margin-bottom:16px;"><?= $type === 'success' ? '✅' : '❌' ?></div>
        <h2 style="font-family:Georgia,serif;font-size:22px;margin-bottom:12px;"><?= $type === 'success' ? 'Subscribed!' : 'Oops!' ?></h2>
        <p style="color:#555;margin-bottom:24px;"><?= htmlspecialchars($msg) ?></p>
        <a href="<?= SITE_URL ?>/index.php" style="background:#C41E1E;color:#fff;padding:10px 24px;font-weight:bold;font-size:14px;">Back to Homepage</a>
    </div>
</div>
<?php renderFooter(); ?>
