<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <!--Font icon links-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
    <!--Google Fonts-->
    <link rel="stylesheet" href="<?php echo assets('css/useradmin.css'); ?>">
    <link rel="stylesheet" href="<?php echo assets('css/useraddnewpost.css'); ?>">
    <link rel="stylesheet" href="<?php echo assets('css/userchangepassword.css'); ?>">
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


            <div class="main-item2">
                <div class="container-item">
                    <header class="admin-header2">
                        <p>Edit Category</p>
                    </header>

                    <?php if (!empty($errors)): ?>
                        <script>
                            var errorList = "<?php echo implode('\n', $errors); ?>";
                            alert("Errors:\n" + errorList);
                        </script>
                    <?php endif; ?>

                    <div class="admin-list2">
                        <form method="post">
                            <div class="form-input">
                                <label for="name">Category Name</label>
                                <input placeholder="Enter the title of your post"type="text" name="name" id="name" value="<?php echo $category['name']; ?>" required>
                            </div>

                            <button class="edit-btn" type="submit">Edit Category</button>
                        </form>
                    </div>
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