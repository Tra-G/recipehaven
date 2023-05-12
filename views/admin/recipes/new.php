<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
</head>

<body>
    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h1>Add New Recipe</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br>

        <label for="directions">Directions:</label>
        <textarea id="directions" name="directions" required></textarea><br>

        <label for="ingredients">Ingredients:</label>
        <textarea id="ingredients" name="ingredients" required></textarea><br>

        <label for="prep_time">Prep Time:</label>
        <input type="number" id="prep_time" name="prep_time" required><br>

        <label for="servings">Servings:</label>
        <input type="number" id="servings" name="servings" required><br>

        <label for="categories">Categories:</label>
        <select id="categories" name="categories[]" multiple required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image" required><br>

        <input type="submit" value="Submit">
    </form>
</body>

</html>