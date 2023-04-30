<?php

/**
 * CRUD OPERATIONS
 *
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
            if (strlen($password) < 8) {
                $this->errors['password'] = 'Password must be at least 8 characters';
            }
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
            $this->errors[$fieldName] = ucfirst($fieldName) . ' is required';
        } else {
            $text = $this->sanitizeInput($this->formData[$fieldName]);
            if (!preg_match("/^[a-zA-Z0-9\s]+$/", $text)) {
                $this->errors[$fieldName] = ucfirst($fieldName) . ' can only contain alphanumeric characters and spaces';
            }
        }

        return $this;
    }

    public function validateLongText($fieldName) {
        if (empty($this->formData[$fieldName])) {
            $this->errors[$fieldName] = ucfirst($fieldName) . ' is required';
        } else {
            $longText = $this->sanitizeInput($this->formData[$fieldName]);
            if (strlen($longText) < 20) {
                $this->errors[$fieldName] = ucfirst($fieldName) . ' must be at least 20 characters';
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
            $this->errors[$fieldName] = ucfirst($fieldName) . ' is required';
        } else {
            $number = $this->sanitizeInput($this->formData[$fieldName]);
            if (!is_numeric($number)) {
                $this->errors[$fieldName] = ucfirst($fieldName) . ' must be a number';
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

?>