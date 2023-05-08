<?php

class authController
{

    // login
    public function login()
    {
        $title = pageTitle("Login");
        $errors = [];

        // Go to user profile if user is logged in
        if (isUserLoggedIn()) {
            redirect("user/profile");
            exit();
        }

        // check if form is submitted
        if (is_post_set('email', 'password')) {

            // get data from form
            $loginData = array(
                'email' => sanitize_input($_POST['email']),
                'password' => sanitize_input($_POST['password']),
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
                    if (password_verify($loginData['password'], $hashed_password)) {

                        // Unset all session variables
                        session_unset();
                        session_regenerate_id(true);

                        $_SESSION["user_id"] = $user['id'];

                        // go to proper dashboard
                        if ($user["role"] == "admin")
                            redirect("admin/dashboard");
                        else if ($user["role"] == "user")
                            redirect("user/profile");
                        else
                            $errors[] = "Incorrect details";
                    } else {
                        $errors[] = "Incorrect details";
                    }
                } else {
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
    public function register()
    {
        $title = pageTitle("Sign Up");
        $errors = [];

        // Go to profile if user is logged in
        if (isUserLoggedIn()) {
            redirect("user/profile");
            exit();
        }

        // check if form is submitted
        if (is_post_set('email', 'first_name', 'last_name', 'password')) {

            // get data from form
            $registerData = array(
                'email' => sanitize_input($_POST['email']),
                'first_name' => sanitize_input($_POST['first_name']),
                'last_name' => sanitize_input($_POST['last_name']),
                'password' => sanitize_input($_POST['password']),
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

                    // go to profile
                    redirect("user/profile");
                }
            }
        }

        return array(
            'title' => $title,
            'errors' => $errors
        );
    }

    // logout
    public function logout()
    {
        // Unset all of the session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to the login page
        redirect("login");
    }

    // forgot password
    public function forgotPassword()
    {
        $title = pageTitle("Forgot Password");
        $errors = [];

        // Go to dashboard if user is logged in
        if (isUserLoggedIn()) {
            redirect("user/dashboard");
            exit();
        }

        // check if form is submitted
        if (is_post_set('email')) {

            // get data from form
            $forgotData = array(
                'email' => sanitize_input($_POST['email']),
            );

            // validate data
            $errors = (new FormValidator($forgotData))
                ->validateEmail()
                ->getErrors();

            // check if email exists
            if (!getRowBySelector('users', 'email', $forgotData['email'])) {
                $errors['email'] = "Email not found.";
            }

            if (empty($errors)) {
                // check if user has pending token
                $token = getRowBySelector('password_resets', 'email', $forgotData['email']);

                if ($token) {
                    // check if there is a token that has not expired
                    if (strtotime($token['expires_at']) > strtotime(date('Y-m-d H:i:s'))) {
                        $result = "A password reset link has already been sent to your email.";
                    }
                } else {
                    // generate token
                    $user = getRowBySelector('users', 'email', $forgotData['email']);
                    $token = generate_token(16);
                    $data_array = array(
                        'user_id' => $user['id'],
                        'email' => $forgotData['email'],
                        'token' => $token,
                        'expires_at' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
                    );

                    // insert token
                    if (insertRow('password_resets', $data_array)) {
                        // send email
                        $subject = "Password Reset";
                        $message = "Click the link below to reset your password. The link expires in 30 minutes<br><br>";
                        $message .= "<a href='" . redirect('reset/' . $token . '') . "'>Reset Password</a>";
                        $message .= "<br><br> If you did not request a password reset, please ignore this email.";
                        $headers = "From: " . getenv('SITE_NAME') . " <" . getenv('SMTP_FROM_EMAIL') . "> \r\n";
                        $headers .= "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                        // send the email using smtp or mail
                        if (send_email($forgotData['email'], $subject, $message, $headers)) {
                            $result = "A password reset link has been sent to your email.";
                        } else {
                            $result = "Email not sent. Please try again.";
                            // delete token
                            deleteRowBySelector('password_resets', 'email', $forgotData['email']);
                        }
                    } else {
                        $result = "Something went wrong. Please try again.";
                        // delete token
                        deleteRowBySelector('password_resets', 'email', $forgotData['email']);
                    }
                }
            }
        } else {
            // return to login
            redirect("login");
            exit();
        }

        return array(
            'title' => $title,
            'result' => $result ?? $errors[0],
        );
    }

    // reset password form
    public function changePassword($token)
    {
        $title = pageTitle("Reset Password");

        // check if token is valid
        $token = trim($token);
        $token_row = getRowBySelector('password_resets', 'token', $token);
        if (!$token_row) {
            redirect("login");
            exit();
        }
        // check if token has expired
        if (strtotime($token_row['expires_at']) < time()) {
            redirect("login");
            exit();
        }

        return array(
            'title' => $title,
            'token' => $token,
        );
    }

    // change password from reset link
    public function changePasswordApi($token)
    {
        $title = pageTitle("Change Password");
        $errors = [];

        // Go to profile if user is logged in
        if (isUserLoggedIn()) {
            redirect("user/profile");
            exit();
        }

        // check if token is valid and has not expired
        $token = trim($token);
        $token_row = getRowBySelector('password_resets', 'token', $token);
        if (!$token_row) {
            $errors[] = "Invalid token.";
        } else if (strtotime($token_row['expires_at']) < time()) {
            $errors[] = "Password reset link has expired.";
        }

        // check if form is submitted
        if (is_post_set('password', 'confirm_password')) {
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            if (empty($password) || empty($confirm_password)) {
                $errors[] = "All fields are required.";
            }
            if ($password != $confirm_password) {
                $errors[] = "Passwords do not match.";
            }

            if (empty($errors)) {
                // update password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $data_array = array(
                    'password' => $hashed_password
                );

                if (updateRowBySelector('users', $data_array, 'id', $token_row['user_id'])) {
                    // delete all tokens for this user
                    deleteRowBySelector('password_resets', 'user_id', $token_row['user_id']);

                    // set result message
                    $result = "Password changed successfully. You can now login.";
                } else {
                    $errors[] = "Something went wrong. Please try again.";
                }
            }
        } else {
            // return to login
            redirect("login");
            exit();
        }

        return array(
            'title' => $title,
            'result' => $result ?? $errors[0],
        );
    }
}

?>