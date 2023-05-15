<?php

class homeController {

    private $recipes;
    private $blog;

    public function __construct() {
        // load recipes class and pass in db connection
        $this->recipes = new Recipe(db_connect());
        $this->blog = new Blog(db_connect());
    }

    // home page
    public function index() {
        $title = pageTitle('Home');
        $most_viewed = $this->recipes->getMostViewedRecipes(20);
        $most_recent = $this->recipes->getMostRecentRecipes(20);

        return array(
            'title' => $title,
            'most_viewed' => $most_viewed,
            'most_recent' => $most_recent,
        );
    }

    // all recipes page
    public function allRecipes() {
        $title = pageTitle('All Recipes');
        $per_page = 4;

        // check if page is set and page is numeric
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

        // get all recipes
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
        $logged = isUserLoggedIn() ? true : false;
        $per_page = 10;

        // check if page is set and page is numeric
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

        // get recipe by id
        $recipe = $this->recipes->getRecipeById($id);

        // if no recipe found, redirect to recipes page
        if (!$recipe) {
            redirect('recipes');
            exit();
        }

        // check if recipe has already been viewed in this session
        if (!isset($_SESSION['viewed_recipes'])) {
            $_SESSION['viewed_recipes'] = array();
        }
        if (!in_array($id, $_SESSION['viewed_recipes'])) {
            // increment views
            $views = $this->recipes->incrementViews($id);
            // add recipe id to viewed recipes in session
            $_SESSION['viewed_recipes'][] = $id;
        } else {
            // recipe has already been viewed in this session, don't increment views
            $views = $recipe['views'];
        }

        // get recipe ratings
        $ratings = $this->recipes->getRecipeRatings($id);

        // get recipe comments
        $comments = $this->recipes->getRecipeComments($id, $page, $per_page);

        // total number of comments for recipe
        $total_comments = $this->recipes->getRecipeCommentsCount($id);

        // check if previous page and next page exist and set to null if not
        $prev = $this->recipes->getRecipeComments($id, $page-1, $per_page);
        $next = $this->recipes->getRecipeComments($id, $page+1, $per_page);

        // check if recipe has already been saved by user
        $saved = $logged ? $this->recipes->isSaved($_SESSION['user_id'], $id) : false;

        // total number of saves for recipe
        $total_saves = $this->recipes->getRecipeSaves($id);

        return array(
            'title' => pageTitle($recipe['title']),
            'logged' => $logged,
            'recipe' => $recipe,
            'views' => $views,
            'ratings' => $ratings,
            'comments' => $comments,
            'total_comments' => $total_comments,
            'saved' => $saved,
            'total_saves' => $total_saves,
            'prev' => $prev ? $page-1 : null,
            'next' => $next ? $page+1 : null,
        );
    }

    // save recipe
    public function saveRecipe($id) {
        $logged = isUserLoggedIn() ? true : false;
        $response = 'Error saving recipe';

        // if user is logged in, save recipe
        if ($logged) {

            // get recipe by id
            $recipe = $this->recipes->getRecipeById($id);

            // if no recipe found, redirect to recipes page
            if (!$recipe) {
                $response = 'Recipe not found';
            }
            else {

                // check if recipe has already been saved by user
                $saved = $this->recipes->isSaved($_SESSION['user_id'], $id);

                // save recipe
                if ($saved) {
                    $response = 'Recipe already saved';
                }
                else {
                    $save = $this->recipes->saveRecipe($_SESSION['user_id'], $id);
                    $response = $save ? 'Recipe saved' : 'Error saving recipe';
                }
            }
        }

        echo $response;
    }

