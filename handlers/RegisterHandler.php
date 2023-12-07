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
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $createdAt = (new DateTime("now", new DateTimeZone('Europe/Moscow')))
        ->format('Y-m-d H:i:s.uP');;

    $result = $userRepository->save(
        new UserEntity(
            null,
            $email,
            $firstName,
            $lastName,
            $createdAt
        )
    );
    if (!$result) {
        $errorMessage = error_get_last()["message"];
        error_clear_last();
        $formData = [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
        ];

        header('Location: ../kanban/authorization/register.php?error=' . urlencode($errorMessage) . '&' . http_build_query($formData));
        exit();
    }

    $userId = pg_fetch_assoc($result)["id"];
    $loginDataRepository->save(
        new LoginDataEntity(
            null,
            $userId,
            $email,
            $password
        )
    );

    session_start();
    $_SESSION['user_id'] = $userId;

    header('Location: ../../kanban/teams/team.php');
    exit();
}
