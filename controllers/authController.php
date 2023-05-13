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
    public function forgotPassword() {
        $errors = [];

        // exit if user is logged in
        if (isUserLoggedIn()) {
            $errors[] = "You are already logged in.";
        }

        // check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);

            if (empty($email)) {
                $errors[] = "Email is required.";
            }

            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }

            // check if email exists
            if (!getRowBySelector('users', 'email', $email)) {
                $errors[] = "Email not found.";
            }

            if (empty($errors)) {
                // check if user has pending token
                $token = getRowBySelector('password_resets', 'email', $email);

                if ($token) {
                    // check if there is a token that has not expired
                    if (strtotime($token['expires_at']) > strtotime(date('Y-m-d H:i:s'))) {
                        echo "A password reset link has already been sent to your email.";
                    } else {
                        echo "The previous password reset link has expired. Please request a new one.";
                    }
                } else {
                    // generate token
                    $user = getRowBySelector('users', 'email', $email);
                    $token = generate_token(16);
                    $data_array = array(
                        'user_id' => $user['id'],
                        'email' => $email,
                        'token' => $token,
                        'expires_at' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
                    );

                    // insert token
                    if (insertRow('password_resets', $data_array)) {
                        // send email
                        $subject = "Password Reset";
                        $message = "Click the link below to reset your password. The link expires in 30 minutes<br><br>";
                        $message .= "<a href='".route('reset/'. $token .'')."'>Reset Password</a>";
                        $message .= "<br><br> If you did not request a password reset, please ignore this email.";
                        $headers = "From: ".getenv('SITE_NAME')." <".getenv('SMTP_FROM_EMAIL')."> \r\n";
                        $headers .= "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                        // send the email using smtp or mail
                        if (send_email($email, $subject, $message, $headers)) {
                            echo "A password reset link has been sent to your email.";
                        } else {
                            $errors[] = "Email not sent. Please try again.";
                            // delete token
                            deleteRowBySelector('password_resets', 'email', $email);
                        }
                    } else {
                        $errors[] = "Something went wrong. Please try again.";
                        // delete token
                        deleteRowBySelector('password_resets', 'email', $email);
                    }
                }
            } else {
                echo $errors[0];
            }
        } else {
            $errors[] = "Please enter your email address.";
            echo $errors[0];
        }
    }

    // reset password form
    public function changePassword($token)
    {
        $title = "Reset Password";

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
        $errors = [];

        // exit if user is logged in
        if (isUserLoggedIn()) {
            $errors[] = "You are already logged in.";
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
                    $result = "Password changed successfully. You will be redirected to the login page.";
                } else {
                    $errors[] = "Something went wrong. Please try again.";
                }
            }
        }
        else {
            $errors[] = "All fields are required.";
        }

        if (isset($result)) {
            echo $result;
        } else {
            echo $errors[0];
        }
    }
}

?>