<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
</head>

<body>
    <a href="<?php echo route('recipe/new'); ?>">Add New Recipe</a>
    <h1>User Info</h1>
    <p>Name: <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></p>
    <p>Email: <?php echo $user['email']; ?></p>

    <h2>Saved Recipes</h2>
    <?php if ($saved_recipes): ?>
        <ul>
            <?php foreach ($saved_recipes as $recipe): ?>
                <li id="recipe-<?php echo $recipe['id']; ?>">
                    <a href="<?php echo route('recipe/' . $recipe['id']); ?>"><?php echo $recipe['title']; ?></a>
                    [<a class="save-button" data-recipe-id="<?php echo $recipe['id']; ?>" href="<?php echo route('recipe/' . $recipe['id'] . '/unsave'); ?>">Remove</a>]
                </li>
            <?php endforeach; ?>
            <?php if ($saved_prev): ?>
                <a href="<?php echo route('user/profile', ['recipe_page' => $saved_prev]); ?>">Previous</a>
            <?php endif; ?>
            <?php if ($saved_next): ?>
                <a href="<?php echo route('user/profile', ['recipe_page' => $saved_next]); ?>">Next</a>
            <?php endif; ?>
        </ul>
    <?php else: ?>
        <p>No saved recipes</p>
    <?php endif; ?>

    <h2>My Recipes</h2>
    <?php if ($mine): ?>
        <ul>
            <?php foreach ($mine as $recipe): ?>
                <li id="myrecipe-<?php echo $recipe['id']; ?>">
                    <a href="<?php echo route('recipe/' . $recipe['id']); ?>"><?php echo $recipe['title']; ?></a>
                    [Status: <?php echo $recipe['status']; ?>
                    [<a class="delete-button" data-recipe-id="<?php echo $recipe['id']; ?>" href="<?php echo route('recipe/' . $recipe['id'] . '/delete'); ?>">Delete</a>]
                    [<a href="<?php echo route('recipe/' . $recipe['id'] . '/edit'); ?>">Edit</a>]
                </li>
            <?php endforeach; ?>
            <?php if ($mine_prev): ?>
                <a href="<?php echo route('user/profile', ['mine_page' => $mine_prev]); ?>">Previous</a>
            <?php endif; ?>
            <?php if ($mine_next): ?>
                <a href="<?php echo route('user/profile', ['mine_page' => $mine_next]); ?>">Next</a>
            <?php endif; ?>
        </ul>
    <?php else: ?>
        <p>No recipes</p>
    <?php endif; ?>

    <h2>Edit Profile</h2>
    <form id="editForm" action="<?php echo route('user/edit'); ?>" method="post">
        <label for="name">First Name:</label>
        <input type="text" id="name" name="first_name" value="<?php echo $user['first_name']; ?>"><br>

        <label for="name">Last Name:</label>
        <input type="text" id="name" name="last_name" value="<?php echo $user['last_name']; ?>"><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>"><br>

        <input type="submit" value="Save Changes">
    </form>

    <h2>Change Password</h2>
    <form id="changePasswordForm" action="<?php echo route('user/password'); ?>" method="post">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password"><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password"><br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password"><br>

        <input type="submit" value="Change Password">
    </form>
</body>

<script>
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

</html>
