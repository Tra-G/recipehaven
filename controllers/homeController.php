<?php

// load the functions
// require_once(__DIR__ . "/../models/functions.php");


class homeController {

    // home page
    public function index() {
        $title = 'Home';

        return array(
            'title' => $title,
        );
    }
}

?>