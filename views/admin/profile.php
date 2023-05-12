<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>
</head>

<body>

    <h1>Edit Profile</h1>

    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if ($success): ?>
        <p>Profile updated successfully.</p>
    <?php endif; ?>

    <form method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo $admin['email']; ?>" required><br>

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" value="<?php echo $admin['first_name']; ?>" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" value="<?php echo $admin['last_name']; ?>" required><br>

        <button type="submit">Edit Profile</button>
    </form>

</body>

</html>