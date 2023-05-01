<?php

class homeController {

    // home page
    public function index() {
        $title = pageTitle('Home');

        return array(
            'title' => $title,
        );
    }
}

?>