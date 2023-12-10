<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/repositories/UserRepository.php";

$userRepository = UserRepository::getInstance();

session_start();
if (!$_SESSION['user_id'])
    header('Location: /kanban/authorization/login.php');


$userEntity = $userRepository->findById($_SESSION['user_id']);
if (!$userEntity)
    header('Location: /kanban/authorization/login.php');

$firstName = $userEntity->getFirstName();
$lastName = $userEntity->getLastName();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/templates/css/styles.css">
    <link rel="stylesheet" href="/templates/css/board.css">
    <link rel="stylesheet" href="/templates/css/header.css">
    <link rel="stylesheet" href="/templates/css/footer.css">
    <link rel="stylesheet" href="/templates/css/team.css">
    <title>Kanban Board</title>
</head>
<body>
<header>
    <nav>
        <div class="left-links">
            <a href="/kanban/teams/team.php">Teams</a>
        </div>
        <div class="right-links">
            <div class="dropdown">
                <a href="#"><?= $firstName . " " . $lastName ?></a>
                <div class="dropdown-content">
                    <a href="/kanban/users/user.php">Profile</a>
                    <a href="/kanban/authorization/logout.php">Log Out</a>
                </div>
            </div>
        </div>
    </nav>
</header>
<main>