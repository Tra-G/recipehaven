<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width">
    <title>
        <?php echo $title; ?>
    </title>
</head>

<body>
    <h1>Edit User</h1>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li>
                        <?php echo $error; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="success">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required><br>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" required><br>

        <input type="submit" value="Submit">
    </form>
</body>

</html>