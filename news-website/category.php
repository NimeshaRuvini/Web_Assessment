<?php
require_once 'includes.php';

$db = getDB();
$slug = $_GET['slug'] ?? '';

$stmt = $db->prepare("SELECT * FROM categories WHERE slug = ?");
$stmt->bind_param("s", $slug);
$stmt->execute();
$category = $stmt->get_result()->fetch_assoc();

if (!$category) {
    header("Location: " . SITE_URL . "/index.php");
    exit;
}

$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 9;
$offset = ($page - 1) * $per_page;

$cat_id = (int)$category['id'];
$total = $db->query("SELECT COUNT(*) FROM articles WHERE category_id = $cat_id AND status = 'published'")->fetch_row()[0];
$pages = ceil($total / $per_page);

$articles = $db->query("
    SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
    FROM articles a LEFT JOIN categories c ON a.category_id = c.id
    WHERE a.category_id = $cat_id AND a.status = 'published'
    ORDER BY a.published_at DESC
    LIMIT $per_page OFFSET $offset
")->fetch_all(MYSQLI_ASSOC);

$popular = $db->query("SELECT id, title, slug FROM articles WHERE status='published' ORDER BY views DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
$db->close();

renderHeader($category['name'], $slug);
?>

<div class="page-hero">
    <div class="container">
        <h1><?= htmlspecialchars($category['name']) ?></h1>
        <p><?= $total ?> articles in this section</p>
    </div>
</div>

<div class="container">
    <div class="main-content">
        <main>
            <?php if (empty($articles)): ?>
            <p style="color:#777;padding:40px 0;">No articles found in this category.</p>
            <?php else: ?>
            <div class="articles-grid">
                <?php foreach ($articles as $art): ?>
                <div class="article-card">
                    <a href="<?= SITE_URL ?>/article.php?slug=<?= $art['slug'] ?>">
                        <img src="<?= htmlspecialchars($art['image_url'] ?? 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&q=80') ?>" alt="<?= htmlspecialchars($art['title']) ?>">
                    </a>
                    <div class="article-card-body">
                        <div class="cat-tag" style="background:<?= $art['cat_color'] ?? '#C41E1E' ?>"><?= htmlspecialchars($art['cat_name']) ?></div>
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

            <!-- Pagination -->
            <?php if ($pages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                <?php if ($i === $page): ?>
                <span class="current"><?= $i ?></span>
                <?php else: ?>
                <a href="?slug=<?= $slug ?>&page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </main>

        <aside class="sidebar">
            <div class="sidebar-widget">
                <div class="sidebar-widget-title">Most Read</div>
                <div class="sidebar-widget-body">
                    <ol class="popular-list">
                        <?php foreach ($popular as $p): ?>
                        <li><a href="<?= SITE_URL ?>/article.php?slug=<?= $p['slug'] ?>"><?= htmlspecialchars($p['title']) ?></a></li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php renderFooter(); ?>
