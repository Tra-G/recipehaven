<?php

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


function deleteRowBySelector($table, $selectorColumn, $selectorValue) {
    $conn = db_connect();
    // Construct the SQL query
    $sql = "DELETE FROM $table WHERE $selectorColumn = " . $conn->real_escape_string($selectorValue);

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // If the query was successful, return the number of rows affected
        $affected_rows = $conn->affected_rows;
    } else {
        // If the query failed, return null
        $affected_rows = null;
    }

    // Close the database connection
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
	return  $_ENV['SITE_NAME'] . " | ".$site_name;
}

// This function redirects the user to a specified URL by sending a Location header
function redirect($url) {
    header('Location: ' . APP_ROOT . '/'.$url);
}

// This function returns the full URL for a given path by concatenating the APP_ROOT constant with the provided URL
function route($url) {
    return APP_ROOT . '/'.$url;
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
        if($user)
            return true;
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

// Form input validator
class FormValidator {
    private $formData;
    private $errors;

    public function __construct($formData) {
        $this->formData = $formData;
        $this->errors = array();
    }

    public function validateName() {
        if (empty($this->formData['name'])) {
            $this->errors['name'] = 'Name is required';
        } else {
            $name = $this->sanitizeInput($this->formData['name']);
            if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                $this->errors['name'] = 'Only letters and white space allowed';
            }
        }

        // Return $this to enable method chaining
        return $this;
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

    public function validateMessage() {
        if (empty($this->formData['message'])) {
            $this->errors['message'] = 'Message is required';
        } else {
            $message = $this->sanitizeInput($this->formData['message']);
            if (strlen($message) < 10) {
                $this->errors['message'] = 'Message must be at least 10 characters';
            }
        }

        // Return $this to enable method chaining
        return $this;
    }

    public function validatePhone() {
        if (empty($this->formData['phone'])) {
            $this->errors['phone'] = 'Phone is required';
        } else {
            $phone = $this->sanitizeInput($this->formData['phone']);
            if (!preg_match("/^[0-9]{10}$/", $phone)) {
                $this->errors['phone'] = 'Invalid phone number format';
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
            // if (strlen($password) < 8) {
            //     $this->errors['password'] = 'Password must be at least 8 characters';
            // }
        }

        // Return $this to enable method chaining
        return $this;
    }

    public function validateConfirmPassword() {
        if (empty($this->formData['confirm_password'])) {
            $this->errors['confirm_password'] = 'Confirm password is required';
        } else {
            $confirmPassword = $this->sanitizeInput($this->formData['confirm_password']);
            $password = $this->sanitizeInput($this->formData['password']);
            if ($confirmPassword !== $password) {
                $this->errors['confirm_password'] = 'Passwords do not match';
            }
        }

        // Return $this to enable method chaining
        return $this;
    }

    public function validateAddress() {
        if (empty($this->formData['address'])) {
            $this->errors['address'] = 'Address is required';
        } else {
            $address = $this->sanitizeInput($this->formData['address']);
            if (strlen($address) < 5) {
                $this->errors['address'] = 'Address must be at least 5 characters';
            }
        }

        // Return $this to enable method chaining
        return $this;
    }

    public function validateCity() {
        if (empty($this->formData['city'])) {
            $this->errors['city'] = 'City is required';
        } else {
            $city = $this->sanitizeInput($this->formData['city']);
            if (!preg_match("/^[a-zA-Z ]*$/", $city)) {
                $this->errors['city'] = 'Only letters and white space allowed';
            }
        }

        // Return $this to enable method chaining
        return $this;
    }

    public function validateState() {
        if (empty($this->formData['state'])) {
            $this->errors['state'] = 'State is required';
        } else {
            $state = $this->sanitizeInput($this->formData['state']);
            if (!preg_match("/^[a-zA-Z ]*$/", $state)) {
                $this->errors['state'] = 'Only letters and white space allowed';
            }
        }

        // Return $this to enable method chaining
        return $this;
    }

    public function validateZip() {
        if (empty($this->formData['zip'])) {
            $this->errors['zip'] = 'Zip is required';
        } else {
            $zip = $this->sanitizeInput($this->formData['zip']);
            if (!preg_match("/^[0-9]{5}$/", $zip)) {
                $this->errors['zip'] = 'Invalid zip code format';
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
            if (strlen($longText) < 20) {
                $this->errors[$fieldName] = ucfirst(str_replace("_", " ", $fieldName)) . ' must be at least 20 characters';
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

    // get recipe by id
    public function getRecipeById($id) {
        $sql = "SELECT * FROM recipes WHERE id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);
        $recipe = $stmt->fetch();
        return $recipe;
    }

    // fetch all recipes
    public function getAllRecipes() {
        $sql = "SELECT * FROM recipes";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get recipe by multiple categories
    public function getRecipeByCategories($categories) {
        $sql = "SELECT * FROM recipes WHERE category IN ($categories)";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get recipe by user id
    public function getRecipeByUserId($userId) {
        $sql = "SELECT * FROM recipes WHERE user_id = ?";
        $params = array($userId);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // create recipe
    public function createRecipe($title, $directions, $ingredients, $prep_time, $servings, $status, $categories, $images, $userId) {
        $sql = "INSERT INTO recipes (title, directions, ingredients, prep_time, servings, status, category, image, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $params = array($title, $directions, $ingredients, $prep_time, $servings, $status, $categories, $images, $userId);
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->rowCount();
    }

    // udpate recipe
    public function updateRecipe($id, $title, $directions, $ingredients, $prep_time, $servings, $status, $categories, $images) {
        $sql = "UPDATE recipes SET title = ?, directions = ?, ingredients = ?, prep_time = ?, servings = ?, status = ?, category = ?, image = ? WHERE id = ?";
        $params = array($title, $directions, $ingredients, $prep_time, $servings, $status, $categories, $images, $id);
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->rowCount();
    }

    // delete recipe
    public function deleteRecipe($id) {
        $sql = "DELETE FROM recipes WHERE id = ?";
        $params = array($id);
        $stmt = $this->executeQuery($sql, $params);
        return $stmt->rowCount();
    }

    // search recipes
    public function searchRecipes($search) {
        $sql = "SELECT * FROM recipes WHERE title LIKE '%$search%' OR ingredients LIKE '%$search%' OR directions LIKE '%$search%'";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get recipes by status
    public function getRecipesByStatus($status) {
        $sql = "SELECT * FROM recipes WHERE status = ?";
        $params = array($status);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get latest recipes
    public function getLatestRecipes() {
        $sql = "SELECT * FROM recipes ORDER BY created_at DESC LIMIT 3";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get most saved recipes (saved_recipes table with foreign key to recipe id)
    public function getMostSavedRecipes() {
        $sql = "SELECT recipes.*, COUNT(saved_recipes.recipe_id) AS total_saves FROM recipes LEFT JOIN saved_recipes ON recipes.id = saved_recipes.recipe_id GROUP BY recipes.id ORDER BY total_saves DESC LIMIT 3";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get random recipes
    public function getRandomRecipes($num) {
        $sql = "SELECT * FROM recipes ORDER BY RAND() LIMIT $num";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get recipe by prep time
    public function getRecipeByPrepTime($prepTime) {
        $sql = "SELECT * FROM recipes WHERE prep_time <= ?";
        $params = array($prepTime);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get recipe by servings
    public function getRecipeByServings($servings) {
        $sql = "SELECT * FROM recipes WHERE servings <= ?";
        $params = array($servings);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get recipe by user id and status
    public function getRecipeByUserIdAndStatus($userId, $status) {
        $sql = "SELECT * FROM recipes WHERE user_id = ? AND status = ?";
        $params = array($userId, $status);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get recipe by ingredients
    public function getRecipeByIngredients($ingredients) {
        $sql = "SELECT * FROM recipes WHERE ingredients LIKE ?";
        $params = array('%' . $ingredients . '%');
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get recipes with highest ratings
    public function getRecipeByHighestRatings() {
        $sql = "SELECT * FROM recipes WHERE id IN (SELECT recipe_id FROM ratings GROUP BY recipe_id ORDER BY AVG(rating) DESC)";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->get_result();
        $recipes = $result->fetch_all(MYSQLI_ASSOC);
        return $recipes;
    }

    // get total recipes count
    public function getTotalRecipesCount() {
        $sql = "SELECT COUNT(*) AS total_recipes FROM recipes";
        $stmt = $this->executeQuery($sql, array());
        $result = $stmt->fetch();
        return $result['total_recipes'];
    }

    // get total recipes count by user id
    public function getTotalRecipesCountByUserId($userId) {
        $sql = "SELECT COUNT(*) AS total_recipes FROM recipes WHERE user_id = ?";
        $params = array($userId);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->fetch();
        return $result['total_recipes'];
    }

    // get total recipes count by status
    public function getTotalRecipesCountByStatus($status) {
        $sql = "SELECT COUNT(*) AS total_recipes FROM recipes WHERE status = ?";
        $params = array($status);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->fetch();
        return $result['total_recipes'];
    }

    // get total recipes count by user id and status
    public function getTotalRecipesCountByUserIdAndStatus($userId, $status) {
        $sql = "SELECT COUNT(*) AS total_recipes FROM recipes WHERE user_id = ? AND status = ?";
        $params = array($userId, $status);
        $stmt = $this->executeQuery($sql, $params);
        $result = $stmt->fetch();
        return $result['total_recipes'];
    }

}

?>