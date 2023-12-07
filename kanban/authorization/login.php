
<form method="post" action="../../handlers/LoginHandler.php">
    <label for="username">Email:</label>
    <input type="text" name="email" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>

<?php include '../../templates/footer.php'; ?>
