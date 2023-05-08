<?php

// Load PHPMailer classes
require_once(__DIR__.'/phpmailer/src/PHPMailer.php');
require_once(__DIR__.'/phpmailer/src/SMTP.php');
require_once(__DIR__.'/phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * CRUD OPERATIONS
 *
 * db_connect: Connect to database
 * insertRow: Insert data into table
 * getRowBySelector: Get rows with a selector
 * updateRowBySelector: Update rows with a selector
 * deleteRowBySelector: Delete rows based on selector
 * getRows: Gets rows and total rows based on optional selector
 * sumAmounts: Get total sum of a column
*/


// Database connection
function db_connect() {
    try {
        $conn = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);
    }
    catch (Exception $e) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function insertRow($table, $data) {
    $conn = db_connect();
    // Construct the SQL query
    $keys = array_keys($data);
    $values = array_values($data);
    $placeholders = array_fill(0, count($values), '?');

    $sql = "INSERT INTO $table (" . implode(',', $keys) . ") VALUES(" . implode(',', $placeholders) . ")";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $types = str_repeat('s', count($values));
    $stmt->bind_param($types, ...$values);

    // Execute the statement
    if ($stmt->execute()) {
        // If the query was successful, return the ID of the newly created row
        $new_id = $stmt->insert_id;
    } else {
        // If the query failed, return null
        $new_id = null;
    }

    // Close the database connection
    mysqli_close($conn);

    return $new_id;

    /* Usage:
    $data = array(
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'phone' => '555-555-5555'
    );

    $id = insertRow('users', $data);

    if (is_numeric($id)) {
        echo "New row created with ID " . $id;
    } else {
        echo $id;
    }
    */
}


function getRowBySelector($table, $selectorColumn, $selectorValue) {
    $conn = db_connect();
    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT * FROM $table WHERE $selectorColumn = ?");

    // Bind the selector value to the query
    $stmt->bind_param("s", $selectorValue);

    // Execute the query
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If there is at least one row, return the data as an associative array
        $data = $result->fetch_assoc();
    } else {
        // If there are no rows, return null
        $data = null;
    }

    // Close the database connection
    mysqli_close($conn);

    return $data;

    /* Usage:
    $table = 'users'; // Change this to the name of the table you want to select from
    $selectorColumn = 'id'; // Change this to the name of the selector column
    $selectorValue = 1; // Change this to the selector value you want to use

    $row = getRowBySelector($table, $selectorColumn, $selectorValue);

    if ($row) {
        // Display the data from the selected row
        foreach ($row as $key => $value) {
            echo $key . ": " . $value . "<br>";
        }
    } else {
        echo "Row not found.";
    }
     */
}


function updateRowBySelector($table, $data, $selectorColumn, $selectorValue) {
    $conn = db_connect();
    // Construct the SQL query
    $set = array();
    foreach ($data as $key => $value) {
        $set[] = "$key = ?";
    }
    $sql = "UPDATE $table SET " . implode(',', $set) . " WHERE $selectorColumn = ?";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind the parameters
    $types = str_repeat('s', count($data) + 1);
    $values = array_values($data);
    $values[] = $selectorValue;
    $stmt->bind_param($types, ...$values);

    // Execute the statement
    if ($stmt->execute()) {
        // If the query was successful, return the number of rows affected
        $affected_rows = $stmt->affected_rows;
    } else {
        $affected_rows = null;
    }

    // Close the database connection
    mysqli_close($conn);

    return $affected_rows;

    /* Usage:
    $table = 'users'; // Change this to the name of the table you want to update
    $data = array(
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'phone' => '555-555-5555'
    ); // Change this to the data you want to update
    $selectorColumn = 'id'; // Change this to the name of the selector column
    $selectorValue = 1; // Change this to the selector value you want to use

    $rowsAffected = updateRowBySelector($table, $data, $selectorColumn, $selectorValue);

    if (is_numeric($rowsAffected)) {
        echo "Rows affected: " . $rowsAffected;
    } else {
        echo $rowsAffected;
    }
    */
}


