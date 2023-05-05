<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title; ?>
    </title>
</head>

<body>
    <h1>
        <?php echo $recipe['title']; ?>
    </h1>
    <p>
        <b>Directions:</b>
        <?php echo $recipe['directions']; ?>
    </p>
    <p>
        <b>Ingredients:</b>
        <?php echo $recipe['ingredients']; ?>
    </p>
    <p>
        <b>Categories:</b>
        <?php echo $recipe['categories']; ?>
    </p>
    <p>
        <b>Created:</b>
        <?php echo $recipe['created_at']; ?>
    </p>
</body>

</html>