<?php
require_once 'auth.php';
$db = getDB();

$total_articles = $db->query("SELECT COUNT(*) FROM articles WHERE status='published'")->fetch_row()[0];
$total_drafts   = $db->query("SELECT COUNT(*) FROM articles WHERE status='draft'")->fetch_row()[0];
$total_comments = $db->query("SELECT COUNT(*) FROM comments WHERE status='approved'")->fetch_row()[0];
$total_views    = $db->query("SELECT SUM(views) FROM articles")->fetch_row()[0] ?? 0;
$total_subs     = $db->query("SELECT COUNT(*) FROM subscribers")->fetch_row()[0];

$recent_articles = $db->query("
    SELECT a.id, a.title, a.slug, a.views, a.status, a.published_at, c.name as cat_name
    FROM articles a LEFT JOIN categories c ON a.category_id = c.id
    ORDER BY a.created_at DESC LIMIT 8
")->fetch_all(MYSQLI_ASSOC);

$recent_comments = $db->query("
    SELECT cm.*, a.title as article_title, a.slug as article_slug
    FROM comments cm LEFT JOIN articles a ON cm.article_id = a.id
    ORDER BY cm.created_at DESC LIMIT 5
")->fetch_all(MYSQLI_ASSOC);

$db->close();

adminHeader('Dashboard', 'dashboard');
?>

<!-- Stat Cards -->
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-label">Published Articles</div>
        <div class="stat-value"><?= number_format($total_articles) ?></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Total Views</div>
        <div class="stat-value"><?= number_format($total_views) ?></div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Comments</div>
        <div class="stat-value"><?= number_format($total_comments) ?></div>
    </div>
    <div class="stat-card purple">
        <div class="stat-label">Subscribers</div>
        <div class="stat-value"><?= number_format($total_subs) ?></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;">
    <!-- Recent Articles -->
    <div class="admin-table-wrap">
        <div class="admin-table-header">
            <h3>Recent Articles</h3>
            <a href="article-add.php" class="btn btn-primary btn-sm">+ New Article</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_articles as $a): ?>
                <tr>
                    <td class="article-title-cell">
                        <strong><?= htmlspecialchars(mb_strimwidth($a['title'], 0, 60, '…')) ?></strong>
                    </td>
                    <td><?= htmlspecialchars($a['cat_name'] ?? '—') ?></td>
                    <td><?= number_format($a['views']) ?></td>
                    <td><span class="badge badge-<?= $a['status'] ?>"><?= ucfirst($a['status']) ?></span></td>
                    <td style="white-space:nowrap;"><?= date('M j', strtotime($a['published_at'])) ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="article-edit.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                            <a href="<?= SITE_URL ?>/article.php?slug=<?= $a['slug'] ?>" target="_blank" class="btn btn-sm btn-outline">View</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Recent Comments -->
    <div class="admin-table-wrap">
        <div class="admin-table-header">
            <h3>Recent Comments</h3>
            <a href="comments.php" class="btn btn-sm btn-outline">View all</a>
        </div>
        <?php foreach ($recent_comments as $c): ?>
        <div style="padding:12px 14px;border-bottom:1px solid #eee;">
            <div style="font-weight:bold;font-size:13px;margin-bottom:3px;"><?= htmlspecialchars($c['name']) ?></div>
            <div style="font-size:12px;color:#888;margin-bottom:5px;">on <a href="<?= SITE_URL ?>/article.php?slug=<?= $c['article_slug'] ?>" style="color:#C41E1E;"><?= htmlspecialchars(mb_strimwidth($c['article_title'] ?? '', 0, 35, '…')) ?></a></div>
            <div style="font-size:13px;color:#555;"><?= htmlspecialchars(mb_strimwidth($c['comment'], 0, 80, '…')) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php adminFooter(); ?>
