<?php
require_once 'includes.php';

$db = getDB();
$slug = $_GET['slug'] ?? '';

// Get article
$stmt = $db->prepare("
    SELECT a.*, c.name as cat_name, c.slug as cat_slug, c.color as cat_color
    FROM articles a LEFT JOIN categories c ON a.category_id = c.id
    WHERE a.slug = ? AND a.status = 'published'
");
$stmt->bind_param("s", $slug);
$stmt->execute();
$article = $stmt->get_result()->fetch_assoc();

if (!$article) {
    header("HTTP/1.0 404 Not Found");
    renderHeader('Article Not Found');
    echo '<div class="container" style="padding:60px 20px;text-align:center;"><h2>Article not found.</h2><p><a href="'.SITE_URL.'/index.php">Return to homepage</a></p></div>';
    renderFooter();
    exit;
}

// Increment views
$db->query("UPDATE articles SET views = views + 1 WHERE id = {$article['id']}");

// Related articles
$cat_id = (int)$article['category_id'];
$art_id = (int)$article['id'];
$related = $db->query("
    SELECT a.id, a.title, a.slug, a.image_url, a.published_at, a.author
    FROM articles a
    WHERE a.category_id = $cat_id AND a.id != $art_id AND a.status = 'published'
    ORDER BY a.published_at DESC LIMIT 3
")->fetch_all(MYSQLI_ASSOC);

// Popular
$popular = $db->query("
    SELECT id, title, slug FROM articles WHERE status='published' ORDER BY views DESC LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

// Comments
$comments = $db->query("
    SELECT * FROM comments WHERE article_id = $art_id AND status = 'approved' ORDER BY created_at ASC
")->fetch_all(MYSQLI_ASSOC);

// Post comment
$comment_success = false;
$comment_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_name'])) {
    $name    = trim($db->real_escape_string($_POST['comment_name'] ?? ''));
    $email   = trim($db->real_escape_string($_POST['comment_email'] ?? ''));
    $comment = trim($db->real_escape_string($_POST['comment_text'] ?? ''));
    if ($name && $comment) {
        $db->query("INSERT INTO comments (article_id, name, email, comment) VALUES ($art_id, '$name', '$email', '$comment')");
        $comment_success = true;
        header("Location: " . SITE_URL . "/article.php?slug=$slug#comments");
        exit;
    } else {
        $comment_error = 'Please fill in your name and comment.';
    }
}

$db->close();

renderHeader($article['title'], $article['cat_slug'] ?? 'home');
?>

<div class="container">
    <div class="article-page">
        <!-- ARTICLE -->
        <main>
            <div class="article-full">
                <div class="article-full-header">
                    <div class="article-full-cat">
                        <span class="cat-tag" style="background:<?= $article['cat_color'] ?? '#C41E1E' ?>"><?= htmlspecialchars($article['cat_name'] ?? 'News') ?></span>
                    </div>
                    <h1 class="article-full-title"><?= htmlspecialchars($article['title']) ?></h1>
                    <div class="article-full-meta">
                        <span>By <strong><?= htmlspecialchars($article['author']) ?></strong></span>
                        <span><?= date('F j, Y, g:i A', strtotime($article['published_at'])) ?> IST</span>
                        <span>👁 <?= number_format($article['views']) ?> views</span>
                    </div>
                </div>

                <?php if ($article['image_url']): ?>
                <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="<?= htmlspecialchars($article['title']) ?>" class="article-hero-img">
                <?php endif; ?>

                <div class="article-body">
                    <?= $article['content'] ?>
                </div>

                <!-- Related -->
                <?php if (!empty($related)): ?>
                <div style="margin-top:32px; border-top:2px solid #ddd; padding-top:20px;">
                    <div class="section-label">Related Articles</div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
                        <?php foreach ($related as $r): ?>
                        <div>
                            <a href="<?= SITE_URL ?>/article.php?slug=<?= $r['slug'] ?>">
                                <img src="<?= htmlspecialchars($r['image_url'] ?? '') ?>" alt="" style="width:100%;height:90px;object-fit:cover;margin-bottom:8px;">
                                <div style="font-family:Georgia,serif;font-size:13px;line-height:1.35;color:#1a1a1a;"><?= htmlspecialchars($r['title']) ?></div>
                            </a>
                            <div class="article-meta" style="margin-top:4px;"><?= timeAgo($r['published_at']) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Comments -->
                <div class="comments-section" id="comments">
                    <div class="section-label">Comments (<?= count($comments) ?>)</div>

                    <?php if ($comment_success): ?>
                    <div class="alert alert-success">Your comment was posted successfully.</div>
                    <?php endif; ?>
                    <?php if ($comment_error): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($comment_error) ?></div>
                    <?php endif; ?>

                    <?php foreach ($comments as $c): ?>
                    <div class="comment-item">
                        <div class="comment-author"><?= htmlspecialchars($c['name']) ?></div>
                        <div class="comment-date"><?= date('F j, Y \a\t g:i A', strtotime($c['created_at'])) ?></div>
                        <div class="comment-text"><?= nl2br(htmlspecialchars($c['comment'])) ?></div>
                    </div>
                    <?php endforeach; ?>

                    <div style="margin-top:20px;">
                        <div class="section-label">Leave a Comment</div>
                        <form class="comment-form" method="post" action="#comments">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <input type="text" name="comment_name" placeholder="Your name *" required>
                                <input type="email" name="comment_email" placeholder="Email (optional)">
                            </div>
                            <textarea name="comment_text" placeholder="Write your comment..." required></textarea>
                            <button type="submit">Post Comment</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <!-- SIDEBAR -->
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
            <div class="sidebar-widget">
                <div class="sidebar-widget-title">Newsletter</div>
                <div class="sidebar-widget-body">
                    <p style="font-size:13px;color:#666;margin-bottom:12px;">Morning briefing, straight to your inbox.</p>
                    <form class="newsletter-form" method="post" action="<?= SITE_URL ?>/subscribe.php">
                        <input type="email" name="email" placeholder="Your email address" required>
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php renderFooter(); ?>
