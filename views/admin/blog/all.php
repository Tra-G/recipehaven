<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
</head>

<body>
    <h1>All Posts</h1>
    <ul>
        <?php if (empty($posts)): ?>
            <li>No posts found.</li>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <li>
                    <?php echo $post['title']; ?>
                    [<a href="<?php echo route('blog/'.$post['id']); ?>">View</a>
                    [<a href="<?php echo route('admin/post/'.$post['id'].'/edit'); ?>">Edit</a>]
                    [<a href="<?php echo route('admin/post/'.$post['id'].'/delete'); ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>]
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <?php if ($prev): ?>
        <a href="<?php echo route('admin/blog', ['page' => $prev]); ?>">Previous</a>
    <?php endif; ?>
    <?php if ($next): ?>
        <a href="<?php echo route('admin/blog', ['page' => $next]); ?>">Next</a>
    <?php endif; ?>
</body>

</html>