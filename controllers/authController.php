<?php

// load the functions
require_once(__DIR__ . "/../models/functions.php");

class authController {

    // login page
    public function login() {
        $title = pageTitle("Login");
        $errors = [];

        // Go to dashboard if user is logged in
        if (isUserLoggedIn()){
            route("user/dashboard");
            exit();
        }

        // check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            if (empty($email) || empty($password)) {
                $errors[] = "All fields are required.";
            }

            $user = getRowBySelector('users', 'email', $email);
            if ($user) {
                $hashed_password = $user['password'];
                if(password_verify($password, $hashed_password)){

                    // Unset all session variables
                    session_unset();
                    session_regenerate_id(true);

                    $_SESSION["logged"] = true;
                    $_SESSION["user_id"] = $user['id'];

                    // go to proper dashboard
                    if ($user["role"] == "admin")
                        route("admin/dashboard");
                    else if ($user["role"] == "user")
                        route("user/dashboard");
                    else
                        $errors[] = "No user found";
                }
                else{
                    $errors[] = "Incorrect password";
                }
            }
            else {
                $errors[] = "Email was not found";
            }
        }

        return array('title' => $title, 'errors' => $errors);
    }

    // register page
    public function register() {
        $title = pageTitle("Sign Up");
        $errors = [];

        // Go to dashboard if user is logged in
        if (isUserLoggedIn()){
            route("user/dashboard");
            exit();
        }

        // check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            $city = trim($_POST['city']);
            $zip = trim($_POST['zip']);

            // $states = array(
            //     "Abia", "Adamawa", "Akwa Ibom", "Anambra", "Bauchi", "Bayelsa", "Benue", "Borno",
            //     "Cross River", "Delta", "Ebonyi", "Edo", "Ekiti", "Enugu", "Federal Capital Territory",
            //     "Gombe", "Imo", "Jigawa", "Kaduna", "Kano", "Katsina", "Kebbi", "Kogi", "Kwara",
            //     "Lagos", "Nasarawa", "Niger", "Ogun", "Ondo", "Osun", "Oyo", "Plateau", "Rivers",
            //     "Sokoto", "Taraba", "Yobe", "Zamfara"
            // );
            // if (!in_array($state, $states)) {
            //     $errors[] = "Invalid state";
            // }

            if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($address) || empty($city) || empty($zip)) {
                $errors[] = "All fields are required.";
            }

            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }
            if (getRowBySelector('users', 'email', $email)) {
                $errors[] = "Email is already taken.";
            }

            // Validate phone number
            if (getRowBySelector('users', 'phone', $phone)) {
                $errors[] = "Phone number is already taken.";
            }
            if (!ctype_digit($phone)) {
                $errors[] = "Phone number is not valid";
            }

            // Insert if no error
            if (count($errors) == 0) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $data_array = array(
                    'role' => 'user',
                    'name' => $name,
                    'email' => $email,
                    'password' => $hashed_password,
                    'phone' => $phone,
                    'address' => $address,
                    'city' => $city,
                    'zip' => $zip,
                );

                $id = insertRow('users', $data_array);
                if ($id) {
                    // Unset all session variables
                    session_unset();
                    session_regenerate_id(true);

                    // Set session variables
                    $_SESSION['user_id'] = $id;
                    $_SESSION['logged'] = true;

                    route("user/dashboard");
                }
            }
        }

        return array('title' => $title, 'errors' => $errors);
    }

    // logout
    public function logout() {
        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to the login page
        route("login");
    }
}

?>