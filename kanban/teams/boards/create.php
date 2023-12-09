<?php include '../../../templates/header.php'; ?>

<form method="post" action="/handlers/BoardHandler.php">
    <label for="username">Board name:</label>
    <input type="text" name="name" required><br>

    <input type="hidden" name="team_id" value="<?= $_GET['team']?>" required>

    <button type="submit">Create board</button>
</form>

<?php include '../../../templates/footer.php'; ?>