function deleteRowBySelector($table, $selectorColumn = null, $selectorValue = null) {
    $conn = db_connect();

    // Use prepared statements to prevent SQL injection
    if ($selectorColumn && $selectorValue) {
        $stmt = $conn->prepare("DELETE FROM $table WHERE $selectorColumn = ?");
        $stmt->bind_param("s", $selectorValue);
    } else {
        $stmt = $conn->prepare("DELETE FROM $table");
    }

    // Execute the query
    $stmt->execute();
    $affected_rows = $stmt->affected_rows;

    // Close the statement and database connection
    $stmt->close();
    mysqli_close($conn);

    return $affected_rows;

    /* Usage:
    $table = 'users'; // Change this to the name of the table you want to delete from
    $selectorColumn = 'id'; // Change this to the name of the selector column
    $selectorValue = 1; // Change this to the selector value you want to use

    $rowsAffected = deleteRowBySelector($table, $selectorColumn, $selectorValue);

    if (is_numeric($rowsAffected)) {
        echo "Rows affected: " . $rowsAffected;
    } else {
        echo $rowsAffected;
    }
    */
}


function getRows($table, $selectorColumn = null, $selectorValue = null, $orderByColumn = null, $orderByDirection = 'ASC', $limit = null) {
    $conn = db_connect();
    $sql = "SELECT * FROM $table";

    if ($selectorColumn && $selectorValue) {
        // If a selector is provided, append the WHERE clause to the SQL query
        $sql .= " WHERE $selectorColumn = ?";
    }

    if ($orderByColumn) {
        // If an order by column is provided, append the ORDER BY clause to the SQL query
        $sql .= " ORDER BY $orderByColumn $orderByDirection";
    }

    if ($limit) {
        // If a limit is provided, append the LIMIT clause to the SQL query
        $sql .= " LIMIT $limit";
    }

    // Prepare the SQL query
    $stmt = $conn->prepare($sql);

    if ($selectorColumn && $selectorValue) {
        // If a selector is provided, bind the selector value to the prepared statement
        $stmt->bind_param("s", $selectorValue);
    }

    // Execute the query
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Get the total number of matched rows
    $count = $result->num_rows;

    // Get all the matched rows
    $rows = $result->fetch_all(MYSQLI_ASSOC);

    // Close the database connection
    mysqli_close($conn);

    return array('count' => $count, 'rows' => $rows);

    /* Usage:
    $table = 'users'; // Change this to the name of the table you want to select from

    // With selector, order by and limit
    $selectorColumn = 'id'; // Change this to the name of the selector column
    $selectorValue = 1; // Change this to the selector value you want to use
    $orderByColumn = 'name'; // Change this to the name of the column you want to order by
    $orderByDirection = 'DESC'; // Change this to the order direction you want to use
    $limit = 10; // Change this to the number of rows you want to limit to
    $result = getRows($table, $selectorColumn, $selectorValue, $orderByColumn, $orderByDirection, $limit);

    // Without selector, order by or limit
    $result = getRows($table);

    $count = $result['count'];
    $rows = $result['rows'];

    echo "Total rows: " . $count . "<br>";
    echo "Matched rows: " . json_encode($rows);
    */
}

// Get total of a column in a table
function sumAmounts($table, $amountColumn) {
    $conn = db_connect();
    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT COALESCE(SUM(`$amountColumn`), 0) FROM `$table`");

    // Execute the query
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();

    // Get the total amount
    $total = $result->fetch_row()[0];

    // Close the database connection
    mysqli_close($conn);

    return $total;
}


// environment variables unpacking
$env_file = __DIR__ . '/../.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comment lines
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($key, $value) = explode('=', $line, 2);
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

