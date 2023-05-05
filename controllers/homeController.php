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
    public function allRecipes() {
        $title = pageTitle('All Recipes');
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $per_page = 4;
        $all_recipes = $this->recipes->getRecipesByStatus('published', $page, $per_page);

        // if no recipes found, redirect to recipes page
        if (!$all_recipes) {
            redirect('recipes');
        }

        // check if previous page and next page exist and set to null if not
        $prev = $this->recipes->getRecipesByStatus('published', $page-1, $per_page);
        $next = $this->recipes->getRecipesByStatus('published', $page+1, $per_page);

        return array(
            'title' => $title,
            'all_recipes' => $all_recipes,
            'prev' => $prev ? $page-1 : null,
            'next' => $next ? $page+1 : null,
        );
    }

    // single recipe page
    public function singleRecipe($id) {
        // get recipe by id
        $recipe = $this->recipes->getRecipeById($id);

        // if no recipe found, redirect to recipes page
        if (!$recipe) {
            redirect('recipes');
            exit();
        }

        $title = pageTitle($recipe['title']);

        return array(
            'title' => $title,
            'recipe' => $recipe,
        );
    }

    // search page
    public function search() {
        $title = pageTitle('Search');
        $param = isset($_GET['param']) ? $_GET['param'] : null;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $per_page = 2;
        $search_results = $prev = $next = $total_results = null;

        // check if search param is set
        if ($param) {
            $search_results = $this->recipes->searchRecipes($param, $page, $per_page);

            // check if previous page and next page exist and set to null if not
            $prev = $this->recipes->searchRecipes($param, $page-1, $per_page);
            $next = $this->recipes->searchRecipes($param, $page+1, $per_page);

            // get total number of search results
            $total_results = $this->recipes->searchRecipesCount($param);
        }

        return array(
            'title' => $title,
            'param' => $param,
            'search_results' => $search_results,
            'prev' => $prev ? $page-1 : null,
            'next' => $next ? $page+1 : null,
            'total_results' => $total_results,
        );
    }
}

?>