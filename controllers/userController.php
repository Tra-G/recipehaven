<?php

class userController {

    public $user_model;
    public $user;
    public $recipe_model;

    public function __construct() {
        $this->user_model = new User(db_connect());
        $this->user = $this->user_model->getUserById($_SESSION['user_id']);
        $this->recipe_model = new Recipe(db_connect());

        // check if user is properly logged in
        if (!isUserLoggedIn()) {
            redirect("login");
            exit();
        }
    }

    // user profile
    public function profile() {
        $title = pageTitle("Profile");
        $saved_per_page = 2;
        $mine_per_page = 2;

        // check if page is set
        $saved_page = isset($_GET['recipe_page']) && is_numeric($_GET['recipe_page']) ? $_GET['recipe_page'] : 1;

        // check if page is set
        $mine_page = isset($_GET['mine_page']) && is_numeric($_GET['mine_page']) ? $_GET['mine_page'] : 1;

        // get user's saved recipes
        $saved_recipes = $this->recipe_model->getSavedRecipes($this->user['id'], $saved_page, $saved_per_page);

        // get all recipe that user submitted
        $mine = $this->recipe_model->getRecipeByUserId($this->user['id'], $mine_page, $mine_per_page);

        // check if previous page and next page exist and set to null if not
        $saved_prev = $this->recipe_model->getSavedRecipes($this->user['id'], $saved_page - 1, $saved_per_page);
        $saved_next = $this->recipe_model->getSavedRecipes($this->user['id'], $saved_page + 1, $saved_per_page);

        // check if previous page and next page exist and set to null if not
        $mine_prev = $this->recipe_model->getRecipeByUserId($this->user['id'], $mine_page - 1, $mine_per_page);
        $mine_next = $this->recipe_model->getRecipeByUserId($this->user['id'], $mine_page + 1, $mine_per_page);

        return array(
            'title' => $title,
            'user' => $this->user,
            'saved_recipes' => $saved_recipes,
            'saved_prev' => $saved_prev ? $saved_page - 1 : null,
            'saved_next' => $saved_next ? $saved_page + 1 : null,
            'mine' => $mine,
            'mine_prev' => $mine_prev ? $mine_page - 1 : null,
            'mine_next' => $mine_next ? $mine_page + 1 : null,
        );
    }

    // edit profile
    public function editProfile () {

        // check if form is submitted
        if (is_post_set('first_name', 'last_name', 'email')) {

            // get data from form
            $userData = array(
                'email' => sanitize_input($_POST['email']),
                'first_name' => sanitize_input($_POST['first_name']),
                'last_name' => sanitize_input($_POST['last_name']),
            );

            // validate data
            $errors = (new FormValidator($userData))
                ->validateEmail()
                ->validateText('first_name')
                ->validateText('last_name')
                ->getErrors();


            // check if email already exists and is not the user's email
            if ($this->user_model->getUserByEmail($userData['email']) && $userData['email'] != $this->user['email']) {
                $errors[] = "Email already exists.";
            }

            if ($errors) {
                $response = implode(" ", $errors);
            }
            else {

                // update user
                if ($this->user_model->editUser($this->user['id'], $userData['first_name'], $userData['last_name'], $userData['email'])) {
                    $response = "Profile updated";
                }
                else {
                    $response = "Something went wrong.";
                }
            }
        }
        else {
            $response = "Error updating profile.";
        }

        echo $response;
    }

    // change password
    public function changePassword() {

        // check if form is submitted
        if (is_post_set('current_password', 'new_password', 'confirm_password')) {

            // get data from form
            $userData = array(
                'current_password' => sanitize_input($_POST['current_password']),
                'new_password' => sanitize_input($_POST['new_password']),
                'confirm_password' => sanitize_input($_POST['confirm_password']),
            );

            // validate data
            $errors = (new FormValidator($userData))
                ->validateAllPassword()
                ->getErrors();

            if ($errors) {
                $response = implode(" ", $errors);
            }
            else {
                // update password
                if ($this->user_model->changePassword($this->user['id'], $userData['current_password'], $userData['new_password'], $userData['confirm_password'])) {
                    $response = "Password changed";
                }
                else {
                    $response = "Incorrect password.";
                }
            }
        }
        else {
            $response = "Error changing password.";
        }

        echo $response;
    }

    // delete recipe
    public function deleteRecipe($id) {
        $recipe = $this->recipe_model->getRecipeById($id);

        // check if recipe exists
        if (!$recipe) {
            $response = "Recipe does not exist.";
        }
        else {

            // check if user is the owner of the recipe
            if ($recipe['user_id'] != $this->user['id']) {
                $response = "Access denied.";
            }
            else {

                // delete recipe
                if ($this->recipe_model->deleteRecipe($id)) {
                    $response = "Recipe deleted";

                    // delete recipe image if exists
                    if ($recipe['image'] && file_exists(__DIR__."/../assets/recipe-images/" . $recipe['image'])) {
                        unlink(__DIR__."/../assets/recipe-images/" . $recipe['image']);
                    }
                }
                else {
                    $response = "Something went wrong.";
                }
            }
        }

        echo $response;
    }

