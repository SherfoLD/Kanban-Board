<?php include '../../templates/authorization_header.php'; ?>

<?php if (isset($_GET['error'])) {
    echo '<p style="color: red;">' . $_GET['error'] . '</p>';
}

$emailValue = $_GET['email'] ?? '';
$firstNameValue = $_GET['first_name'] ?? '';
$lastNameValue = $_GET['last_name'] ?? '';
?>

<form class="fancy-form" method="post" action="/handlers/RegisterHandler.php">
    <label for="username">Email:</label>
    <input class="fancy-input" type="text" name="email" value="<?= $emailValue ?>" required><br>

    <label for="username">First Name:</label>
    <input class="fancy-input" type="text" name="first_name" value="<?= $firstNameValue ?>" required><br>

    <label for=" username">Last Name:</label>
    <input class="fancy-input" type="text" name="last_name" value="<?= $lastNameValue ?>" required><br>

    <label for=" password">Create a password:</label>
    <input class="fancy-input" type="password" name="password" required><br>

    <button class="fancy-button" type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>

<?php include '../../templates/footer.php'; ?>
