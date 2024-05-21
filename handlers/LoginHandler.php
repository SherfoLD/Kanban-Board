<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/repositories/UserRepository.php";
require_once "$root/data/repositories/LoginDataRepository.php";
require_once "$root/data/entities/UserEntity.php";
require_once "$root/data/entities/LoginDataEntity.php";

$userRepository = UserRepository::getInstance();
$loginDataRepository = LoginDataRepository::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userId = $loginDataRepository->findUserByEmailAndPassword($email, $password);
    if (!$userId) {
        $errorMessage = "Incorrect email or password";
        $formData = [
            'email' => $email,
        ];

        header('Location: /kanban/authorization/login.php?error=' . urlencode($errorMessage) . '&' . http_build_query($formData));
        exit();
    }

    $isBlocked = $loginDataRepository->isUserBlockedByUserId($userId);
    if ($isBlocked) {
        $errorMessage = "You are blocked by admin";
        $formData = [
            'email' => $email,
        ];

        header('Location: /kanban/authorization/login.php?error=' . urlencode($errorMessage) . '&' . http_build_query($formData));
        exit();
    }


    session_start();
    $_SESSION['user_id'] = $userId;

    header('Location: /kanban/teams/team.php');
    exit();

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['action'])) {
    if ($_POST['action'] == "block") {
        $userId = $_POST['user_id'];

        $loginData = $loginDataRepository->findByUserId($userId);

        $loginDataRepository->save(
            new LoginDataEntity(
                $loginData->getId(),
                $loginData->getUserId(),
                $loginData->getEmail(),
                $loginData->getPassword(),
                1
            )
        );

        $errorMessage = "User was blocked";
        header('Location:' . $_SERVER['HTTP_REFERER'] . '&error=' . urldecode($errorMessage));
        exit();

    } else if ($_POST['action'] == "unblock") {
        $userId = $_POST['user_id'];

        $loginData = $loginDataRepository->findByUserId($userId);

        $loginDataRepository->save(
            new LoginDataEntity(
                $loginData->getId(),
                $loginData->getUserId(),
                $loginData->getEmail(),
                $loginData->getPassword(),
                0
            )
        );

        $errorMessage = "User was unblocked";
        header('Location:' . $_SERVER['HTTP_REFERER'] . '&error=' . urldecode($errorMessage));
        exit();
    }
}
