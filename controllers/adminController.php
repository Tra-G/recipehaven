<?php

class adminController {

    public $admin;
    public $admin_model;
    public $recipe_model;
    public $post_model;

    public function __construct() {
        $this->admin_model = new Admin(db_connect());
        $this->admin = $this->admin_model->getAdminById($_SESSION['user_id']);
        $this->recipe_model = new Recipe(db_connect());
        $this->post_model = new Blog(db_connect());

        // check if admin is properly logged in
        if (!isAdminLoggedIn()) {
            redirect("login");
            exit();
        }
    }

    public function index() {
        $title = pageTitle('Admin Dashboard');
        $total_users = $this->admin_model->getTotalUsers();
        $total_recipes = $this->admin_model->getTotalRecipes();
        $total_published_recipes = $this->admin_model->getTotalRecipes('published');
        $total_pending_recipes = $this->admin_model->getTotalRecipes('pending');
        $total_posts = $this->admin_model->getTotalPosts();

        return array(
            'title' => $title,
            'total_users' => $total_users,
            'total_recipes' => $total_recipes,
            'total_published_recipes' => $total_published_recipes,
            'total_pending_recipes' => $total_pending_recipes,
            'total_posts' => $total_posts,
        );
    }

    public function allUsers() {
        $title = pageTitle('All Users');
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
        $per_page = 10;

        // get all users
        $users = $this->admin_model->getAllUsers($page, $per_page);

        // check if previous page and next page exist and set to null if not
        $prev = $this->admin_model->getAllUsers($page-1, $per_page);
        $next = $this->admin_model->getAllUsers($page+1, $per_page);

        return array(
            'title' => $title,
            'users' => $users,
            'prev' => $prev ? $page-1 : null,
            'next' => $next ? $page+1 : null,
        );
    }

    public function editUser($id) {
        $title = pageTitle('Edit User');
        $errors = [];
        $user = $this->admin_model->getUserById($id);

        // check if user exists
        if (!$user) {
            redirect("admin/users");
            exit();
        }

        // check if form is submitted
        if (is_post_set('first_name', 'last_name', 'email')) {
            $editData = array(
                'email' => sanitize_input($_POST['email']),
                'first_name' => sanitize_input($_POST['first_name']),
                'last_name' => sanitize_input($_POST['last_name']),
            );

            $errors = (new FormValidator($editData))
                ->validateEmail()
                ->validateText('first_name')
                ->validateText('last_name')
                ->getErrors();

            if (empty($errors)) {
                // update user
                if ($this->admin_model->editUser($id, $editData['first_name'], $editData['last_name'], $editData['email'])) {
                    $success = "User updated successfully.";
                }
            }
        }

        return array(
            'title' => $title,
            'user' => $this->admin_model->getUserById($id),
            'errors' => $errors,
            'success' => isset($success) ? $success : null,
        );
    }

    public function deleteUser($id) {
        // check if user exists and delete user
        if ($this->admin_model->getUserById($id) && $this->admin_model->deleteUser($id)) {
            redirect("admin/users");
            exit();
        }
        else {
            redirect("admin/dashboard");
            exit();
        }
    }

