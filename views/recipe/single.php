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
            <a id="save-button" href="<?php echo route('recipe/' . $recipe['id'] . '/unsave'); ?>">Remove from saves</a>
        <?php else: ?>
            <a id="save-button" href="<?php echo route('recipe/' . $recipe['id'] . '/save'); ?>">Save Recipe</a>
        <?php endif; ?>
        [Total Saves: <?php echo $total_saves; ?>]
    <?php endif; ?>

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
        <b>Comments: [Total: <?php echo $total_comments; ?>]</b><br>
        <?php if ($comments): ?>
            <?php foreach ($comments as $comment): ?>
            <p>
                <b>
                    <?php echo $comment['first_name'] . ' ' . $comment['last_name']; ?>
                    [<?php echo $comment['created_at']; ?>]
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

    <!-- Comment Form -->
    <?php if ($logged): ?>
        <form id="comment-form" action="<?php echo route('recipe/' . $recipe['id'] . '/comment'); ?>" method="POST">
            <textarea name="comment" placeholder="Comment"></textarea><br>
            <input type="submit" value="Submit">
        </form>
    <?php endif; ?>

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
                        alert(response);
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
                    // if response is "Recipe rated" display message
                    if (response === 'Recipe rated') {
                        document.getElementById('message').innerHTML = 'Ratings submitted successfully';
                    }
                    // else alert response
                    else {
                        alert(response);
                    }
                }
            };
            xhr.send();
        });
    });

    // Comment
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('comment-form').addEventListener('submit', function (event) {
            event.preventDefault(); // prevent default behavior of form
            var url = this.getAttribute('action'); // get URL from action attribute
            var formData = new FormData(this); // get form data
            var xhr = new XMLHttpRequest();
            xhr.open('POST', url, true);
            xhr.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    var response = this.responseText;
                    // if response is "Comment added" reload page
                    if (response === 'Comment added') {
                        location.reload();
                    }
                    // else alert response
                    else {
                        alert(response);
                    }
                }
            };
            xhr.send(formData);
        });
    });
</script>

</html>