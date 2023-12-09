<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/repositories/BoardRepository.php";
require_once "$root/data/entities/BoardEntity.php";

$boardRepository = BoardRepository::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];
    $teamId = $_POST['team_id'];
    $createdAt = (new DateTime("now", new DateTimeZone('Europe/Moscow')))
        ->format('Y-m-d H:i:s.uP');

    $result = $boardRepository->save(
        new BoardEntity(
            null,
            $teamId,
            $name,
            $createdAt
        )
    );

    $boardId = pg_fetch_assoc($result)["id"];

    header('Location: /kanban/teams/boards/board.php?board=' . $boardId);
    exit();
}