<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
</head>

<body>
    <h1>Admin Dashboard</h1>
    <ul>
        <li>Total Users: <?php echo $total_users; ?></li>
        <li>Total Recipes: <?php echo $total_recipes; ?></li>
        <li>Total Published Recipes: <?php echo $total_published_recipes; ?></li>
        <li>Total Pending Recipes: <?php echo $total_pending_recipes; ?></li>
        <li>Total Posts: <?php echo $total_posts; ?></li>
    </ul>

    <a href="<?php echo route('admin/users'); ?>">Manage Users</a><br>
    <a href="<?php echo route('admin/recipes'); ?>">All Recipes</a><br>
    <a href="<?php echo route('admin/recipe/new'); ?>">Add New Recipe</a><br>
</body>

</html>