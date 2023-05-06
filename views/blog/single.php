<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
</head>

<body>
    <div class="post-container">
        <h1><?php echo $post['title']; ?></h1>
        <p>By <?php echo $post['first_name'] .' '. $post['last_name']; ?> | Published on <?php echo $post['created_at']; ?></p>
        <p><?php echo $post['content']; ?></p>
    </div>
</body>

</html>