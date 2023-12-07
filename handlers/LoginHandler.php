<?php
require_once "../data/repositories/UserRepository.php";
require_once "../data/repositories/LoginDataRepository.php";
require_once "../data/entities/UserEntity.php";
require_once "../data/entities/LoginDataEntity.php";

$userRepository = UserRepository::getInstance();
$loginDataRepository = LoginDataRepository::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $userId = $loginDataRepository->findUserByEmailAndPassword($email, $password);
    if(!$userId){
        $errorMessage = "Incorrect email or password";
        $formData = [
            'email' => $email,
        ];

        header('Location: ../kanban/authorization/login.php?error=' . urlencode($errorMessage) . '&' . http_build_query($formData));
        exit();
    }

    session_start();
    $_SESSION['user_id'] = $userId;

    header('Location: ../../kanban/teams/team.php');
    exit();
}
