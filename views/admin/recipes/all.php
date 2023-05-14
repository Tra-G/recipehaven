<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title; ?>
    </title>
    <!--Font icon links-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <!--Google Fonts-->
    <link rel="stylesheet" href="<?php echo assets('css/useradmin.css'); ?>">
</head>

<body>
    <div class="admin-sidemenu">
        <i style="font-size: 1.5rem;" class="fa-solid fa-xmark"></i>
        <div class="admin-container">
            <header class="admin-header">
                <a href="<?php echo route('admin/dashboard'); ?>">Admin Dashboard</a>
            </header>

            <div class="admin-link">
                <div>
                    <a href="<?php echo route('admin/users'); ?>">Manage Users</a>
                </div>

                <div>
                    <a href="<?php echo route('admin/recipes'); ?>">All Recipe</a>
                </div>

                <div>
                    <a href="<?php echo route('admin/recipe/new'); ?>">Add New Recipe</a>
                </div>

                <div>
                    <a href="<?php echo route('admin/blog'); ?>">Blog Management</a>
                </div>

                <div>
                    <a href="<?php echo route('admin/post/new'); ?>">Add New Post</a>
                </div>

                <div>
                    <a href="<?php echo route('admin/categories'); ?>">Categories</a>
                </div>

                <div>
                    <a href="<?php echo route('admin/category/new'); ?>">Add New Category</a>
                </div>

                <div>
                    <a href="<?php echo route('admin/profile'); ?>">Edit Profile</a>
                </div>

                <div>
                    <a href="<?php echo route('admin/password'); ?>">Change Password</a>
                </div>

                <form class="form-btn" action="<?php echo route('logout'); ?>">
                    <button onclick="window.location.href = '<?php echo route('logout'); ?>'">Log out</button>
                </form>
            </div>
        </div>
    </div>


    <div class="admin-main">
        <div class="main-container">
            <header class="main-head">
                <div class="hamburger">
                    <i style="font-size: 1.6rem; color: #000;" class="fa-solid fa-bars"></i>
                </div>

                <a class="nav-header" href="<?php echo route(''); ?>">
                    <i style="color: #F15025;" class="fa fa-cutlery"></i>
                    <p>recipe<span style="color: #F15025;">haven</span></p>
                </a>
            </header>



            <div class="main-item">
                <div class="container-item">
                    <header class="admin-header2">
                        <p>All Recipes</p>
                    </header>

                    <ul class="admin-list">
                        <?php if (empty($published_recipes)): ?>
                            <li>No recipe found.</li>
                        <?php else: ?>
                            <?php foreach ($published_recipes as $recipe): ?>
                                <li>
                                    <?php echo $recipe['title']; ?>
                                    [<a href="<?php echo route('admin/recipe/' . $recipe['id'] . '/edit'); ?>" style="color: blue">Edit</a>]
                                    [<a href="<?php echo route('admin/recipe/' . $recipe['id'] . '/delete'); ?>"
                                        onclick="return confirm('Are you sure you want to delete this recipe?');" style="color: blue">Delete</a>]
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($pb_prev): ?>
                            <a href="<?php echo route('admin/recipes', ['pb_page' => $pb_prev]); ?>" style="color: blue">Previous</a>
                        <?php endif; ?>
                        <?php if ($pb_next): ?>
                            <a href="<?php echo route('admin/recipes', ['pb_page' => $pb_next]); ?>" style="color: blue">Next</a>
                        <?php endif; ?>
                    </ul>

                </div>
            </div>
        </div>
    </div>



    <script>
        const hamburger = document.querySelector(".hamburger");
        const menu = document.querySelector(".admin-sidemenu");
        const closeIcon = document.querySelector('.fa-xmark');

        hamburger.addEventListener("click", function () {
            menu.classList.toggle("show");
            hamburger.classList.toggle("hide");
        });

        closeIcon.addEventListener('click', () => {
            menu.classList.remove('show');
            hamburger.classList.remove('hide');
        });
    </script>
</body>

</html>