<?php
require_once 'includes.php';

$db = getDB();
$q = trim($_GET['q'] ?? '');
$articles = [];

if ($q) {
    $safe = $db->real_escape_string($q);
    $articles = $db->query("
        SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
        FROM articles a LEFT JOIN categories c ON a.category_id = c.id
        WHERE a.status='published' AND (a.title LIKE '%$safe%' OR a.content LIKE '%$safe%' OR a.excerpt LIKE '%$safe%')
        ORDER BY a.published_at DESC LIMIT 20
    ")->fetch_all(MYSQLI_ASSOC);
}

$db->close();
renderHeader('Search: ' . $q);
?>

<div class="page-hero">
    <div class="container">
        <h1>Search Results</h1>
        <?php if ($q): ?>
        <p><?= count($articles) ?> results for "<strong><?= htmlspecialchars($q) ?></strong>"</p>
        <?php endif; ?>
    </div>
</div>

<div class="container" style="padding:28px 20px;">
    <form method="get" style="display:flex;gap:8px;margin-bottom:28px;">
        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search news..." style="flex:1;padding:10px 14px;border:1px solid #ddd;font-size:15px;outline:none;">
        <button type="submit" style="background:#C41E1E;color:#fff;border:none;padding:10px 20px;font-size:14px;font-weight:bold;cursor:pointer;">Search</button>
    </form>

    <?php if (!$q): ?>
    <p style="color:#777;">Enter a search term above to find articles.</p>
    <?php elseif (empty($articles)): ?>
    <p style="color:#777;">No articles found for "<?= htmlspecialchars($q) ?>". Try different keywords.</p>
    <?php else: ?>
    <div class="articles-grid">
        <?php foreach ($articles as $art): ?>
        <div class="article-card">
            <a href="<?= SITE_URL ?>/article.php?slug=<?= $art['slug'] ?>">
                <img src="<?= htmlspecialchars($art['image_url'] ?? 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&q=80') ?>" alt="">
            </a>
            <div class="article-card-body">
                <div class="cat-tag" style="background:<?= $art['cat_color'] ?? '#C41E1E' ?>"><?= htmlspecialchars($art['cat_name'] ?? 'News') ?></div>
                <h3><a href="<?= SITE_URL ?>/article.php?slug=<?= $art['slug'] ?>"><?= htmlspecialchars($art['title']) ?></a></h3>
                <p><?= htmlspecialchars($art['excerpt']) ?></p>
                <div class="article-meta">
                    <span><?= htmlspecialchars($art['author']) ?></span>
                    <span><?= timeAgo($art['published_at']) ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php renderFooter(); ?>
