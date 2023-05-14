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



            <div class="main-item">
                <div class="container-item">
                    <header class="admin-header2">
                        <p>Edit User</p>
                    </header>

                    <?php if (!empty($errors)): ?>
                        <script>
                            var errorList = "<?php echo implode('\n', $errors); ?>";
                            alert("Errors:\n" + errorList);
                        </script>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <script>
                            alert("<?php echo $success; ?>");
                        </script>
                    <?php endif; ?>

                    <div class="admin-list1">
                        <form method="post">
                            <div class="form-input">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Email" required>
                            </div>

                            <div class="form-input">
                                <label for="first-name">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" placeholder="First name" required>
                            </div>

                            <div class="form-input">
                                <label for="last-name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" placeholder="Last name" required>
                            </div>

                            <button class="change-btn" type="submit">Edit User</button>
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

        hamburger.addEventListener("click", function() {
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