    // unsave recipe
    public function unsaveRecipe($id) {
        $logged = isUserLoggedIn() ? true : false;
        $response = 'Error unsaving recipe';

        // if user is logged in, unsave recipe
        if ($logged) {

            // get recipe by id
            $recipe = $this->recipes->getRecipeById($id);

            // if no recipe found, redirect to recipes page
            if (!$recipe) {
                $response = 'Recipe not found';
            }
            else {

                // check if recipe has already been saved by user
                $saved = $this->recipes->isSaved($_SESSION['user_id'], $id);

                // unsave recipe
                if (!$saved) {
                    $response = 'Recipe is not in saved recipes';
                }
                else {
                    $unsave = $this->recipes->unsaveRecipe($_SESSION['user_id'], $id);
                    $response = $unsave ? 'Recipe removed' : 'Error unsaving recipe';
                }
            }
        }

        echo $response;
    }

    // rate recipe
    public function rateRecipe($id, $rating) {
        $logged = isUserLoggedIn() ? true : false;
        $response = 'Error rating recipe';

        // if user is logged in, rate recipe
        if ($logged) {

            // get recipe by id
            $recipe = $this->recipes->getRecipeById($id);

            // if no recipe found, redirect to recipes page
            if (!$recipe) {
                $response = 'Recipe not found';
            }
            else {
                $rate = $this->recipes->rateRecipe($_SESSION['user_id'], $id, $rating);
                $response = $rate ? 'Recipe rated' : 'Error rating recipe';
            }
        }

        echo $response;
    }

    // comment recipe
    public function commentRecipe($id) {
        $logged = isUserLoggedIn() ? true : false;
        $response = 'Error commenting recipe';

        // if user is logged in, comment recipe
        if ($logged) {

            // get recipe by id
            $recipe = $this->recipes->getRecipeById($id);

            // if no recipe found, redirect to recipes page
            if (!$recipe) {
                $response = 'Recipe not found';
            }
            else {

                // get comment
                $comment = isset($_POST['comment']) ? $_POST['comment'] : null;

                // check if comment is set
                if (!$comment) {
                    $response = 'Comment is required';
                }
                else {
                    $comment = trim($comment);

                    // check if comment is empty
                    if (empty($comment)) {
                        $response = 'Comment is required';
                    }
                    else {
                        $comment = strip_tags($comment);

                        // check if comment is too long
                        if (strlen($comment) > 200) {
                            $response = 'Comment is too long';
                        }
                        else {
                            $comment = htmlspecialchars($comment);

                            // comment recipe
                            $comment = $this->recipes->saveComment($_SESSION['user_id'], $id, $comment);
                            $response = $comment ? 'Comment added' : 'Error commenting recipe';
                        }
                    }
                }
            }
        }

        echo $response;
    }

    // search page
    public function search() {
        $title = pageTitle('Search');
        $param = isset($_GET['param']) ? $_GET['param'] : null;
        $per_page = 4;
        $search_results = $prev = $next = $total_results = null;

        // check if page is set and page is numeric
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

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

    // about page
    public function about() {
        $title = pageTitle('About');

        return array(
            'title' => $title,
        );
    }

    // contact page
    public function contact() {
        $title = pageTitle('Contact Us');

        return array(
            'title' => $title,
        );
    }

    // blog page
    public function blog() {
        $title = pageTitle('Blog');
        $per_page = 3;

        // check if page is set and page is numeric
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

        // get all posts
        $all_posts = $this->blog->getAllPosts($page, $per_page);

        // check if previous page and next page exist and set to null if not
        $prev = $this->blog->getAllPosts($page-1, $per_page);
        $next = $this->blog->getAllPosts($page+1, $per_page);

        return array(
            'title' => $title,
            'all_posts' => $all_posts,
            'prev' => $prev ? $page-1 : null,
            'next' => $next ? $page+1 : null,
        );
    }

    // single blog post page
    public function post($id) {
        // get post by id
        $post = $this->blog->getPostById($id);

        // if no post found, redirect to blog page
        if (!$post) {
            redirect('blog');
            exit();
        }

        $title = pageTitle($post['title']);

        return array(
            'title' => $title,
            'post' => $post,
        );
    }
}

?>