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
    <?php if ($success): ?>
        <p><?php echo $success; ?></p>
    <?php endif; ?>

    <h1>Edit Recipe</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo ($recipe['title']); ?>" required><br>

        <label for="directions">Directions:</label>
        <textarea id="directions" name="directions" required><?php echo ($recipe['directions']); ?></textarea><br>

        <label for="ingredients">Ingredients:</label>
        <textarea id="ingredients" name="ingredients" required><?php echo ($recipe['ingredients']); ?></textarea><br>

        <label for="prep_time">Prep Time:</label>
        <input type="number" id="prep_time" name="prep_time" value="<?php echo ($recipe['prep_time']); ?>" required><br>

        <label for="servings">Servings:</label>
        <input type="number" id="servings" name="servings" value="<?php echo ($recipe['servings']); ?>" required><br>

        <label for="categories">Categories:</label>
        <select id="categories" name="categories[]" multiple required>
            <?php foreach ($all_categories as $category): ?>
                <option value="<?php echo $category['name']; ?>" <?php if (in_array($category['name'], $categories)) echo 'selected'; ?>><?php echo $category['name']; ?></option>
            <?php endforeach; ?>
        </select><br>

        <label for="image">Image:</label>
        <input type="file" id="image" name="image"><br>

        <img src="<?php echo assets('recipe-images/'.$recipe['image']); ?>" alt="Recipe Image" width="200"><br>

        <input type="submit" value="Submit">
    </form>
</body>

</html>
