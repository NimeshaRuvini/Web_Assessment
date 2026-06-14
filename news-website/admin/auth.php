<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

function adminHeader($title, $active = '') {
    $name = htmlspecialchars($_SESSION['admin_name'] ?? 'Admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> — LankaTimes Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">Lanka<span>Times</span></div>
        <nav class="sidebar-nav">
            <div class="sidebar-section">Main</div>
            <a href="dashboard.php" class="<?= $active === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
            <a href="articles.php" class="<?= $active === 'articles' ? 'active' : '' ?>">Articles</a>
            <a href="article-add.php" class="<?= $active === 'article-add' ? 'active' : '' ?>">New Article</a>
            <div class="sidebar-section">Manage</div>
            <a href="categories.php" class="<?= $active === 'categories' ? 'active' : '' ?>">Categories</a>
        </nav>
        <div class="sidebar-footer">
            Logged in as <strong><?= $name ?></strong><br>
            <a href="logout.php">Sign out</a> &nbsp;·&nbsp; <a href="<?= SITE_URL ?>/index.php" target="_blank">View site</a>
        </div>
    </aside>

    <!-- Main -->
    <div class="admin-main">
        <div class="admin-topbar">
            <h1><?= htmlspecialchars($title) ?></h1>
            <div class="user-info">
                <?= $name ?> &nbsp;
                <a href="<?= SITE_URL ?>/index.php" target="_blank">↗ Site</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
        <div class="admin-body">
<?php
}

function adminFooter() {
?>
        </div>
    </div>
</div>
</body>
</html>
<?php
}

function slugify($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}
?>
