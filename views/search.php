<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title; ?>
    </title>
</head>

<body>
    <h1>Search Results</h1>

    <form action="" method="get">
        <label for="search">Search:</label>
        <input type="text" id="search" name="param" <?php if ($param): ?> value="<?php echo $param; ?>" <?php endif; ?>>
        <button type="submit">Submit</button>
    </form>

    <?php if ($param): ?>
        <h2>Results for "<?php echo $param; ?>" (Total Results: <?php echo $total_results; ?>)</h2>

        <?php if ($search_results): ?>
            <?php foreach ($search_results as $result): ?>
                <h3>
                    <a href="<?php echo route('recipe/' . $result['id']); ?>"><?php echo $result['title']; ?></a>
                </h3>
                <p><b>Directions:</b>
                    <?php echo nl2br($result['directions']); ?>
                </p>
                <p><b>Ingredients:</b>
                    <?php echo nl2br($result['ingredients']); ?>
                </p>
                <p><b>Categories:</b>
                    <?php echo $result['categories']; ?>
                </p>
                <p><b>Created:</b>
                    <?php echo $result['created_at']; ?>
                </p><br>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
        <!-- Pagination -->
        <?php if ($prev): ?>
            <a href="<?php echo route('search', ['param' => $param, 'page' => $prev]); ?>">Previous</a>
        <?php endif; ?>
        <?php if ($next): ?>
            <a href="<?php echo route('search', ['param' => $param, 'page' => $next]); ?>">Next</a>
        <?php endif; ?>
    <?php endif; ?>

</body>

</html>