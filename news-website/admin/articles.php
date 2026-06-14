<?php
require_once 'auth.php';
$db = getDB();

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM articles WHERE id = $id");
    header('Location: articles.php?msg=deleted');
    exit;
}

// Toggle status
if (isset($_GET['toggle'])) {
    $id = (int)$_GET['toggle'];
    $db->query("UPDATE articles SET status = IF(status='published','draft','published') WHERE id = $id");
    header('Location: articles.php?msg=updated');
    exit;
}

$search = trim($_GET['search'] ?? '');
$cat_filter = (int)($_GET['cat'] ?? 0);
$page = max(1, (int)($_GET['page'] ?? 1));
$per_page = 15;
$offset = ($page - 1) * $per_page;

$where = "WHERE 1=1";
if ($search) $where .= " AND (a.title LIKE '%" . $db->real_escape_string($search) . "%')";
if ($cat_filter) $where .= " AND a.category_id = $cat_filter";

$total = $db->query("SELECT COUNT(*) FROM articles a $where")->fetch_row()[0];
$pages = ceil($total / $per_page);

$articles = $db->query("
    SELECT a.*, c.name as cat_name
    FROM articles a LEFT JOIN categories c ON a.category_id = c.id
    $where ORDER BY a.created_at DESC LIMIT $per_page OFFSET $offset
")->fetch_all(MYSQLI_ASSOC);

$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
$db->close();

adminHeader('Articles', 'articles');
?>

<?php if (isset($_GET['msg'])): ?>
<div class="alert alert-success"><?= $_GET['msg'] === 'deleted' ? 'Article deleted.' : 'Article updated.' ?></div>
<?php endif; ?>

<div class="admin-table-wrap">
    <div class="admin-table-header">
        <h3>All Articles (<?= $total ?>)</h3>
        <div style="display:flex;gap:8px;align-items:center;">
            <form method="get" style="display:flex;gap:6px;">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search..." style="padding:6px 10px;border:1px solid #ddd;font-size:13px;outline:none;width:180px;">
                <select name="cat" style="padding:6px;border:1px solid #ddd;font-size:13px;outline:none;">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat_filter == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
            </form>
            <a href="article-add.php" class="btn btn-primary btn-sm">+ New Article</a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Author</th>
                <th>Views</th>
                <th>Featured</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($articles)): ?>
            <tr><td colspan="9" style="text-align:center;padding:30px;color:#888;">No articles found.</td></tr>
            <?php endif; ?>
            <?php foreach ($articles as $a): ?>
            <tr>
                <td style="color:#aaa;">#<?= $a['id'] ?></td>
                <td class="article-title-cell">
                    <strong><?= htmlspecialchars(mb_strimwidth($a['title'], 0, 65, '…')) ?></strong>
                    <small><?= htmlspecialchars($a['slug']) ?></small>
                </td>
                <td><?= htmlspecialchars($a['cat_name'] ?? '—') ?></td>
                <td><?= htmlspecialchars($a['author']) ?></td>
                <td><?= number_format($a['views']) ?></td>
                <td><?= $a['is_featured'] ? '⭐' : '—' ?></td>
                <td>
                    <a href="?toggle=<?= $a['id'] ?>" onclick="return confirm('Toggle article status?')">
                        <span class="badge badge-<?= $a['status'] ?>"><?= ucfirst($a['status']) ?></span>
                    </a>
                </td>
                <td style="white-space:nowrap;"><?= date('M j, Y', strtotime($a['published_at'])) ?></td>
                <td>
                    <div class="action-btns">
                        <a href="article-edit.php?id=<?= $a['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                        <a href="<?= SITE_URL ?>/article.php?slug=<?= $a['slug'] ?>" target="_blank" class="btn btn-sm btn-outline">↗</a>
                        <a href="?delete=<?= $a['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this article? This cannot be undone.')">Del</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($pages > 1): ?>
    <div style="padding:14px 18px;border-top:1px solid #eee;display:flex;gap:4px;">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&cat=<?= $cat_filter ?>"
           style="display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border:1px solid #ddd;font-size:13px;background:<?= $i === $page ? '#C41E1E' : '#fff' ?>;color:<?= $i === $page ? '#fff' : '#333' ?>;"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php adminFooter(); ?>
