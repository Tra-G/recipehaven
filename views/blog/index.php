<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
</head>

<body>
    <h1>All Blog Posts</h1>
    <ul>
        <?php foreach ($all_posts as $post): ?>
            <li><a href="<?php echo route('blog/'.$post['id']); ?>"><?php echo $post['title']; ?></a></li>
        <?php endforeach; ?>
    </ul>

    <?php if ($prev): ?>
        <a href="<?php echo route('blog', ['page' => $prev]); ?>">Previous</a>
    <?php endif; ?>
    <?php if ($next): ?>
        <a href="<?php echo route('blog', ['page' => $next]); ?>">Next</a>
    <?php endif; ?>
</body>

</html>