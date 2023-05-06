<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title; ?>
    </title>
</head>

<body>
    <h1>
        <?php echo $recipe['title']; ?>
    </h1>

    <?php if ($logged): ?>
        <?php if ($saved): ?>
            <a id="save-button" href="<?php echo route('recipe/' . $recipe['id'] . '/unsave'); ?>">Remove</a>
        <?php else: ?>
            <a id="save-button" href="<?php echo route('recipe/' . $recipe['id'] . '/save'); ?>">Save</a>
        <?php endif; ?>
    <?php endif; ?>

    <p>
        <b>Author:</b>
        <?php echo $recipe['first_name'] . ' ' . $recipe['last_name']; ?>
    </p>
    <p>
        <b>Directions:</b>
        <?php echo $recipe['directions']; ?>
    </p>
    <p>
        <b>Ingredients:</b>
        <?php echo $recipe['ingredients']; ?>
    </p>
    <p>
        <b>Categories:</b>
        <?php echo $recipe['categories']; ?>
    </p>
    <p>
        <b>Created:</b>
        <?php echo $recipe['created_at']; ?>
    </p>
    <p>
        <b>Views:</b>
        <?php echo $views; ?>
    </p>

    <?php if ($logged): ?>
        <p>
        <div id="rating">
            <b>Rate this recipe:</b>
            <a href="<?php echo route('recipe/' . $recipe['id'] . '/rate/1'); ?>">★</a>
            <a href="<?php echo route('recipe/' . $recipe['id'] . '/rate/2'); ?>">★</a>
            <a href="<?php echo route('recipe/' . $recipe['id'] . '/rate/3'); ?>">★</a>
            <a href="<?php echo route('recipe/' . $recipe['id'] . '/rate/4'); ?>">★</a>
            <a href="<?php echo route('recipe/' . $recipe['id'] . '/rate/5'); ?>">★</a>
        </div>
        <p id="message"></p>
        </p>
    <?php endif; ?>

    <p>
        <b>Ratings (Total Count:
            <?php echo $ratings['total']; ?>)
        </b><br>
        <?php if ($ratings['average'] > 0): ?>
            <?php echo $ratings['average']; ?>/5
        <?php else: ?>
            No ratings yet
        <?php endif; ?>
    </p>
    <p>
        <b>Comments:</b><br>
        <?php if ($comments): ?>
            <?php foreach ($comments as $comment): ?>
            <p>
                <b>
                    <?php echo $comment['first_name'] . ' ' . $comment['last_name']; ?> [
                    <?php echo $comment['created_at']; ?>]
                </b><br>
                <?php echo $comment['comment']; ?>
            </p>
        <?php endforeach; ?>
    <?php else: ?>
        No comments yet
    <?php endif; ?>
    <!-- Pagination -->
    <?php if ($prev): ?>
        <a href="<?php echo route('recipe/' . $recipe['id'], ['page' => $prev]); ?>">Previous</a>
    <?php endif; ?>
    <?php if ($next): ?>
        <a href="<?php echo route('recipe/' . $recipe['id'], ['page' => $next]); ?>">Next</a>
    <?php endif; ?>
    </p>
</body>

<script>
    // Save/Remove Recipe
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('save-button').addEventListener('click', function (event) {
            event.preventDefault(); // prevent default behavior of link
            var url = this.getAttribute('href'); // get URL from href attribute
            var xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var response = this.responseText;
                    // if response is "Recipe saved" change link to "Remove"
                    if (response === 'Recipe saved') {
                        document.getElementById('save-button').setAttribute('href', url.replace('/save', '/unsave'));
                        document.getElementById('save-button').innerHTML = 'Remove';
                    }
                    // if response is "Recipe removed" change link to "Save"
                    else if (response === 'Recipe removed') {
                        document.getElementById('save-button').setAttribute('href', url.replace('/unsave', '/save'));
                        document.getElementById('save-button').innerHTML = 'Save';
                    }
                    // else alert response
                    else {
                        document.getElementById('save-button').innerHTML = 'Error';
                    }
                }
            };
            xhr.send();
        });
    });

    // Rating
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('rating').addEventListener('click', function (event) {
            event.preventDefault(); // prevent default behavior of link
            var url = event.target.getAttribute('href'); // get URL from href attribute
            var xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var response = this.responseText;
                    // if response is "Recipe rated" change link to "Remove"
                    if (response === 'Recipe rated') {
                        document.getElementById('message').innerHTML = 'Recipe rated';
                    }
                    // else alert response
                    else {
                        document.getElementById('message').innerHTML = response;
                    }
                }
            };
            xhr.send();
        });
    });
</script>

</html>