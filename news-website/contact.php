<?php
require_once 'includes.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $message) {
        // In production: use mail() or a mail library
        $success = true;
    } else {
        $error = 'Please fill in all required fields.';
    }
}

renderHeader('Contact Us', 'contact');
?>

<div class="page-hero">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Get in touch with the LankaTimes editorial team</p>
    </div>
</div>

<div class="container">
    <div class="contact-grid">
        <!-- Contact Info -->
        <div class="contact-card">
            <h2>Get in Touch</h2>
            <?php if ($success): ?>
            <div class="alert alert-success">Thank you! Your message has been received. We'll respond within 2 business days.</div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form class="contact-form" method="post">
                <div class="form-row">
                    <input type="text" name="name" placeholder="Your Full Name *" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    <input type="email" name="email" placeholder="Email Address *" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <select name="subject">
                    <option value="">Select Subject</option>
                    <option value="news-tip">News Tip</option>
                    <option value="correction">Correction Request</option>
                    <option value="advertising">Advertising Inquiry</option>
                    <option value="general">General Inquiry</option>
                    <option value="press">Press / Media</option>
                </select>
                <textarea name="message" placeholder="Your message..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>

        <!-- Info Card -->
        <div class="contact-card">
            <h2>Contact Information</h2>
            <div class="contact-info-item">
                <strong>Address</strong>
                <span>42 D.R. Wijewardena Mawatha,<br>Colombo 10, Sri Lanka</span>
            </div>
            <div class="contact-info-item">
                <strong>Phone</strong>
                <span>+94 11 234 5678</span>
            </div>
            <div class="contact-info-item">
                <strong>Email</strong>
                <span>editor@lankatimes.lk</span>
            </div>
            <div class="contact-info-item">
                <strong>Hours</strong>
                <span>Mon – Fri: 8:00 AM – 6:00 PM IST<br>Sat: 9:00 AM – 1:00 PM IST</span>
            </div>
            <div class="contact-info-item">
                <strong>Tips</strong>
                <span>tips@lankatimes.lk<br><small style="color:#999;">Anonymous submissions welcome</small></span>
            </div>

            <div style="margin-top:24px;padding:16px;background:#f5f5f0;border-left:3px solid #C41E1E;">
                <strong style="font-size:13px;display:block;margin-bottom:6px;">Departments</strong>
                <div style="font-size:13px;color:#444;line-height:2;">
                    Editorial: <a href="mailto:editorial@lankatimes.lk">editorial@lankatimes.lk</a><br>
                    Advertising: <a href="mailto:ads@lankatimes.lk">ads@lankatimes.lk</a><br>
                    Legal: <a href="mailto:legal@lankatimes.lk">legal@lankatimes.lk</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php renderFooter(); ?>
