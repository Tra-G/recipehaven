<!DOCTYPE html>
<html>

<head>
    <title>Contact Us</title>
</head>

<body>
    <h1>Contact Us</h1>
    <p>Got a question or feedback? We'd love to hear from you!</p>
    <form action="submit-form.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" required></textarea><br>
        <button type="submit">Send</button>
    </form>
</body>

</html>