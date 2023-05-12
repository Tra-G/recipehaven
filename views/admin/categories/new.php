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

    <h1>Add New Category</h1>
    <form method="post">
        <label for="name">Category Name</label><br>
        <input type="text" name="name" id="name"><br><br>

        <button type="submit">Edit Post</button>
    </form>
</body>

</html>
