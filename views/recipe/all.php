<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Recipes</title>
</head>
<body>
    <h1>Recipes</h1>
    <?php foreach ($all_recipes as $recipe): ?>
        <h3><a href="<?php echo route('recipe/' . $recipe['id']); ?>"><?php echo $recipe['title']; ?></a></h3>
        <p><b>Directions:</b> <?php echo $recipe['directions']; ?></p>
        <p><b>Ingredients:</b> <?php echo $recipe['ingredients']; ?></p>
        <p><b>Categories:</b> <?php echo $recipe['categories']; ?></p>
        <p><b>Created:</b> <?php echo $recipe['created_at']; ?></p><br>
    <?php endforeach; ?>

    <?php if ($prev): ?>
        <a href="<?php echo route('recipes', ['page' => $prev]); ?>">Previous</a>
    <?php endif; ?>
    <?php if ($next): ?>
        <a href="<?php echo route('recipes', ['page' => $next]); ?>">Next</a>
    <?php endif; ?>
</body>
</html>
