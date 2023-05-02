<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Recipes</title>
</head>
<body>
    <h1>Recipes</h1>
    <?php $i = 1; ?>
    <?php foreach ($all_recipes as $recipe): ?>
        <h3><?php echo $i++.". ".$recipe['title']; ?></h3>
        <p><b>Directions:</b> <?php echo $recipe['directions']; ?></p>
        <p><b>Ingredients:</b> <?php echo $recipe['ingredients']; ?></p>
        <p><b>Categories:</b> <?php echo $recipe['categories']; ?></p>
        <p><b>Created:</b> <?php echo $recipe['created_at']; ?></p><br>
    <?php endforeach; ?>
</body>
</html>
