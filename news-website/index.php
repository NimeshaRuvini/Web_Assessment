<?php
require_once 'includes.php';

$db = getDB();

// Featured articles
$featured = $db->query("
    SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
    FROM articles a LEFT JOIN categories c ON a.category_id = c.id
    WHERE a.status='published' AND a.is_featured=1
    ORDER BY a.published_at DESC LIMIT 3
")->fetch_all(MYSQLI_ASSOC);

// Latest articles (not featured)
$latest = $db->query("
    SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
    FROM articles a LEFT JOIN categories c ON a.category_id = c.id
    WHERE a.status='published'
    ORDER BY a.published_at DESC LIMIT 6
")->fetch_all(MYSQLI_ASSOC);

// Sports articles
$sports = $db->query("
    SELECT a.*, c.name as cat_name, c.slug as cat_slug
    FROM articles a LEFT JOIN categories c ON a.category_id = c.id
    WHERE a.status='published' AND c.slug='sports'
    ORDER BY a.published_at DESC LIMIT 3
")->fetch_all(MYSQLI_ASSOC);

// Popular articles
$popular = $db->query("
    SELECT id, title, slug, views, published_at
    FROM articles WHERE status='published'
    ORDER BY views DESC LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Category counts
$cat_counts = $db->query("
    SELECT c.name, c.slug, c.color, COUNT(a.id) as cnt
    FROM categories c LEFT JOIN articles a ON a.category_id=c.id AND a.status='published'
    GROUP BY c.id ORDER BY cnt DESC
")->fetch_all(MYSQLI_ASSOC);

$db->close();

renderHeader('Home', 'home');
?>

<div class="container">
    <div class="main-content">
        <!-- MAIN -->
        <main>
            <?php if (!empty($featured)): ?>
            <!-- Featured Grid -->
            <div class="featured-grid">
                <!-- Main Feature -->
                <div class="featured-main">
                    <a href="<?= SITE_URL ?>/article.php?slug=<?= $featured[0]['slug'] ?>">
                        <img src="<?= htmlspecialchars($featured[0]['image_url'] ?? 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800&q=80') ?>" alt="<?= htmlspecialchars($featured[0]['title']) ?>">
                        <div class="overlay">
                            <div class="cat-tag" style="background:<?= $featured[0]['cat_color'] ?? '#C41E1E' ?>"><?= htmlspecialchars($featured[0]['cat_name'] ?? 'News') ?></div>
                            <h2><?= htmlspecialchars($featured[0]['title']) ?></h2>
                            <p><?= htmlspecialchars($featured[0]['author']) ?> &nbsp;·&nbsp; <?= timeAgo($featured[0]['published_at']) ?></p>
                        </div>
                    </a>
                </div>
                <!-- Side Features -->
                <div class="featured-side">
                    <?php foreach (array_slice($featured, 1) as $f): ?>
                    <div class="featured-side-item">
                        <a href="<?= SITE_URL ?>/article.php?slug=<?= $f['slug'] ?>">
                            <img src="<?= htmlspecialchars($f['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($f['title']) ?>">
                            <div class="cat-tag" style="background:<?= $f['cat_color'] ?? '#C41E1E' ?>;font-size:9px;"><?= htmlspecialchars($f['cat_name'] ?? 'News') ?></div>
                            <h3><?= htmlspecialchars($f['title']) ?></h3>
                        </a>
                        <div class="article-meta" style="margin-top:6px;">
                            <span><?= timeAgo($f['published_at']) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Latest News -->
            <div class="section-label">Latest News</div>
            <div class="articles-grid">
                <?php foreach ($latest as $art): ?>
                <div class="article-card">
                    <a href="<?= SITE_URL ?>/article.php?slug=<?= $art['slug'] ?>">
                        <img src="<?= htmlspecialchars($art['image_url'] ?? 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&q=80') ?>" alt="<?= htmlspecialchars($art['title']) ?>">
                    </a>
                    <div class="article-card-body">
                        <div class="cat-tag" style="background:<?= $art['cat_color'] ?? '#C41E1E' ?>"><?= htmlspecialchars($art['cat_name'] ?? 'News') ?></div>
                        <h3><a href="<?= SITE_URL ?>/article.php?slug=<?= $art['slug'] ?>"><?= htmlspecialchars($art['title']) ?></a></h3>
                        <p><?= htmlspecialchars($art['excerpt']) ?></p>
                        <div class="article-meta">
                            <span>By <?= htmlspecialchars($art['author']) ?></span>
                            <span><?= timeAgo($art['published_at']) ?></span>
                            <span>👁 <?= number_format($art['views']) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Sports Section -->
            <?php if (!empty($sports)): ?>
            <div class="section-label">Sports</div>
            <?php foreach ($sports as $s): ?>
            <div class="article-list-item">
                <a href="<?= SITE_URL ?>/article.php?slug=<?= $s['slug'] ?>">
                    <img src="<?= htmlspecialchars($s['image_url'] ?? 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=200&q=80') ?>" alt="<?= htmlspecialchars($s['title']) ?>">
                </a>
                <div class="article-list-body">
                    <div class="cat-tag" style="background:#2E7D32"><?= htmlspecialchars($s['cat_name']) ?></div>
                    <h4><a href="<?= SITE_URL ?>/article.php?slug=<?= $s['slug'] ?>"><?= htmlspecialchars($s['title']) ?></a></h4>
                    <div class="article-meta">
                        <span><?= htmlspecialchars($s['author']) ?></span>
                        <span><?= timeAgo($s['published_at']) ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </main>

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <!-- Popular -->
            <div class="sidebar-widget">
                <div class="sidebar-widget-title">Most Read</div>
                <div class="sidebar-widget-body">
                    <ol class="popular-list">
                        <?php foreach ($popular as $p): ?>
                        <li>
                            <a href="<?= SITE_URL ?>/article.php?slug=<?= $p['slug'] ?>"><?= htmlspecialchars($p['title']) ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>

            <!-- Categories -->
            <div class="sidebar-widget">
                <div class="sidebar-widget-title">Browse by Category</div>
                <div class="sidebar-widget-body">
                    <ul class="category-list">
                        <?php foreach ($cat_counts as $cat): ?>
                        <li>
                            <a href="<?= SITE_URL ?>/category.php?slug=<?= $cat['slug'] ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                                <span class="cat-count"><?= $cat['cnt'] ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="sidebar-widget">
                <div class="sidebar-widget-title">Newsletter</div>
                <div class="sidebar-widget-body">
                    <p style="font-size:13px;color:#666;margin-bottom:12px;">Get the top Sri Lanka news delivered to your inbox every morning.</p>
                    <form class="newsletter-form" method="post" action="<?= SITE_URL ?>/subscribe.php">
                        <input type="email" name="email" placeholder="Your email address" required>
                        <button type="submit">Subscribe</button>
                    </form>
                    <p class="newsletter-note">No spam. Unsubscribe anytime.</p>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php renderFooter(); ?>
