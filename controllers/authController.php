<?php

class authController {

    // login
    public function login() {
        $title = pageTitle("Login");
        $errors = [];

        // Go to dashboard if user is logged in
        if (isUserLoggedIn()){
            redirect("user/dashboard");
            exit();
        }

        // check if form is submitted
        if (is_post_set('email', 'password')) {

            // get data from form
            $loginData = array(
                'email' => $_POST['email'],
                'password' => $_POST['password'],
            );

            // validate data
            $errors = (new FormValidator($loginData))
                ->validateEmail()
                ->validatePassword()
                ->getErrors();

            // if no errors, check if user exists
            if (empty($errors)) {
                $user = getRowBySelector('users', 'email', $loginData['email']);

                // check if user exists
                if ($user) {
                    $hashed_password = $user['password'];

                    // check if password is correct
                    if(password_verify($loginData['password'], $hashed_password)){

                        // Unset all session variables
                        session_unset();
                        session_regenerate_id(true);

                        $_SESSION["user_id"] = $user['id'];

                        // go to proper dashboard
                        if ($user["role"] == "admin")
                            redirect("admin/dashboard");
                        else if ($user["role"] == "user")
                            redirect("user/dashboard");
                        else
                            $errors[] = "Incorrect details";
                    }
                    else{
                        $errors[] = "Incorrect details";
                    }
                }
                else {
                    $errors[] = "Incorrect details";
                }
            }
        }

        return array(
            'title' => $title,
            'errors' => $errors
        );
    }

    // register page
    public function register() {
        $title = pageTitle("Sign Up");
        $errors = [];

        // Go to dashboard if user is logged in
        if (isUserLoggedIn()){
            redirect("user/dashboard");
            exit();
        }

        // check if form is submitted
        if (is_post_set('email', 'password', 'first_name', 'last_name')) {

            // get data from form
            $registerData = array(
                'email' => $_POST['email'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'password' => $_POST['password'],
            );

            // validate data
            $errors = (new FormValidator($registerData))
                ->validateEmail()
                ->validateText('first_name')
                ->validateText('last_name')
                ->validatePassword()
                ->getErrors();

            // check if email already exists
            if (getRowBySelector('users', 'email', $registerData['email'])) {
                $errors['email'] = "Email already exists";
            }

            // if no errors, insert data into database
            if (empty($errors)) {
                $hashed_password = password_hash($registerData['password'], PASSWORD_DEFAULT);

                // prepare data for database
                $db_data = $registerData;
                $db_data['role'] = 'user';
                $db_data['password'] = $hashed_password;

                $id = insertRow('users', $db_data);
                if ($id) {
                    // Unset all session variables
                    session_unset();
                    session_regenerate_id(true);

                    // Set session variables
                    $_SESSION['user_id'] = $id;

                    redirect("user/dashboard");
                }
            }
        }

        return array(
            'title' => $title,
            'errors' => $errors
        );
    }

    // logout
    public function logout() {
        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to the login page
        redirect("login");
    }
}

?>