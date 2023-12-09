<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/repositories/TeamRepository.php";
require_once "$root/data/entities/TeamEntity.php";
require_once "$root/data/repositories/TeamUserRepository.php";
require_once "$root/data/entities/TeamUserEntity.php";

$teamRepository = TeamRepository::getInstance();
$teamUserRepository = TeamUserRepository::getInstance();
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    if (!$result) {
        $errorMessage = error_get_last()["message"];
        error_clear_last();
        $formData = [
            'name' => $name,
        ];

        header('Location: /kanban/teams/create.php?error=' . urlencode($errorMessage) . '&' . http_build_query($formData));
        exit();
    }

    $teamId = pg_fetch_assoc($result)["id"];
    $teamUserRepository->save(
        new TeamUserEntity(
            null,
            $_SESSION['user_id'],
            $teamId,
            1 //Owner
        )
    );

    header('Location: /kanban/teams/team.php?name=' . $name);
    exit();
}