    public function allRecipes() {
        $title = pageTitle('All Recipes');
        $pd_page = isset($_GET['pd_page']) && is_numeric($_GET['pd_page']) ? $_GET['pd_page'] : 1;
        $pb_page = isset($_GET['pb_page']) && is_numeric($_GET['pb_page']) ? $_GET['pb_page'] : 1;
        $pd_per_page = 5;
        $pb_per_page = 5;

        // get all recipes
        $pending_recipes = $this->recipe_model->getRecipesByStatus('pending', $pd_page, $pd_per_page);
        $published_recipes = $this->recipe_model->getRecipesByStatus('published', $pb_page, $pb_per_page);

        // check if previous page and next page exist and set to null if not
        $pd_prev = $this->recipe_model->getRecipesByStatus('pending', $pd_page-1, $pd_per_page);
        $pd_next = $this->recipe_model->getRecipesByStatus('pending', $pd_page+1, $pd_per_page);
        $pb_prev = $this->recipe_model->getRecipesByStatus('published', $pb_page-1, $pb_per_page);
        $pb_next = $this->recipe_model->getRecipesByStatus('published', $pb_page+1, $pb_per_page);

        return array(
            'title' => $title,
            'pending_recipes' => $pending_recipes,
            'published_recipes' => $published_recipes,
            'pd_prev' => $pd_prev ? $pd_page-1 : null,
            'pd_next' => $pd_next ? $pd_page+1 : null,
            'pb_prev' => $pb_prev ? $pb_page-1 : null,
            'pb_next' => $pb_next ? $pb_page+1 : null,
        );
    }

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
                    if ($this->recipe_model->addRecipe($this->admin['id'], $recipeData['title'], $recipeData['directions'], $recipeData['ingredients'], $recipeData['prep_time'], $recipeData['servings'], 'published', $stringed_categories, $image)) {
                        // redirect to recipes page
                        redirect('admin/recipes');
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
            'user' => $this->admin,
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
                        $success = "Recipe updated successfully.";
                    }
                    else {
                        $errors[] = "Error adding recipe.";
                    }
                }
            }
        }

        return array(
            'title' => $title,
            'categories' => $categories,
            'all_categories' => $all_categories,
            'errors' => $errors,
            'success' => isset($success) ? $success : null,
            'recipe' => $this->recipe_model->getRecipeById($id),
        );
    }

    public function deleteRecipe($id) {
        $recipe = $this->recipe_model->getRecipeById($id);

        // check if recipe exists
        if (!$recipe) {
            redirect("admin/recipes");
            exit();
        }

        // delete recipe
        if ($this->recipe_model->deleteRecipe($id)) {
            redirect("admin/recipes");
            exit();
        }
        else {
            redirect("admin/recipes");
            exit();
        }
    }

    public function approveRecipe($id) {
        $recipe = $this->recipe_model->getRecipeById($id);

        // check if recipe exists
        if (!$recipe) {
            redirect("admin/recipes");
            exit();
        }

        // approve recipe
        if ($this->recipe_model->approveRecipe($id)) {
            redirect("admin/recipes");
            exit();
        }
        else {
            redirect("admin/recipes");
            exit();
        }
    }

    public function editProfile() {
        $title = pageTitle("Edit Profile");
        $errors = [];

        // check if form is submitted
        if (is_post_set('first_name', 'last_name', 'email')) {
            $userData = array(
                'first_name' => sanitize_input($_POST['first_name']),
                'last_name' => sanitize_input($_POST['last_name']),
                'email' => sanitize_input($_POST['email']),
            );

            // validate data
            $errors = (new FormValidator($userData))
                ->validateText('first_name')
                ->validateText('last_name')
                ->validateEmail()
                ->getErrors();

            // check if email already exists and if it's not the current user's email
            if ($this->admin_model->getUserByEmail($userData['email']) && $userData['email'] != $this->admin['email']) {
                $errors[] = "Email already exists.";
            }

            if (!$errors) {

                // update user
                if ($this->admin_model->editUser($this->admin['id'], $userData['first_name'], $userData['last_name'], $userData['email'])) {
                    $success = "Profile updated successfully.";
                }
                else {
                    $errors[] = "Error updating profile.";
                }
            }
        }

        return array(
            'title' => $title,
            'admin' => $this->admin,
            'errors' => $errors,
            'success' => isset($success) ? $success : null,
        );
    }

    public function changePassword() {
        $title = pageTitle("Change Password");
        $errors = [];

        // check if form is submitted
        if (is_post_set('current_password', 'new_password', 'confirm_password')) {
            $passwordData = array(
                'current_password' => sanitize_input($_POST['current_password']),
                'new_password' => sanitize_input($_POST['new_password']),
                'confirm_password' => sanitize_input($_POST['confirm_password']),
            );

            // validate data
            $errors = (new FormValidator($passwordData))
                ->validateAllPassword()
                ->getErrors();

            // check if current password is correct
            if (!password_verify($passwordData['current_password'], $this->admin['password'])) {
                $errors[] = "Current password is incorrect.";
            }

            if (!$errors) {

                // update password
                if ($this->admin_model->changePassword($this->admin['id'], $passwordData['current_password'], $passwordData['new_password'], $passwordData['confirm_password'])) {
                    $success = "Password changed successfully.";
                }
                else {
                    $errors[] = "Error changing password.";
                }
            }
        }

        return array(
            'title' => $title,
            'admin' => $this->admin,
            'errors' => $errors,
            'success' => isset($success) ? $success : null,
        );
    }

    public function allPosts() {
        $title = pageTitle("All Posts");
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
        $per_page = 3;

        // get all posts
        $posts = $this->post_model->getAllPosts($page, $per_page);

        // check if previous page and next page exist and set to null if not
        $prev = $this->post_model->getAllPosts($page-1, $per_page);
        $next = $this->post_model->getAllPosts($page+1, $per_page);

        return array(
            'title' => $title,
            'posts' => $posts,
            'prev' => $prev ? $page-1 : null,
            'next' => $next ? $page+1 : null,
        );
    }

    public function editPost($id) {
        $title = pageTitle("Edit Post");
        $post = $this->post_model->getPostById($id);
        $errors = [];

        // check if post exists
        if (!$this->post_model->getPostById($id)) {
            redirect("admin/blog");
            exit();
        }

        // check if form is submitted
        if (is_post_set('title', 'content')) {
            $postData = array(
                'title' => sanitize_input($_POST['title']),
                'content' => sanitize_input($_POST['content']),
                'image' => $_FILES['image'],
            );

            // validate data but don't validate image if it's not set
            $errors = (new FormValidator($postData))
                ->validateText('title')
                ->validateLongText('content')
                ->getErrors();

            if (!$errors) {

                // validate image if it's set
                if (isset($_FILES['image']['name']) && $_FILES['image']['name']) {
                    $errors = (new FormValidator($postData))
                        ->validateImage('image')
                        ->getErrors();

                    if (!$errors) {

                        // upload image
                        $image = $this->uploadImage($postData['image'], __DIR__.'/../assets/blog-images/', 'post_');

                        // unlink old image if it exists
                        if ($post['thumbnail_path'] && file_exists(__DIR__.'/../assets/blog-images/'.$post['thumbnail_path'])) {
                            unlink(__DIR__.'/../assets/blog-images/'.$post['thumbnail_path']);
                        }
                    }
                    else {
                        $image = $post['thumbnail_path'];
                    }
                }
                else {
                    $image = $post['thumbnail_path'];
                }

                // edit post
                if ($this->post_model->editPost($id, $image, $postData['title'], $postData['content']) && !$errors) {
                    $success = "Post updated successfully.";
                }
                else {
                    $errors[] = "Error updating post.";
                }
            }
        }

        return array(
            'title' => $title,
            'errors' => $errors,
            'success' => isset($success) ? $success : null,
            'post' => $this->post_model->getPostById($id),
        );
    }

    public function addPost() {
        $title = pageTitle("Add New Post");
        $errors = [];

        // check if form is submitted
        if (is_post_set('title', 'content')) {
            $postData = array(
                'title' => sanitize_input($_POST['title']),
                'content' => sanitize_input($_POST['content']),
                'image' => $_FILES['image'],
            );

            // validate data
            $errors = (new FormValidator($postData))
                ->validateText('title')
                ->validateLongText('content')
                ->validateImage('image')
                ->getErrors();

            if (!$errors) {

                // upload image
                $image = $this->uploadImage($postData['image'], __DIR__.'/../assets/blog-images/', 'post_');

                // add post
                if ($this->post_model->addPost($this->admin['id'], $image, $postData['title'], $postData['content'])) {
                    redirect("admin/blog");
                    exit();
                }
                else {
                    $errors[] = "Error adding post.";
                }
            }
        }

        return array(
            'title' => $title,
            'errors' => $errors,
        );
    }

    public function deletePost($id) {
        $post = $this->post_model->getPostById($id);

        // check if post exists
        if (!$this->post_model->getPostById($id)) {
            redirect("admin/blog");
            exit();
        }

        // delete post
        if ($this->post_model->deletePost($id)) {

            // unlink image if it exists
            if ($post['thumbnail_path'] && file_exists(__DIR__.'/../assets/blog-images/'.$post['thumbnail_path'])) {
                unlink(__DIR__.'/../assets/blog-images/'.$post['thumbnail_path']);
            }

            redirect("admin/blog");
            exit();
        }
        else {
            redirect ("admin/post/".$id."/edit");
            exit();
        }
    }

    public function allCategories() {
        $title = pageTitle("All Categories");
        $categories = $this->recipe_model->getCategories();

        return array(
            'title' => $title,
            'categories' => $categories,
        );
    }

    public function editCategory($id) {
        $title = pageTitle("Edit Category");
        $category = $this->recipe_model->getCategoryById($id);
        $errors = [];

        // check if category exists
        if (!$this->recipe_model->getCategoryById($id)) {
            redirect("admin/categories");
            exit();
        }

        // check if form is submitted
        if (is_post_set('name')) {
            $categoryData = array(
                'name' => sanitize_input($_POST['name']),
            );

            // validate data
            $errors = (new FormValidator($categoryData))
                ->validateText('name')
                ->getErrors();

            if (!$errors) {

                // edit category
                if ($this->recipe_model->editCategory($id, $categoryData['name'])) {
                    redirect("admin/categories");
                    exit();
                }
                else {
                    $errors[] = "Error updating category.";
                }
            }
        }

        return array(
            'title' => $title,
            'errors' => $errors,
            'category' => $this->recipe_model->getCategoryById($id),
        );
    }

    public function addCategory() {
        $title = pageTitle("Add New Category");
        $errors = [];

        // check if form is submitted
        if (is_post_set('name')) {
            $categoryData = array(
                'name' => sanitize_input($_POST['name']),
            );

            // validate data
            $errors = (new FormValidator($categoryData))
                ->validateText('name')
                ->getErrors();

            if (!$errors) {

                // add category
                if ($this->recipe_model->addCategory($categoryData['name'])) {
                    redirect("admin/categories");
                    exit();
                }
                else {
                    $errors[] = "Error adding category.";
                }
            }
        }

        return array(
            'title' => $title,
            'errors' => $errors,
        );
    }

    public function deleteCategory($id) {
        $category = $this->recipe_model->getCategoryById($id);

        // check if category exists
        if (!$this->recipe_model->getCategoryById($id)) {
            redirect("admin/categories");
            exit();
        }

        // delete category
        if ($this->recipe_model->deleteCategory($id)) {
            redirect("admin/categories");
            exit();
        }
        else {
            redirect ("admin/category/".$id."/edit");
            exit();
        }
    }

    private function uploadImage($file, $target_dir = null, $prefix = 'recipe_') {
        if (!$target_dir) {
            $target_dir = __DIR__."/../assets/recipe-images/";
        }
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
            $new_file_name = uniqid($prefix).".".$imageFileType;
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