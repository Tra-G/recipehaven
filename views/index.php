<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
</head>
<body>
    <h1>Welcome to My Recipe Website</h1>
    <nav>
        <ul>
            <li><a href="<?php echo route(''); ?>">Home</a></li>
            <li><a href="<?php echo route('recipes'); ?>">All Recipes</a></li>
            <li><a href="<?php echo route('about'); ?>">About</a></li>
            <li><a href="<?php echo route('blog'); ?>">Blog</a></li>
            <li><a href="<?php echo route('contact'); ?>">Contact</a></li>
            <li><a href="<?php echo route('search'); ?>">Search</a></li>
            <li><a href="<?php echo route('login'); ?>">Login</a></li>
            <li><a href="<?php echo route('register'); ?>">Register</a></li>
        </ul>
    </nav>

    <!-- search form -->
    <form action="<?php echo route('search'); ?>" method="get">
        <input type="text" name="param" placeholder="Search for recipe">
        <input type="submit" value="Search">
    </form>

    <h2>Featured Recipes</h2>
    <ul>
        <li>
            <h3>Recipe Title 1</h3>
            <p>Description of recipe 1.</p>
            <a href="#">View Recipe</a>
        </li>
        <li>
            <h3>Recipe Title 2</h3>
            <p>Description of recipe 2.</p>
            <a href="#">View Recipe</a>
        </li>
        <li>
            <h3>Recipe Title 3</h3>
            <p>Description of recipe 3.</p>
            <a href="#">View Recipe</a>
        </li>
    </ul>
    <h2>Top Rated Recipes</h2>
    <ul>
        <li>
            <h3>Recipe Title 1</h3>
            <p>Description of recipe 1.</p>
            <a href="#">View Recipe</a>
        </li>
        <li>
            <h3>Recipe Title 2</h3>
            <p>Description of recipe 2.</p>
            <a href="#">View Recipe</a>
        </li>
        <li>
            <h3>Recipe Title 3</h3>
            <p>Description of recipe 3.</p>
            <a href="#">View Recipe</a>
        </li>
    </ul>
    <h2>Newest Recipes</h2>
    <ul>
        <li>
            <h3>Recipe Title 1</h3>
            <p>Description of recipe 1.</p>
            <a href="#">View Recipe</a>
        </li>
        <li>
            <h3>Recipe Title 2</h3>
            <p>Description of recipe 2.</p>
            <a href="#">View Recipe</a>
        </li>
        <li>
            <h3>Recipe Title 3</h3>
            <p>Description of recipe 3.</p>
            <a href="#">View Recipe</a>
        </li>
    </ul>
</body>
</html>