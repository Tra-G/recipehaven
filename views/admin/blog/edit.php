<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
</head>

<body>
    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if ($success): ?>
        <p><?php echo $success; ?></p>
    <?php endif; ?>

    <h1>Edit Post</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Title</label><br>
        <input type="text" name="title" id="title" value="<?php echo $post['title']; ?>"><br><br>

        <label for="content">Content</label><br>
        <textarea name="content" id="content"><?php echo $post['content']; ?></textarea><br><br>

        <label for="image">Image</label><br>
        <input type="file" name="image" id="image"><br><br>
        <img src="<?php echo assets('blog-images/'.$post['thumbnail_path']); ?>"><br>

        <button type="submit">Edit Post</button>
    </form>
</body>

</html>