// error reporting
if (getenv('APP_ENV') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
else {
    error_reporting(0);
}

// app root
define('APP_ROOT', getenv('APP_ROOT'));

// set timezone
date_default_timezone_set($_ENV['TIME_ZONE']);

// dynamic page title
function pageTitle($site_name){
	return  $site_name . " | " . $_ENV['SITE_NAME'];
}

// This function redirects the user to a specified URL by sending a Location header
function redirect($url) {
    header('Location: ' . APP_ROOT . '/'.$url);
}

// This function returns the full URL for a given path by concatenating the APP_ROOT constant with the provided URL
function route($url, $params = []) {
    $query = '';
    foreach ($params as $name => $value) {
        $query .= '&' . urlencode($name) . '=' . urlencode($value);
    }
    $query = ltrim($query, '&');
    if (!empty($query)) {
        $url .= '?' . $query;
    }
    return APP_ROOT . '/' . $url;
}


// This function returns the full URL to the assets folder by concatenating the APP_ROOT constant with the provided path
function assets($file) {
    return APP_ROOT . '/assets/'.$file;
}

// Checks if user is logged in
function isUserLoggedIn() {
    if(isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $user = getRowBySelector('users', 'id', $userId);
        // check if role is user
        if ($user['role'] == 'user') {
            return true;
        }
    }
    return false;
}

// Checks if admin is logged in
function isAdminLoggedIn() {
    if(isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $user = getRowBySelector('users', 'id', $userId);
        // check if role is admin
        if ($user['role'] == 'admin') {
            return true;
        }
    }
    return false;
}

// Checks if form is submitted
function is_post_set(...$names) {
    foreach ($names as $name) {
        if (!isset($_POST[$name])) {
            return false;
        }
    }
    return true;
}

// Generate token
function generate_token($length=32) {
    return bin2hex(random_bytes($length));
}

// email sending function with option for smtp without phpmailer and normal mail
function send_email($to, $subject, $message, $headers = null) {
    if (getenv('SMTP_ENABLED') == 'true') {
        // SMTP enabled
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = 0; // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = $_ENV['SMTP_HOST']; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = $_ENV['SMTP_USERNAME']; // SMTP username
            $mail->Password = $_ENV['SMTP_PASSWORD']; // SMTP password
            $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION']; // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $_ENV['SMTP_PORT']; // TCP port to connect to

            //Recipients
            $mail->setFrom($_ENV['SMTP_FROM_EMAIL'], $_ENV['SMTP_FROM_NAME']);
            $mail->addAddress($to); // Add a recipient

            //Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    } else {
        // SMTP disabled
        if (mail($to, $subject, $message, $headers))
            return true;
        else
            return false;
    }
}

// sanitize input
function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    $input = strip_tags($input);
    return $input;
}

// Form input validator
class FormValidator {
    private $formData;
    private $errors;

    public function __construct($formData) {
        $this->formData = $formData;
        $this->errors = array();
    }

