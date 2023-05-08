<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
</head>

<body>
    <h1>All Pending Recipes</h1>
    <ul>
        <?php if (empty($pending_recipes)): ?>
            <li>No recipe found.</li>
        <?php else: ?>
            <?php foreach ($pending_recipes as $recipe): ?>
                <li>
                    <?php echo $recipe['title']; ?>
                    [<a href="<?php echo route('admin/recipe/'.$recipe['id'].'/approve'); ?>" onclick="return confirm('Are you sure you want to publish this recipe?');">Approve</a>]
                    [<a href="<?php echo route('admin/recipe/'.$recipe['id'].'/edit'); ?>">Edit</a>]
                    [<a href="<?php echo route('admin/recipe/'.$recipe['id'].'/delete'); ?>" onclick="return confirm('Are you sure you want to delete this recipe?');">Delete</a>]
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <?php if ($pd_prev): ?>
        <a href="<?php echo route('admin/recipes', ['pd_page' => $pd_prev]); ?>">Previous</a>
    <?php endif; ?>
    <?php if ($pd_next): ?>
        <a href="<?php echo route('admin/recipes', ['pd_page' => $pd_next]); ?>">Next</a>
    <?php endif; ?>

    <h1>All Published Recipes</h1>
    <ul>
        <?php if (empty($published_recipes)): ?>
            <li>No recipe found.</li>
        <?php else: ?>
            <?php foreach ($published_recipes as $recipe): ?>
                <li>
                    <?php echo $recipe['title']; ?>
                    [<a href="<?php echo route('admin/recipe/'.$recipe['id'].'/edit'); ?>">Edit</a>]
                    [<a href="<?php echo route('admin/recipe/'.$recipe['id'].'/delete'); ?>" onclick="return confirm('Are you sure you want to delete this recipe?');">Delete</a>]
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <?php if ($pb_prev): ?>
        <a href="<?php echo route('admin/recipes', ['pb_page' => $pb_prev]); ?>">Previous</a>
    <?php endif; ?>
    <?php if ($pb_next): ?>
        <a href="<?php echo route('admin/recipes', ['pb_page' => $pb_next]); ?>">Next</a>
    <?php endif; ?>
</body>

</html>