<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/repositories/UserRepository.php";
require_once "$root/data/entities/UserEntity.php";

$userRepository = UserRepository::getInstance();
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['first_name']) && isset($_POST['last_name'])) {
    $user = $userRepository->findById($_SESSION["user_id"]);
    $userRepository->save(
        new UserEntity(
            $user->getId(),
            $user->getEmail(),
            $_POST['first_name'],
            $_POST['last_name'],
            $user->getCreatedAt()
        )
    );

    header('Location:' . $_SERVER['HTTP_REFERER']);
}