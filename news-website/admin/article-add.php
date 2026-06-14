<?php
require_once 'auth.php';
$db = getDB();

$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetch_all(MYSQLI_ASSOC);
$article = null;
$editing = false;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $article = $db->query("SELECT * FROM articles WHERE id = $id")->fetch_assoc();
    $editing = true;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $content     = trim($_POST['content'] ?? '');
    $excerpt     = trim($_POST['excerpt'] ?? '');
    $image_url   = trim($_POST['image_url'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $author      = trim($_POST['author'] ?? 'LankaTimes Staff');
    $status      = in_array($_POST['status'] ?? '', ['published', 'draft']) ? $_POST['status'] : 'draft';
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_breaking = isset($_POST['is_breaking']) ? 1 : 0;

    if (!$title || !$content) {
        $error = 'Title and content are required.';
    } else {
        $slug = slugify($title);
        // Ensure unique slug
        $base_slug = $slug;
        $i = 1;
        $edit_id = $editing ? (int)$_GET['id'] : 0;
        while (true) {
            $check = $db->query("SELECT id FROM articles WHERE slug = '" . $db->real_escape_string($slug) . "' AND id != $edit_id");
            if ($check->num_rows === 0) break;
            $slug = $base_slug . '-' . (++$i);
        }

        $title_s     = $db->real_escape_string($title);
        $content_s   = $db->real_escape_string($content);
        $excerpt_s   = $db->real_escape_string($excerpt);
        $image_url_s = $db->real_escape_string($image_url);
        $author_s    = $db->real_escape_string($author);
        $slug_s      = $db->real_escape_string($slug);
        $cat_val     = $category_id ?: 'NULL';

        if ($editing) {
            $db->query("UPDATE articles SET
                title='$title_s', slug='$slug_s', excerpt='$excerpt_s', content='$content_s',
                image_url='$image_url_s', category_id=$cat_val, author='$author_s',
                status='$status', is_featured=$is_featured, is_breaking=$is_breaking,
                updated_at=NOW()
                WHERE id = $edit_id");
            $success = 'Article updated successfully.';
            $article = $db->query("SELECT * FROM articles WHERE id = $edit_id")->fetch_assoc();
        } else {
            $db->query("INSERT INTO articles (title, slug, excerpt, content, image_url, category_id, author, status, is_featured, is_breaking)
                VALUES ('$title_s', '$slug_s', '$excerpt_s', '$content_s', '$image_url_s', $cat_val, '$author_s', '$status', $is_featured, $is_breaking)");
            $new_id = $db->insert_id;
            header("Location: article-edit.php?id=$new_id&msg=created");
            exit;
        }
    }
}

if (isset($_GET['msg']) && $_GET['msg'] === 'created') {
    $success = 'Article created successfully!';
}

$db->close();
$page_title = $editing ? 'Edit Article' : 'New Article';
adminHeader($page_title, $editing ? 'articles' : 'article-add');
?>

<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
    <a href="articles.php" style="font-size:13px;color:#888;">← Back to Articles</a>
    <?php if ($editing && $article): ?>
    <a href="<?= SITE_URL ?>/article.php?slug=<?= $article['slug'] ?>" target="_blank" class="btn btn-outline btn-sm">↗ View on Site</a>
    <?php endif; ?>
</div>

<form method="post" class="admin-form">
    <div class="form-row form-row-2" style="margin-bottom:18px;">
        <div class="form-group" style="margin:0;grid-column:1/-1;">
            <label>Article Title *</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($article['title'] ?? '') ?>" placeholder="Enter article headline...">
        </div>
    </div>

    <div class="form-row form-row-2">
        <div class="form-group">
            <label>Category</label>
            <select name="category_id">
                <option value="">— Select Category —</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($article['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Author</label>
            <input type="text" name="author" value="<?= htmlspecialchars($article['author'] ?? 'LankaTimes Staff') ?>">
        </div>
    </div>

    <div class="form-group">
        <label>Excerpt (Short Summary)</label>
        <textarea name="excerpt" style="height:70px;" placeholder="Brief description shown on listing pages..."><?= htmlspecialchars($article['excerpt'] ?? '') ?></textarea>
    </div>

    <div class="form-group">
        <label>Article Content * (HTML supported)</label>
        <textarea name="content" id="content-editor" required><?= htmlspecialchars($article['content'] ?? '') ?></textarea>
        <div class="form-hint">Wrap paragraphs in &lt;p&gt; tags. HTML formatting is supported.</div>
    </div>

    <div class="form-group">
        <label>Feature Image URL</label>
        <input type="url" name="image_url" value="<?= htmlspecialchars($article['image_url'] ?? '') ?>" placeholder="https://images.unsplash.com/...">
        <div class="form-hint">Paste a direct image URL. Recommended size: 800×500px.</div>
        <?php if (!empty($article['image_url'])): ?>
        <img src="<?= htmlspecialchars($article['image_url']) ?>" style="margin-top:8px;max-height:120px;max-width:240px;object-fit:cover;">
        <?php endif; ?>
    </div>

    <div class="form-row form-row-3">
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="published" <?= ($article['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="draft" <?= ($article['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
            </select>
        </div>
        <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px;">
            <label class="form-check">
                <input type="checkbox" name="is_featured" <?= ($article['is_featured'] ?? 0) ? 'checked' : '' ?>>
                Mark as Featured
            </label>
        </div>
        <div class="form-group" style="display:flex;align-items:flex-end;padding-bottom:4px;">
            <label class="form-check">
                <input type="checkbox" name="is_breaking" <?= ($article['is_breaking'] ?? 0) ? 'checked' : '' ?>>
                Breaking News Ticker
            </label>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $editing ? 'Update Article' : 'Publish Article' ?></button>
        <?php if ($editing): ?>
        <a href="?id=<?= $article['id'] ?>&delete=1" class="btn btn-danger" onclick="return confirm('Delete this article permanently?')">Delete Article</a>
        <?php endif; ?>
        <a href="articles.php" class="btn btn-outline">Cancel</a>
    </div>
</form>

<?php adminFooter(); ?>
