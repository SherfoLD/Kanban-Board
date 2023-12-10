<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/repositories/TeamRepository.php";
require_once "$root/data/entities/TeamEntity.php";
require_once "$root/data/repositories/TeamUserRepository.php";
require_once "$root/data/entities/TeamUserEntity.php";
require_once "$root/data/repositories/UserRepository.php";

$teamRepository = TeamRepository::getInstance();
$teamUserRepository = TeamUserRepository::getInstance();
$userRepository = UserRepository::getInstance();
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $createdAt = (new DateTime("now", new DateTimeZone('Europe/Moscow')))
        ->format('Y-m-d H:i:s.uP');

    $result = $teamRepository->save(
        new TeamEntity(
            null,
            $name,
            $createdAt
        )
    );

    $teamId = pg_fetch_assoc($result)["id"];
    $teamUserRepository->save(
        new TeamUserEntity(
            null,
            $_SESSION['user_id'],
            $teamId,
            1 //Owner
        )
    );

    header('Location: /kanban/teams/team.php?team=' . $teamId);
    exit();

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['team_id'])) {
    $email = $_POST['email'];
    $teamId = $_POST['team_id'];

    $user = $userRepository->findByEmail($email);
    if (!$user) {
        header('Location: /kanban/teams/team.php?team=' . $teamId . '&error=' . urlencode("User with this email was not found"));
        exit();
    }

    $result = $teamUserRepository->save(
        new TeamUserEntity(
            null,
            $user->getId(),
            $teamId,
            3 // Reader
        )
    );

    header('Location: /kanban/teams/team.php?team=' . $teamId);
    exit();

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['team_user_id'])) {
    $teamUserRepository->deleteById($_POST['team_user_id']);

    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}

