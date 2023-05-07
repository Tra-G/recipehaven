<?php

class adminController {

    public $admin;
    public $admin_model;

    public function __construct() {
        $this->admin_model = new Admin(db_connect());
        $this->admin = $this->admin_model->getAdminById($_SESSION['user_id']);

        // check if admin is properly logged in
        if (!isAdminLoggedIn()) {
            route("login");
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