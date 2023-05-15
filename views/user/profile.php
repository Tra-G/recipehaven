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
    <link rel="stylesheet" href="<?php echo assets('css/board.css'); ?>">
    <link rel="stylesheet" href="<?php echo assets('css/useradmin.css'); ?>">
</head>

<body>
    <div class="sidemenu">
        <div class="flex-sidebar">
            <i style="font-size: 1.5rem;" class="fa-solid fa-xmark"></i>

            <header class="side-header">
                <i style="background-color: #F15025; padding: .5rem .7rem; border-radius: 1rem; font-size: 1rem;"
                    class="fa-solid fa-user"></i>
                <div class="header-names">
                    <p>
                        <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                    </p>
                    <p>
                        <?php echo $user['email']; ?>
                    </p>
                </div>
            </header>

            <div class="user-details">

                <div class="users personal-info" style="display: flex; align-items: center;">
                    <i style="color: #F15025;" ; class="fa-solid fa-user"></i>
                    <span>Personal Info</span>
                </div>

                <div class="users savebtn" style="display: flex; align-items: center;">
                    <i style="color: #F15025;" class="fa-solid fa-heart"></i>
                    <span>Saved Recipes</span>
                </div>

                <div class="users personal-recipe" style="display: flex; align-items: center;">
                    <i style="color: #F15025;" class="fa-solid fa-bowl-food"></i>
                    <span>Personal Recipe</span>
                </div>

                <div class="users editLink" style="display: flex; align-items: center;">
                    <i style="color: #F15025;" class="fa-solid fa-message"></i>
                    <span>Edit Profile</span>
                </div>

                <div class="users changeBtn" style="display: flex; align-items: center;">
                    <i style="color: #F15025;" class="fa-solid fa-lock"></i>
                    <span>Change Password</span>
                </div>
            </div>

            <form action="<?php echo route('logout'); ?>">
                <button class="sidebar-btn" onclick="window.location.href = '<?php echo route('logout'); ?>'">Log
                    Out</button>
            </form>
        </div>
    </div>


    <div class="bodymenu">
        <header class="main-header">
            <div class="hamburger">
                <i style="font-size: 1.6rem; color: #000;" class="fa-solid fa-bars"></i>
            </div>

            <a class="nav-header" href="<?php echo route(''); ?>">
                <i style="color: #F15025;" class="fa fa-cutlery"></i>
                <p>recipe<span style="color: #F15025;">haven</span></p>
            </a>
        </header>

        <div class="main-container">
            <div class="personal-info">
                <header class="info-header">
                    <p>Personal Info</p>
                </header>


                <form>
                    <div class="form">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>"
                            placeholder="Your email" disabled>
                    </div>

                    <div class="form">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>"
                            placeholder="Your first name" disabled>
                    </div>

                    <div class="form">
                        <label for="last_name">Last name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>"
                            placeholder="Your last name" disabled>
                    </div>
                </form>
            </div>

        </div>


        <div class="savedrecipe">
            <div class="savecontainer">
                <header class="saveheader">
                    <p>Saved Recipes</p>
                </header>

                <ul class="admin-list1">
                    <?php if ($saved_recipes): ?>
                        <?php foreach ($saved_recipes as $recipe): ?>
                            <li id="recipe-<?php echo $recipe['id']; ?>">
                                <a href="<?php echo route('recipe/' . $recipe['id']); ?>" style="color: blue;"><?php echo $recipe['title']; ?></a>
                                [<a class="save-button" data-recipe-id="<?php echo $recipe['id']; ?>"
                                    href="<?php echo route('recipe/' . $recipe['id'] . '/unsave'); ?>"
                                    style="color: blue;">Remove</a>]
                            </li>
                        <?php endforeach; ?>
                        <?php if ($saved_prev): ?>
                            <a href="<?php echo route('user/profile', ['recipe_page' => $saved_prev]); ?>"
                                style="color: blue;">Previous</a>
                        <?php endif; ?>
                        <?php if ($saved_next): ?>
                            <a href="<?php echo route('user/profile', ['recipe_page' => $saved_next]); ?>"
                                style="color: blue;">Next</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <li>No saved recipes</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>


        <div class="myrecipe">
            <div class="saved-container">
                <header class="saved-header">
                    <p style="font-size: 1.6rem; font-weight: 600; margin-top: 2rem;">My Recipes</p>
                    <p style="font-size: 1rem;">Recipes you save will be stored here</p>
                </header>

                <ul class="admin-list1">
                    <?php if ($mine): ?>
                        <?php foreach ($mine as $recipe): ?>
                            <li id="myrecipe-<?php echo $recipe['id']; ?>">
                                <a href="<?php echo route('recipe/' . $recipe['id']); ?>"
                                style="color: blue;"><?php echo $recipe['title']; ?></a>

                                [<a href="<?php echo route('recipe/' . $recipe['id'] . '/edit'); ?>"
                                style="color: blue;">Edit</a>]

                                [<a class="delete-button" data-recipe-id="<?php echo $recipe['id']; ?>"
                                    href="<?php echo route('recipe/' . $recipe['id'] . '/delete'); ?>"
                                style="color: blue;">Delete</a>]
                            </li>
                        <?php endforeach; ?>
                        <?php if ($mine_prev): ?>
                            <a href="<?php echo route('user/profile', ['mine_page' => $mine_prev]); ?>"
                                style="color: blue;">Previous</a>
                        <?php endif; ?>
                        <?php if ($mine_next): ?>
                            <a href="<?php echo route('user/profile', ['mine_page' => $mine_next]); ?>"
                                style="color: blue;">Next</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <li>No recipes</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="edit-profile">
            <div class="profile-container">
                <header class="edit-header">
                    <p>Edit Profile</p>
                </header>

                <form id="editForm" class="edit-form" id="editForm" action="<?php echo route('user/edit'); ?>"
                    method="post">
                    <div>
                        <label for="first_name">First Name</label>
                        <input type="text" id="name" name="first_name" value="<?php echo $user['first_name']; ?>"
                            required>
                    </div>

                    <div>
                        <label for="last_name">Last Name</label>
                        <input type="text" id="name" name="last_name" value="<?php echo $user['last_name']; ?>"
                            required>
                    </div>

                    <div>
                        <label for="email">Email </label>
                        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                    </div>

                    <button class="edit-btn" type="submit">
                        Save Changes
                    </button>
                </form>
            </div>
        </div>


        <div class="change-password">
            <div class="password-container">
                <header class="password-header">
                    <p>Change Password</p>
                </header>


                <form class="change-form" id="changePasswordForm" action="<?php echo route('user/password'); ?>"
                    method="post">
                    <div>
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>

                    <div>
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>

                    <div>
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password">
                    </div>

                    <button class="changebtn" type="submit">Confirm password</button>
                </form>

            </div>
        </div>

    </div>



    <script>
        const hamburger = document.querySelector(".hamburger");
        const menu = document.querySelector(".sidemenu");
        const closeIcon = document.querySelector('.fa-xmark');

        hamburger.addEventListener("click", function () {
            menu.classList.toggle("show");
            hamburger.classList.toggle("hide");
        });

        closeIcon.addEventListener('click', () => {
            menu.classList.remove('show');
            hamburger.classList.remove('hide');
        });



        /*PERSONAL INFO*/
        let personalInfo = document.querySelector('.personal-info');
        let modalBox = document.querySelector('.main-container');
        let myRecipe = document.querySelector('.personal-recipe');
        let myRecipeInfo = document.querySelector('.myrecipe');
        let savemodal = document.querySelector('.savedrecipe');
        let savebtn = document.querySelector('.savebtn');
        let editbtn = document.querySelector('.editLink');
        let editModal = document.querySelector('.edit-profile');
        let changeModal = document.querySelector('.change-password');
        let changelink = document.querySelector('.changeBtn')

        function personalInfoModal() {
            modalBox.style.display = 'block';
            personalInfo.style.color = '#d54215';
            myRecipeInfo.style.display = 'none';
            myRecipe.style.color = 'unset';
            savemodal.style.display = 'none';
            savebtn.style.color = 'unset;'
            editModal.style.display = 'none';
            editbtn.style.color = 'unset';
            changeModal.style.display = 'none';
            changelink.style.color = 'unset';
        }



        function myRecipeBtn() {
            myRecipeInfo.style.display = 'block';
            myRecipe.style.color = '#d54215';
            modalBox.style.display = 'none';
            personalInfo.style.color = 'unset';
            savemodal.style.display = 'none';
            savebtn.style.color = 'unset;'
            editModal.style.display = 'none';
            editbtn.style.color = 'unset';
            changeModal.style.display = 'none';
            changelink.style.color = 'unset';
        }

        function saveBtn() {
            savemodal.style.display = 'block';
            savebtn.style.color = '#d54215;'
            myRecipeInfo.style.display = 'none';
            myRecipe.style.color = 'unset';
            modalBox.style.display = 'none';
            personalInfo.style.color = 'unset';
            editModal.style.display = 'none';
            editbtn.style.color = 'unset';
            changeModal.style.display = 'none';
            changelink.style.color = 'unset';
        }

        function editBtn() {
            editModal.style.display = 'block';
            editbtn.style.color = '#d54215';
            savemodal.style.display = 'none';
            savebtn.style.color = 'unset';
            myRecipeInfo.style.display = 'none';
            myRecipe.style.color = 'unset';
            modalBox.style.display = 'none';
            personalInfo.style.color = 'unset';
            changeModal.style.display = 'none';
            changelink.style.color = 'unset';
        }

        function changeBtn() {
            changeModal.style.display = 'block';
            changelink.style.color = '#d54215';
            editModal.style.display = 'none';
            editbtn.style.color = '#unset';
            savemodal.style.display = 'none';
            savebtn.style.color = 'unset';
            myRecipeInfo.style.display = 'none';
            myRecipe.style.color = 'unset';
            modalBox.style.display = 'none';
            personalInfo.style.color = 'unset';
        }

        personalInfo.onclick = personalInfoModal;
        myRecipe.onclick = myRecipeBtn;
        savebtn.onclick = saveBtn;
        editbtn.onclick = editBtn;
        changelink.onclick = changeBtn;

        // Remove recipe from saved recipes
        document.addEventListener('DOMContentLoaded', function () {
            var buttons = document.getElementsByClassName('save-button');
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].addEventListener('click', function (event) {
                    event.preventDefault(); // prevent default behavior of link
                    var url = this.getAttribute('href'); // get URL from href attribute
                    var recipeId = this.getAttribute('data-recipe-id'); // get recipe ID from data attribute

                    // Add a confirmation dialog box to warn the user before proceeding with the removal
                    if (confirm("Are you sure you want to remove this recipe from saved recipes?")) {
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', url, true);
                        xhr.onreadystatechange = function () {
                            if (this.readyState === 4 && this.status === 200) {
                                var response = this.responseText;
                                // if response is "Recipe removed" remove recipe from list using the li's id
                                if (response === 'Recipe removed') {
                                    document.getElementById('recipe-' + recipeId).remove();
                                }
                            }
                        };
                        xhr.send();
                    }
                });
            }
        });

        // Delete recipe (warn before deleting)
        document.addEventListener('DOMContentLoaded', function () {
            var buttons = document.getElementsByClassName('delete-button');
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].addEventListener('click', function (event) {
                    event.preventDefault(); // prevent default behavior of link
                    var url = this.getAttribute('href'); // get URL from href attribute
                    var recipeId = this.getAttribute('data-recipe-id'); // get recipe ID from data attribute

                    // Add a confirmation dialog box to warn the user before proceeding with the deletion
                    if (confirm("Are you sure you want to delete this recipe?")) {
                        var xhr = new XMLHttpRequest();
                        xhr.open('GET', url, true);
                        xhr.onreadystatechange = function () {
                            if (this.readyState === 4 && this.status === 200) {
                                var response = this.responseText;
                                // if response is "Recipe deleted" remove recipe from list using the li's id
                                if (response === 'Recipe deleted') {
                                    document.getElementById('myrecipe-' + recipeId).remove();
                                }
                            }
                        };
                        xhr.send();
                    }
                });
            }
        });

        // Edit Profile
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('editForm');
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // prevent default behavior of form
                var url = form.getAttribute('action'); // get URL from action attribute
                var formData = new FormData(form); // create FormData object from form
                var xhr = new XMLHttpRequest();
                xhr.open('POST', url, true);
                xhr.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        var response = this.responseText;
                        // if response is "Profile updated" reload page and show alert
                        if (response === 'Profile updated') {
                            alert(response);
                            location.reload();
                        }
                        else {
                            alert(response);
                        }
                    }
                };
                xhr.send(formData);
            });
        });

        // Change Password
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('changePasswordForm');
            form.addEventListener('submit', function (event) {
                event.preventDefault(); // prevent default behavior of form
                var url = form.getAttribute('action'); // get URL from action attribute
                var formData = new FormData(form); // create FormData object from form
                var xhr = new XMLHttpRequest();
                xhr.open('POST', url, true);
                xhr.onreadystatechange = function () {
                    if (this.readyState === 4 && this.status === 200) {
                        var response = this.responseText;
                        // if response is "Password changed" reload page and show alert
                        if (response === 'Password changed') {
                            alert(response);
                            location.reload();
                        }
                        else {
                            alert(response);
                        }
                    }
                };
                xhr.send(formData);
            });
        });

    </script>
</body>

</html>