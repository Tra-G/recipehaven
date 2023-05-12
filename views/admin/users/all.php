<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
</head>

<body>
    <h1>User List</h1>
    <ul>
        <?php if (empty($users)): ?>
            <li>No users found.</li>
        <?php else: ?>
            <?php foreach ($users as $user): ?>
                <li>
                    <?php echo $user['first_name'] .' '. $user['last_name']; ?> (<?php echo $user['email']; ?>)
                    [<a href="<?php echo route('admin/user/'.$user['id'].'/edit'); ?>">Edit</a>]
                    [<a href="<?php echo route('admin/user/'.$user['id'].'/delete'); ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>]
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <?php if ($prev): ?>
        <a href="<?php echo route('admin/users', ['page' => $prev]); ?>">Previous</a>
    <?php endif; ?>
    <?php if ($next): ?>
        <a href="<?php echo route('admin/users', ['page' => $next]); ?>">Next</a>
    <?php endif; ?>
</body>

</html>