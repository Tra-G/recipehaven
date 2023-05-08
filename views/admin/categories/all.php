<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
</head>

<body>
    <h1>All Categories</h1>
    <ul>
        <?php if (empty($categories)): ?>
            <li>No categories found.</li>
        <?php else: ?>
            <?php foreach ($categories as $cat): ?>
                <li>
                    <?php echo $cat['name']; ?>
                    [<a href="<?php echo route('admin/category/'.$cat['id'].'/edit'); ?>">Edit</a>]
                    [<a href="<?php echo route('admin/category/'.$cat['id'].'/delete'); ?>" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>]
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</body>

</html>