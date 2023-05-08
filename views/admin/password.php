<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
</head>

<body>

    <h1>Change Password</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if ($success): ?>
        <p><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" id="current_password" required><br>

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required><br>

        <button type="submit">Change Password</button>
    </form>

</body>

</html>