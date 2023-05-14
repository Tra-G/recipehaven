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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <!--Google Fonts-->
    <link rel="stylesheet" href="<?php echo assets('css/useradmin.css'); ?>">
    <link rel="stylesheet" href="<?php echo assets('css/usermanageusers.css'); ?>">
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

            <div class="modal-container">
                <div class="container-item">
                    <header class="manage-header2">
                        <p>Users List</p>
                    </header>

                    <ul class="modal-list">
                        <?php if (empty($users)): ?>
                            <li>No users found.</li>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <li>
                                    <?php echo $user['first_name'] . ' ' . $user['last_name']; ?> (
                                    <?php echo $user['email']; ?>)
                                    [<a href="<?php echo route('admin/user/' . $user['id'] . '/edit'); ?>" style="color: blue">Edit</a>]
                                    [<a href="<?php echo route('admin/user/' . $user['id'] . '/delete'); ?>"
                                        onclick="return confirm('Are you sure you want to delete this user?');" style="color: blue">Delete</a>]
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if ($prev): ?>
                            <a href="<?php echo route('admin/users', ['page' => $prev]); ?>" style="color: blue">Previous</a>
                        <?php endif; ?>
                        <?php if ($next): ?>
                            <a href="<?php echo route('admin/users', ['page' => $next]); ?>" style="color: blue">Next</a>
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