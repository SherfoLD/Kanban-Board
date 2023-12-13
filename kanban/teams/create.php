<?php include '../../templates/header.php'; ?>

<form class="fancy-form" method="post" action="/handlers/TeamHandler.php">
    <label for="username">Team name:</label>
    <input type="text" name="name" required><br>

    <button type="submit">Create team</button>
</form>

<?php include '../../templates/footer.php'; ?>
