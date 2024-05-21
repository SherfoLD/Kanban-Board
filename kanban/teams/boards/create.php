<?php include '../../../templates/header.php'; ?>

<form class="fancy-form" method="post" action="/handlers/BoardHandler.php">
    <label for="username">Board name:</label>
    <input class="fancy-input" type="text" name="board_name" required><br>

    <input type="hidden" name="team_id" value="<?= $_GET['team']?>" required>

    <button class="fancy-button" type="submit">Create board</button>
</form>

<?php include '../../../templates/footer.php'; ?>
