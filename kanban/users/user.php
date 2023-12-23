<?php include '../../templates/header.php';
require_once '../../data/repositories/TeamUserRepository.php';
?>
<?php
$userRepository = UserRepository::getInstance();
$user = $userRepository->findById($_SESSION['user_id']);

$emailValue = $user->getEmail();
$firstNameValue = $user->getFirstName();
$lastNameValue = $user->getLastName();
?>

<form class="fancy-form" method="post" action="/handlers/UserHandler.php">
    <label>Your email:</label>
    <input class="fancy-input" type="text" name="email" value="<?= $emailValue ?>" required disabled><br>

    <label>Change first name:</label>
    <input class="fancy-input" type="text" name="first_name" value="<?= $firstNameValue ?>" required><br>

    <label>Change last name:</label>
    <input class="fancy-input" type="text" name="last_name" value="<?= $lastNameValue ?>" required><br>

    <button class="fancy-button" type="submit">Save</button>
</form>

<?php include '../../templates/footer.php';?>
