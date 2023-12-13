<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/repositories/BoardRepository.php";
require_once "$root/data/entities/BoardEntity.php";
require_once "$root/data/repositories/ListRepository.php";
require_once "$root/data/entities/ListEntity.php";
require_once "$root/data/repositories/CardRepository.php";
require_once "$root/data/entities/CardEntity.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['board_name']) && isset($_POST['team_id'])) {
    createBoard($_POST['board_name'], $_POST['team_id']);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['list_name']) && isset($_POST['board_id']) && isset($_POST['created_by'])) {
    createList($_POST['list_name'], $_POST['board_id'], $_POST['created_by']);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_name']) && isset($_POST['list_id']) && isset($_POST['created_by'])) {
    createCard($_POST['card_name'], $_POST['list_id'], $_POST['created_by']);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['list_id']) && isset($_POST['list_name'])) {
    editListName($_POST['list_name'], $_POST['list_id']);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_id']) && isset($_POST['card_name'])) {
    editCardName($_POST['card_name'], $_POST['card_id']);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_id']) && isset($_POST['card_position_increment'])) {
    editCardPosition($_POST['card_position_increment'], $_POST['card_id']);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['list_id']) && isset($_POST['list_position_increment'])) {
    editListPosition($_POST['list_position_increment'], $_POST['list_id']);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['board_id'])) {
    deleteBoard($_POST['board_id']);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_id'])) {
    deleteCard($_POST['card_id']);

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['list_id'])) {
    deleteList($_POST['list_id']);
}


function editListPosition(int $listPositionIncrement, $listId): void
{
    $listRepository = ListRepository::getInstance();

    $list = $listRepository->findById($listId);

    $listToSwapWith = $listRepository->findByBoardIdAndPosition($list->getBoardId(),
        $list->getPosition() + $listPositionIncrement);

    $listRepository->save(
        new ListEntity(
            $listId,
            $list->getBoardId(),
            $list->getName(),
            $list->getPosition() + $listPositionIncrement,
            $list->getCreatedBy(),
            $list->getCreatedAt()
        )
    );
    if ($listToSwapWith)
        $listRepository->save(
            new ListEntity(
                $listToSwapWith->getId(),
                $listToSwapWith->getBoardId(),
                $listToSwapWith->getName(),
                $listToSwapWith->getPosition() - $listPositionIncrement,
                $listToSwapWith->getCreatedBy(),
                $listToSwapWith->getCreatedAt()
            )
        );

    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}

function editCardPosition(int $cardPositionIncrement, $cardId): void
{
    $cardRepository = CardRepository::getInstance();

    $card = $cardRepository->findById($cardId);

    $cardToSwapWith = $cardRepository->findByListIdAndPosition($card->getListId(),
        $card->getPosition() + $cardPositionIncrement);

    $cardRepository->save(
        new CardEntity(
            $cardId,
            $card->getListId(),
            $card->getName(),
            $card->getPosition() + $cardPositionIncrement,
            $card->getCreatedBy(),
            $card->getCreatedAt()
        )
    );
    if ($cardToSwapWith)
        $cardRepository->save(
            new CardEntity(
                $cardToSwapWith->getId(),
                $cardToSwapWith->getListId(),
                $cardToSwapWith->getName(),
                $cardToSwapWith->getPosition() - $cardPositionIncrement,
                $cardToSwapWith->getCreatedBy(),
                $cardToSwapWith->getCreatedAt()
            )
        );

    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}

function editCardName($cardName, $cardId): void
{
    $cardRepository = CardRepository::getInstance();

    $card = $cardRepository->findById($cardId);

    $cardRepository->save(
        new CardEntity(
            $cardId,
            $card->getListId(),
            $cardName,
            $card->getPosition(),
            $card->getCreatedBy(),
            $card->getCreatedAt()
        )
    );

    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}

function editListName($listName, $listId): void
{
    $listRepository = ListRepository::getInstance();

    $list = $listRepository->findById($listId);

    $listRepository->save(
        new ListEntity(
            $listId,
            $list->getBoardId(),
            $listName,
            $list->getPosition(),
            $list->getCreatedBy(),
            $list->getCreatedAt()
        )
    );

    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}

function deleteList($listId): void
{
    $listRepository = ListRepository::getInstance();

    $listRepository->deleteById($listId);

    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}


function deleteCard($cardId): void
{
    $cardRepository = CardRepository::getInstance();

    $cardRepository->deleteById($cardId);

    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}

function createCard($name, $listId, $createdBy): void
{
    $cardRepository = CardRepository::getInstance();

    $position = $cardRepository->findLastPositionByListId($listId) + 1;
    $createdAt = (new DateTime("now", new DateTimeZone('Europe/Moscow')))
        ->format('Y-m-d H:i:s.uP');

    $cardRepository->save(
        new CardEntity(
            null,
            $listId,
            $name,
            $position,
            $createdBy,
            $createdAt
        )
    );

    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}


function createList($name, $boardId, $createdBy): void
{
    $listRepository = ListRepository::getInstance();

    $position = $listRepository->findLastPositionByBoardId($boardId) + 1;
    $createdAt = (new DateTime("now", new DateTimeZone('Europe/Moscow')))
        ->format('Y-m-d H:i:s.uP');

    $listRepository->save(
        new ListEntity(
            null,
            $boardId,
            $name,
            $position,
            $createdBy,
            $createdAt
        )
    );

    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}

function createBoard($name, $teamId): void
{
    $boardRepository = BoardRepository::getInstance();

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

    header('Location: /kanban/teams/boards/board.php?board=' . $boardId . '&team=' . $teamId);
    exit();
}

function deleteBoard($boardId): void
{
    $boardRepository = BoardRepository::getInstance();

    $boardRepository->deleteById($boardId);

    header('Location:' . $_SERVER['HTTP_REFERER']);
    exit();
}