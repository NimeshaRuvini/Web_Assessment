<?php
require_once __DIR__ . '/config.php';

function renderHeader($pageTitle = 'LankaTimes', $activePage = 'home') {
    $db = getDB();
    $categories = $db->query("SELECT * FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
    $breaking = $db->query("SELECT id, title, slug FROM articles WHERE is_breaking=1 AND status='published' ORDER BY published_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
    $db->close();
    $today = date('l, F j, Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> — LankaTimes</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/css/style.css">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🦁</text></svg>">
</head>
<body>

<!-- Top Bar -->
<div class="topbar">
    <div class="topbar-inner">
        <span><?= $today ?></span>
        <div class="topbar-right">
            <a href="<?= SITE_URL ?>/about.php">About</a>
            <a href="<?= SITE_URL ?>/contact.php">Contact</a>
            <a href="<?= SITE_URL ?>/admin/">Admin</a>
        </div>
    </div>
</div>

<!-- Header -->
<header class="site-header">
    <div class="header-inner">
        <div>
            <a href="<?= SITE_URL ?>/index.php" class="site-logo">Lanka<span>Times</span></a>
            <div class="site-tagline">Sri Lanka's Trusted Source for News</div>
        </div>
        <div class="header-date">
            <div><?= date('H:i') ?> IST</div>
            <form class="header-search" method="get" action="<?= SITE_URL ?>/search.php">
                <input type="text" name="q" placeholder="Search news..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button type="submit">Search</button>
            </form>
        </div>
    </div>
    <!-- Nav -->
    <nav class="main-nav">
        <div class="nav-inner">
            <a href="<?= SITE_URL ?>/index.php" class="<?= $activePage === 'home' ? 'active' : '' ?>">Home</a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?= SITE_URL ?>/category.php?slug=<?= $cat['slug'] ?>" class="<?= ($activePage === $cat['slug']) ? 'active' : '' ?>"><?= htmlspecialchars($cat['name']) ?></a>
            <?php endforeach; ?>
        </div>
    </nav>
</header>

<!-- Breaking News -->
<?php if (!empty($breaking)): ?>
<div class="breaking-bar">
    <div class="breaking-label">Breaking</div>
    <div class="breaking-ticker">
        <div class="breaking-ticker-inner">
            <?php foreach (array_merge($breaking, $breaking) as $b): ?>
            <a href="<?= SITE_URL ?>/article.php?slug=<?= $b['slug'] ?>"><?= htmlspecialchars($b['title']) ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
}

function renderFooter() {
    $db = getDB();
    $categories = $db->query("SELECT name, slug FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
    $db->close();
?>
<footer class="site-footer">
    <div class="container">
        <div class="footer-top">
            <div>
                <div class="footer-logo">Lanka<span>Times</span></div>
                <p class="footer-about">LankaTimes is Sri Lanka's trusted digital news platform, delivering accurate, timely, and independent journalism since 2015. Based in Colombo, we cover politics, economy, sports, culture, and more.</p>
            </div>
            <div class="footer-col">
                <h4>Sections</h4>
                <ul>
                    <?php foreach ($categories as $cat): ?>
                    <li><a href="<?= SITE_URL ?>/category.php?slug=<?= $cat['slug'] ?>"><?= htmlspecialchars($cat['name']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Company</h4>
                <ul>
                    <li><a href="<?= SITE_URL ?>/about.php">About Us</a></li>
                    <li><a href="<?= SITE_URL ?>/contact.php">Contact</a></li>
                    <li><a href="<?= SITE_URL ?>/about.php#team">Our Team</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Use</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li><a href="mailto:editor@lankatimes.lk">editor@lankatimes.lk</a></li>
                    <li><a href="tel:+94112345678">+94 11 234 5678</a></li>
                    <li><a href="#">42 D.R. Wijewardena Mawatha, Colombo 10</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?= date('Y') ?> LankaTimes. All rights reserved. Built with PHP &amp; MySQL.
        </div>
    </div>
</footer>
<script src="<?= SITE_URL ?>/js/main.js"></script>
</body>
</html>
<?php
}

function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    if ($diff->d > 7) return date('M j, Y', strtotime($datetime));
    if ($diff->d > 0) return $diff->d . 'd ago';
    if ($diff->h > 0) return $diff->h . 'h ago';
    if ($diff->i > 0) return $diff->i . 'm ago';
    return 'Just now';
}

function categoryColor($slug) {
    $colors = [
        'politics' => '#C62828',
        'economy' => '#1565C0',
        'sports' => '#2E7D32',
        'technology' => '#6A1B9A',
        'world' => '#E65100',
        'culture' => '#AD1457',
    ];
    return $colors[$slug] ?? '#333';
}
?>