    public function validateEmail() {
        if (empty($this->formData['email'])) {
            $this->errors['email'] = 'Email is required';
        } else {
            $email = $this->sanitizeInput($this->formData['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Invalid email format';
            }
        }

        // Return $this to enable method chaining
        return $this;
    }

    public function validatePassword() {
        if (empty($this->formData['password'])) {
            $this->errors['password'] = 'Password is required';
        } else {
            $password = $this->sanitizeInput($this->formData['password']);
            if (strlen($password) < 3) {
                $this->errors['password'] = 'Password must be at least 8 characters';
            }
        }

        // Return $this to enable method chaining
        return $this;
    }

    public function validateAllPassword() {
        if (empty($this->formData['current_password'])) {
            $this->errors['current_password'] = 'Current password is required';
        }

        if (empty($this->formData['new_password'])) {
            $this->errors['new_password'] = 'New password is required';
        } else {
            $newPassword = $this->sanitizeInput($this->formData['new_password']);
            if (strlen($newPassword) < 3) {
                $this->errors['new_password'] = 'New password must be at least 3 characters';
            }
        }

        if (empty($this->formData['confirm_password'])) {
            $this->errors['confirm_password'] = 'Confirm password is required';
        } else {
            $confirmPassword = $this->sanitizeInput($this->formData['confirm_password']);
            if ($newPassword !== $confirmPassword) {
                $this->errors['confirm_password'] = 'Passwords do not match';
            }
        }

        // Return $this to enable method chaining
        return $this;
    }

    public function validateText($fieldName) {
        if (empty($this->formData[$fieldName])) {
            $this->errors[$fieldName] = ucfirst(str_replace("_", " ", $fieldName)) . ' is required';
        } else {
            $text = $this->sanitizeInput($this->formData[$fieldName]);
            if (!preg_match("/^[a-zA-Z0-9\s]+$/", $text)) {
                $this->errors[$fieldName] = ucfirst(str_replace("_", " ", $fieldName)) . ' can only contain alphanumeric characters and spaces';
            }
        }

        return $this;
    }

    public function validateLongText($fieldName) {
        if (empty($this->formData[$fieldName])) {
            $this->errors[$fieldName] = ucfirst(str_replace("_", " ", $fieldName)) . ' is required';
        } else {
            $longText = $this->sanitizeInput($this->formData[$fieldName]);
            if (strlen($longText) < 10) {
                $this->errors[$fieldName] = ucfirst(str_replace("_", " ", $fieldName)) . ' must be at least 10 characters';
            }
        }

        return $this;
    }

    public function validateImage($fieldName) {
        if (empty($_FILES[$fieldName]['name'])) {
            $this->errors[$fieldName] = 'Please upload an image';
        } else {
            $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
            $fileType = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
            if (!in_array($fileType, $allowedTypes)) {
                $this->errors[$fieldName] = 'Only JPG, JPEG, PNG, and GIF images are allowed';
            } else if ($_FILES[$fieldName]['size'] > 5000000) {
                $this->errors[$fieldName] = 'File size should not exceed 5MB';
            }
        }

        return $this;
    }

    public function validateNumber($fieldName) {
        if (empty($this->formData[$fieldName])) {
            $this->errors[$fieldName] = ucfirst(str_replace("_", " ", $fieldName)) . ' is required';
        } else {
            $number = $this->sanitizeInput($this->formData[$fieldName]);
            if (!is_numeric($number)) {
                $this->errors[$fieldName] = ucfirst(str_replace("_", " ", $fieldName)) . ' must be a number';
            }
        }

        return $this;
    }

    public function getErrors() {
        return $this->errors;
    }

    private function sanitizeInput($input) {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    /* Example usage:

    $formData = array(
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'message' => $_POST['message'],
        'phone' => $_POST['phone']
    );

    $errors = (new FormValidator($formData))
                ->validateName()
                ->validateEmail()
                ->validateMessage()
                ->validatePhone()
                ->getErrors();

    if (!empty($errors)) {
        // There are errors, display them to the user
        foreach ($errors as $field => $error) {
            echo "<p>Error for $field: $error</p>";
        }
    } else {
        // Form is valid, do something with the data
        // echo $formData['name'];
    }

    */
}

// Recipe class
class Recipe {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // execute query
    private function executeQuery($sql, $params) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // fetch all recipes and add page and perpage
    public function getAllRecipes($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM recipes LIMIT ?, ?";
        $params = array($offset, $perPage);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get recipe by id
    public function getRecipeById($id) {
        $sql = "SELECT recipes.*, users.first_name, users.last_name
                FROM recipes
                LEFT JOIN users ON recipes.user_id = users.id
                WHERE recipes.id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $recipe = $result->fetch_assoc();
        return $recipe;
    }

    // get recipes by status
    public function getRecipesByStatus($status, $page = 1, $perPage = 10, $orderBy = 'created_at', $direction = 'DESC') {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM recipes WHERE status = ? ORDER BY $orderBy $direction LIMIT ?, ?";
        $params = array($status, $offset, $perPage);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // search recipes
    public function searchRecipes($search, $page = null, $perpage = null) {
        $sql = "SELECT * FROM recipes WHERE title LIKE '%$search%' OR ingredients LIKE '%$search%' OR categories LIKE '%$search%'";

        if ($page && $perpage) {
            $offset = ($page - 1) * $perpage;
            $sql .= " LIMIT ?, ?";
            $stmt = $this->executeQuery($sql, array($offset, $perpage));
        } else {
            $stmt = $this->executeQuery($sql, array());
        }

        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // count total search results
    public function searchRecipesCount($search) {
        $sql = "SELECT COUNT(*) AS count FROM recipes WHERE title LIKE '%$search%' OR ingredients LIKE '%$search%' OR categories LIKE '%$search%'";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // increment views and return new view count
    public function incrementViews($id) {
        $sql = "UPDATE recipes SET views = views + 1 WHERE id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);
        $sql = "SELECT views FROM recipes WHERE id = ?";
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['views'];
    }

    // get recipe ratings from the ratings table and get the average rating
    public function getRecipeRatings($id) {
        $sql = "SELECT * FROM ratings WHERE recipe_id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $ratings = $result->fetch_all(MYSQLI_ASSOC);

        $total = 0;
        foreach ($ratings as $rating) {
            $total += $rating['rating'];
        }

        if (count($ratings) > 0) {
            $average = $total / count($ratings);
            $average = round($average * 2) / 2; // round to nearest 0.5
        } else {
            $average = 0;
        }

        // total number of ratings
        $total_ratings = count($ratings);

        return array(
            'ratings' => $ratings,
            'total' => $total_ratings,
            'average' => $average
        );
    }

    // get comments for a recipe
    public function getRecipeComments($id, $page = 1, $perpage = 10, $sort = 'created_at', $direction = 'DESC') {
        $offset = ($page - 1) * $perpage;
        $sql = "SELECT comments.*, users.first_name, users.last_name
                FROM comments
                LEFT JOIN users ON comments.user_id = users.id
                WHERE recipe_id = ?
                ORDER BY $sort $direction
                LIMIT ?, ?";
        $params = array($id, $offset, $perpage);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $comments = $result->fetch_all(MYSQLI_ASSOC);
        return $comments;
    }

    // count total comments for a recipe
    public function getRecipeCommentsCount($id) {
        $sql = "SELECT COUNT(*) AS count FROM comments WHERE recipe_id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // save recipe (table: saved_recipes)
    public function saveRecipe($user_id, $recipe_id) {
        $sql = "INSERT INTO saved_recipes (user_id, recipe_id) VALUES (?, ?)";
        $params = array($user_id, $recipe_id);
        $stmt = $this->executeQuery($sql, $params);

        // return true if successful
        return $stmt ? true : false;
    }

    // unsave recipe (table: saved_recipes)
    public function unsaveRecipe($user_id, $recipe_id) {
        $sql = "DELETE FROM saved_recipes WHERE user_id = ? AND recipe_id = ?";
        $params = array($user_id, $recipe_id);
        $stmt = $this->executeQuery($sql, $params);

        // return true if successful
        return $stmt ? true : false;
    }

    // check if recipe is saved by user
    public function isSaved($user_id, $recipe_id) {
        $sql = "SELECT * FROM saved_recipes WHERE user_id = ? AND recipe_id = ?";
        $params = array($user_id, $recipe_id);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? true : false;
    }

    // total number of saves for a recipe
    public function getRecipeSaves($recipe_id) {
        $sql = "SELECT COUNT(*) AS count FROM saved_recipes WHERE recipe_id = ?";
        $params = array($recipe_id);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // recipe rating (table: ratings) - save or update
    public function rateRecipe($user_id, $recipe_id, $rating) {
        $sql = "SELECT * FROM ratings WHERE user_id = ? AND recipe_id = ?";
        $params = array($user_id, $recipe_id);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // update rating
            $sql = "UPDATE ratings SET rating = ? WHERE user_id = ? AND recipe_id = ?";
            $params = array($rating, $user_id, $recipe_id);
            $stmt = $this->executeQuery($sql, $params);
        } else {
            // insert rating
            $sql = "INSERT INTO ratings (user_id, recipe_id, rating) VALUES (?, ?, ?)";
            $params = array($user_id, $recipe_id, $rating);
            $stmt = $this->executeQuery($sql, $params);
        }

        // return true if successful
        return $stmt ? true : false;
    }

    // save comment (table: comments)
    public function saveComment($user_id, $recipe_id, $comment) {
        $sql = "INSERT INTO comments (user_id, recipe_id, comment) VALUES (?, ?, ?)";
        $params = array($user_id, $recipe_id, $comment);
        $stmt = $this->executeQuery($sql, $params);

        // return true if successful
        return $stmt ? true : false;
    }

    // get all saved recipes for a user
    public function getSavedRecipes($user_id, $page = 1, $perPage = 10, $orderby = 'saved_recipes.created_at', $direction = 'DESC') {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT recipes.*, users.first_name, users.last_name
                FROM saved_recipes
                LEFT JOIN recipes ON saved_recipes.recipe_id = recipes.id
                LEFT JOIN users ON recipes.user_id = users.id
                WHERE saved_recipes.user_id = ?
                ORDER BY $orderby $direction
                LIMIT ?, ?";
        $params = array($user_id, $offset, $perPage);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get recipe by user id
    public function getRecipeByUserId($user_id, $page = 1, $perPage = 10, $orderby = 'recipes.created_at', $direction = 'DESC') {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT recipes.*, users.first_name, users.last_name
                FROM recipes
                LEFT JOIN users ON recipes.user_id = users.id
                WHERE recipes.user_id = ?
                ORDER BY $orderby $direction
                LIMIT ?, ?";
        $params = array($user_id, $offset, $perPage);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // delete recipe by id (delete all from saved_recipes, ratings, comments)
    public function deleteRecipe($recipe_id) {
        $sql = "DELETE FROM saved_recipes WHERE recipe_id = ?";
        $params = array($recipe_id);
        $stmt = $this->executeQuery($sql, $params);

        $sql = "DELETE FROM ratings WHERE recipe_id = ?";
        $params = array($recipe_id);
        $stmt = $this->executeQuery($sql, $params);

        $sql = "DELETE FROM comments WHERE recipe_id = ?";
        $params = array($recipe_id);
        $stmt = $this->executeQuery($sql, $params);

        $sql = "DELETE FROM recipes WHERE id = ?";
        $params = array($recipe_id);
        $stmt = $this->executeQuery($sql, $params);

        // return true if successful
        return $stmt ? true : false;
    }

    // add new recipe
    public function addRecipe($user_id, $title, $directions, $ingredients, $prep_time, $servings, $status, $categories, $image) {
        $sql = "INSERT INTO recipes (user_id, title, directions, ingredients, prep_time, servings, status, categories, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array($user_id, $title, $directions, $ingredients, $prep_time, $servings, $status, $categories, $image);
        $stmt = $this->executeQuery($sql, $params);

        // return true if successful
        return $stmt ? true : false;
    }

    // update recipe
    public function updateRecipe($recipe_id, $title, $directions, $ingredients, $prep_time, $servings, $status, $categories, $image) {
        $sql = "UPDATE recipes SET title = ?, directions = ?, ingredients = ?, prep_time = ?, servings = ?, status = ?, categories = ?, image = ? WHERE id = ?";
        $params = array($title, $directions, $ingredients, $prep_time, $servings, $status, $categories, $image, $recipe_id);
        $stmt = $this->executeQuery($sql, $params);

        // return true if successful
        return $stmt ? true : false;
    }

    // get categories and return as array
    public function getCategories() {
        $sql = "SELECT * FROM categories";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        return $categories;
    }

    // check if categories (variadic) exists (category column is name)
    public function categoryExists(...$categories) {
        $sql = "SELECT * FROM categories WHERE name IN (";
        $params = array();
        foreach ($categories as $category) {
            $sql .= "?, ";
            $params[] = $category;
        }
        $sql = rtrim($sql, ', ');
        $sql .= ")";
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        return $categories;
    }

    public function approveRecipe($recipe_id) {
        $sql = "UPDATE recipes SET status = 'published' WHERE id = ?";
        $params = array($recipe_id);
        $stmt = $this->executeQuery($sql, $params);

        // return true if successful
        return $stmt ? true : false;
    }
}

// Blog class
class Blog {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // execute query
    private function executeQuery($sql, $params) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // get all posts (with optional orderby, page and perpage)
    public function getAllPosts($page = 1, $perPage = 10, $orderBy = 'created_at', $orderDir = 'desc') {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM blog_posts ORDER BY $orderBy $orderDir LIMIT ?, ?";
        $params = array($offset, $perPage);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $posts = $result->fetch_all(MYSQLI_ASSOC);
        return $posts;
    }

    // get blog by id and add user's first and last name
    public function getPostById($id) {
        $sql = "SELECT blog_posts.*, users.first_name, users.last_name
                FROM blog_posts
                LEFT JOIN users ON blog_posts.user_id = users.id
                WHERE blog_posts.id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $post = $result->fetch_assoc();
        return $post;
    }
}

class User {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // execute query
    private function executeQuery($sql, $params) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // get user by id
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user;
    }

    // edit user
    public function editUser($id, $first_name, $last_name, $email) {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?";
        $params = array($first_name, $last_name, $email, $id);
        $stmt = $this->executeQuery($sql, $params);

        // return true if successful
        return $stmt ? true : false;
    }

    // change password
    public function changePassword($id, $current_password, $new_password, $confirm_password) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // check if current password is correct
        if (password_verify($current_password, $user['password'])) {
            // check if new password and confirm password match
            if ($new_password === $confirm_password) {
                $sql = "UPDATE users SET password = ? WHERE id = ?";
                $params = array(password_hash($new_password, PASSWORD_DEFAULT), $id);
                $stmt = $this->executeQuery($sql, $params);

                // return true if successful
                return $stmt ? true : false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

class Admin {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // execute query
    private function executeQuery($sql, $params) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // get admin by id
    public function getAdminById($id) {
        $sql = "SELECT * FROM users WHERE id = ? AND role = 'admin'";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
        return $admin;
    }

    // get total number of users
    public function getTotalUsers() {
        $sql = "SELECT COUNT(*) FROM users WHERE role = 'user'";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->get_result();
        $total_users = $result->fetch_assoc();
        return $total_users['COUNT(*)'];
    }

    // get total number of recipes (optional: status)
    public function getTotalRecipes($status = null) {
        $sql = "SELECT COUNT(*) FROM recipes";
        $params = array();
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $total_recipes = $result->fetch_assoc();
        return $total_recipes['COUNT(*)'];
    }

    // get total number of posts
    public function getTotalPosts() {
        $sql = "SELECT COUNT(*) FROM blog_posts";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->get_result();
        $total_posts = $result->fetch_assoc();
        return $total_posts['COUNT(*)'];
    }

    // get all users (with optional orderby, page and perpage)
    public function getAllUsers($page = 1, $perPage = 10, $orderBy = 'created_at', $orderDir = 'DESC') {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM users WHERE role = 'user' ORDER BY $orderBy $orderDir LIMIT ?, ?";
        $params = array($offset, $perPage);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $users = $result->fetch_all(MYSQLI_ASSOC);
        return $users;
    }

    // get user by id
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ? AND role = 'user'";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        return $user;
    }

    // edit user
    public function editUser($id, $first_name, $last_name, $email) {
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?";
        $params = array($first_name, $last_name, $email, $id);
        $stmt = $this->executeQuery($sql, $params);

        // return true if successful
        return $stmt ? true : false;
    }

    // delete user (delete from comments, ratings, saved_recipes, recipes and finally users)
    public function deleteUser($id) {
        $sql = "SET FOREIGN_KEY_CHECKS=0";
        $this->executeQuery($sql, array());

        $sql = "DELETE FROM comments WHERE user_id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);

        $sql = "DELETE FROM ratings WHERE user_id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);

        $sql = "DELETE FROM saved_recipes WHERE user_id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);

        $sql = "DELETE FROM recipes WHERE user_id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);

        $sql = "DELETE FROM users WHERE id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);

        $sql = "SET FOREIGN_KEY_CHECKS=1";
        $this->executeQuery($sql, array());

        // return true if successful
        return $stmt ? true : false;
    }

}

?>