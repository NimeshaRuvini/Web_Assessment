<?php
require_once 'auth.php';
$db = getDB();

$error = '';
$success = '';

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $db->query("DELETE FROM categories WHERE id = $id");
    header('Location: categories.php?msg=deleted');
    exit;
}

// Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($db->real_escape_string($_POST['name'] ?? ''));
    $slug  = trim($db->real_escape_string($_POST['slug'] ?? ''));
    $color = $db->real_escape_string($_POST['color'] ?? '#C41E1E');
    $id    = (int)($_POST['id'] ?? 0);

    if (!$name || !$slug) {
        $error = 'Name and slug are required.';
    } else {
        if ($id) {
            $db->query("UPDATE categories SET name='$name', slug='$slug', color='$color' WHERE id=$id");
            $success = 'Category updated.';
        } else {
            $db->query("INSERT INTO categories (name, slug, color) VALUES ('$name', '$slug', '$color')");
            $success = 'Category added.';
        }
    }
}

$categories = $db->query("
    SELECT c.*, COUNT(a.id) as article_count
    FROM categories c LEFT JOIN articles a ON a.category_id = c.id AND a.status = 'published'
    GROUP BY c.id ORDER BY c.name
")->fetch_all(MYSQLI_ASSOC);

$edit_cat = null;
if (isset($_GET['edit'])) {
    foreach ($categories as $c) if ($c['id'] == $_GET['edit']) { $edit_cat = $c; break; }
}

$db->close();
adminHeader('Categories', 'categories');
?>

<?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<?php if (isset($_GET['msg'])): ?><div class="alert alert-success">Category deleted.</div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 380px;gap:20px;">
    <!-- List -->
    <div class="admin-table-wrap">
        <div class="admin-table-header"><h3>All Categories</h3></div>
        <table>
            <thead><tr><th>Name</th><th>Slug</th><th>Color</th><th>Articles</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($cat['name']) ?></strong></td>
                    <td><code style="font-size:12px;color:#888;"><?= htmlspecialchars($cat['slug']) ?></code></td>
                    <td><span style="display:inline-block;width:18px;height:18px;background:<?= $cat['color'] ?>;vertical-align:middle;margin-right:6px;border-radius:2px;"></span><?= $cat['color'] ?></td>
                    <td><?= $cat['article_count'] ?></td>
                    <td>
                        <div class="action-btns">
                            <a href="?edit=<?= $cat['id'] ?>" class="btn btn-sm btn-outline">Edit</a>
                            <a href="?delete=<?= $cat['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Del</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Form -->
    <div class="admin-form">
        <h3 style="margin-bottom:16px;"><?= $edit_cat ? 'Edit Category' : 'Add New Category' ?></h3>
        <form method="post">
            <?php if ($edit_cat): ?><input type="hidden" name="id" value="<?= $edit_cat['id'] ?>"><?php endif; ?>
            <div class="form-group">
                <label>Category Name *</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($edit_cat['name'] ?? '') ?>" placeholder="e.g. Politics" id="cat-name" oninput="autoSlug()">
            </div>
            <div class="form-group">
                <label>Slug *</label>
                <input type="text" name="slug" id="cat-slug" required value="<?= htmlspecialchars($edit_cat['slug'] ?? '') ?>" placeholder="e.g. politics">
            </div>
            <div class="form-group">
                <label>Accent Color</label>
                <input type="color" name="color" value="<?= $edit_cat['color'] ?? '#C41E1E' ?>" style="width:60px;height:36px;padding:2px;border:1px solid #ddd;">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $edit_cat ? 'Update' : 'Add Category' ?></button>
                <?php if ($edit_cat): ?><a href="categories.php" class="btn btn-outline">Cancel</a><?php endif; ?>
            </div>
        </form>
    </div>
</div>

<script>
function autoSlug() {
    const name = document.getElementById('cat-name').value;
    document.getElementById('cat-slug').value = name.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/[\s]+/g,'-').trim();
}
</script>

<?php adminFooter(); ?>