    // add recipe
    public function addRecipe() {
        $title = pageTitle("Add Recipe");
        $errors = [];
        $categories = $this->recipe_model->getCategories();

        // check if form is submitted
        if (is_post_set('title', 'directions', 'ingredients', 'prep_time', 'servings')) {
            $recipeData = array(
                'title' => sanitize_input($_POST['title']),
                'directions' => sanitize_input($_POST['directions']),
                'ingredients' => sanitize_input($_POST['ingredients']),
                'prep_time' => sanitize_input($_POST['prep_time']),
                'servings' => sanitize_input($_POST['servings']),
                'categories' => isset($_POST['categories']) ? $_POST['categories'] : $errors[] = "Please select at least one category.",
                'image' => $_FILES['image'],
            );

            // validate data
            $errors = (new FormValidator($recipeData))
                ->validateText('title')
                ->validateLongText('directions')
                ->validateLongText('ingredients')
                ->validateNumber('prep_time')
                ->validateNumber('servings')
                ->validateImage('image')
                ->getErrors();

            // check if categories are valid by checking if they exist in the database
            if (!$this->recipe_model->categoryExists(...$recipeData['categories'])) {
                $errors[] = "Invalid categories.";
            }

            if (!$errors) {

                // upload image
                $image = $this->uploadImage($recipeData['image']);

                // check if image was uploaded
                if (!$image) {
                    $errors[] = "Error uploading image.";
                }
                else {

                    // make the categories into a string
                    $stringed_categories = implode(", ", $recipeData['categories']);

                    // add recipe
                    if ($this->recipe_model->addRecipe($this->user['id'], $recipeData['title'], $recipeData['directions'], $recipeData['ingredients'], $recipeData['prep_time'], $recipeData['servings'], 'pending', $stringed_categories, $image)) {
                        // redirect to profile
                        redirect('user/profile');
                        exit();
                    }
                    else {
                        $errors[] = "Error adding recipe.";
                    }
                }
            }
        }

        return array(
            'title' => $title,
            'user' => $this->user,
            'categories' => $categories,
            'errors' => $errors,
        );
    }

    public function editRecipe($id) {
        $title = pageTitle("Edit Recipe");
        $errors = [];
        $recipe = $this->recipe_model->getRecipeById($id);
        $all_categories = $this->recipe_model->getCategories();

        // get recipe categories
        $categories = explode(", ", $recipe['categories']);

        // check if recipe exists
        if (!$recipe) {
            $errors[] = "Recipe does not exist.";
        }
        else {

            // check if user is the owner of the recipe
            if ($recipe['user_id'] != $this->user['id']) {
                $errors[] = "You do not have permission to edit this recipe.";
            }
            else {

                // check if form is submitted
                if (is_post_set('title', 'directions', 'ingredients', 'prep_time', 'servings')) {
                    $recipeData = array(
                        'title' => sanitize_input($_POST['title']),
                        'directions' => sanitize_input($_POST['directions']),
                        'ingredients' => sanitize_input($_POST['ingredients']),
                        'prep_time' => sanitize_input($_POST['prep_time']),
                        'servings' => sanitize_input($_POST['servings']),
                        'categories' => isset($_POST['categories']) ? $_POST['categories'] : $errors[] = "Please select at least one category.",
                        'image' => $_FILES['image'],
                    );

                    // validate data but don't validate image if it's not set
                    $errors = (new FormValidator($recipeData))
                        ->validateText('title')
                        ->validateLongText('directions')
                        ->validateLongText('ingredients')
                        ->validateNumber('prep_time')
                        ->validateNumber('servings')
                        ->getErrors();

                    // check if categories are valid by checking if they exist in the database
                    if (!$this->recipe_model->categoryExists(...$recipeData['categories'])) {
                        $errors[] = "Invalid categories.";
                    }

                    if (!$errors) {

                        // validate image if it's set
                        if (isset($_FILES['image']['name']) && $_FILES['image']['name']) {
                            $errors = (new FormValidator($recipeData))
                                ->validateImage('image')
                                ->getErrors();

                                // upload image
                                $image = $this->uploadImage($recipeData['image']);

                                // unlink old image if it exists
                                if ($recipe['image'] && file_exists(__DIR__.'/../assets/recipe-images/'.$recipe['image'])) {
                                    unlink(__DIR__.'/../assets/recipe-images/'.$recipe['image']);
                                }
                        }
                        else {
                            $image = $recipe['image'];
                        }

                        // make the categories into a string
                        $stringed_categories = implode(", ", $recipeData['categories']);

                        // edit recipe
                        if ($this->recipe_model->updateRecipe($id, $recipeData['title'], $recipeData['directions'], $recipeData['ingredients'], $recipeData['prep_time'], $recipeData['servings'], 'pending', $stringed_categories, $image)) {
                            // redirect to recipe page
                            redirect('recipe/'.$recipe['id']);
                            exit();
                        }
                        else {
                            $errors[] = "Error adding recipe.";
                        }
                    }
                }
            }
        }

        return array(
            'title' => $title,
            'user' => $this->user,
            'categories' => $categories,
            'all_categories' => $all_categories,
            'errors' => $errors,
            'recipe' => $recipe,
        );
    }

    private function uploadImage($file) {
        $target_dir = __DIR__."/../assets/recipe-images/";
        $target_file = $target_dir . basename($file["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // check if image is real
        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // check if file already exists
        if (file_exists($target_file)) {
            $uploadOk = 0;
        }

        // check file size
        if ($file["size"] > 500000) {
            $uploadOk = 0;
        }

        // check file type
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $uploadOk = 0;
        }

        // check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return false;
        } else {
            // rename file to unique name
            $new_file_name = uniqid('recipe_').".".$imageFileType;
            $target_file = $target_dir . $new_file_name;

            // if everything is ok, try to upload file
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                return $new_file_name;
            } else {
                return false;
            }
        }
    }
}

?>