<?php include '../../templates/authorization_header.php'; ?>

<?php if (isset($_GET['error'])) {
    echo '<p style="color: red;">' . $_GET['error'] . '</p>';
}

$emailValue = $_GET['email'] ?? '';
?>

<form class="useful-from" method="post" action="/handlers/LoginHandler.php">
    <label for="username">Email:</label>
    <input type="text" name="email" value="<?= $emailValue ?>" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>

<?php include '../../templates/footer.php'; ?>
