<?php

class homeController {

    private $recipes;

    public function __construct() {
        // load recipes class and pass in db connection
        $this->recipes = new Recipe(db_connect());
    }

    // home page
    public function index() {
        $title = pageTitle('Home');

        return array(
            'title' => $title,
        );
    }

    // all recipes page
    public function allRecipe() {
        $title = pageTitle('All Recipes');
        $all_recipes = $this->recipes->getRecipesByStatus('published');

        return array(
            'title' => $title,
            'all_recipes' => $all_recipes,
        );
    }
}

?>