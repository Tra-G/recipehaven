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

    <h1>Add New Posts</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="title">Title</label><br>
        <input type="text" name="title" id="title" required><br><br>

        <label for="content">Content</label><br>
        <textarea name="content" id="content" required></textarea><br><br>

        <label for="image">Image</label><br>
        <input type="file" name="image" id="image" required><br><br>

        <button type="submit">Edit Post</button>
    </form>
</body>

</html>
