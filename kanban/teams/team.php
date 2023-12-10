<?php include '../../templates/header.php' ?>
<?php if (isset($_GET['error'])) {
    echo '<p style="color: red;">' . $_GET['error'] . '</p>';
}
?>

<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/repositories/TeamRepository.php";
require_once "$root/data/repositories/TeamUserRepository.php";
require_once "$root/data/repositories/UserRepository.php";
require_once "$root/data/repositories/BoardRepository.php";

function printAllTeams(): void
{
    echo '<div class="team-tile"><a href="create.php">Create team</a></div><br>';

    $teamUserRepository = TeamUserRepository::getInstance();
    $teamRepository = TeamRepository::getInstance();

    $result = $teamUserRepository->fetchAllTeamsByUserId($_SESSION['user_id']);
    $array = pg_fetch_all($result, PGSQL_NUM);
    $teamIds = [];
    foreach ($array as $subArray)
        $teamIds[] = $subArray[0];

    echo '<h4>Teams you in:</h4>';
    foreach ($teamIds as $teamId) {
        $teamName = $teamRepository->findById($teamId)->getName();

        echo '<div class="team-tile"><a href="team.php?team=' . $teamId . '">' . $teamName . '</a></div>';
    }

}

function printTeamUsersOverview($teamId): void
{
    $teamUserRepository = TeamUserRepository::getInstance();
    $userRepository = UserRepository::getInstance();

    addTeamUserAddForm($teamId);

    $result = $teamUserRepository->fetchAllUsersByTeamId($teamId);
    $array = pg_fetch_all($result, PGSQL_NUM);

    $userIds = [];
    foreach ($array as $subArray)
        $userIds[] = $subArray[0];

    echo '<h4>Users in the team:</h4>';
    $thisUser = $teamUserRepository->findByTeamIdAndUserId($teamId, $_SESSION['user_id']);
    foreach ($userIds as $userId) {
        $user = $userRepository->findById($userId);
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();

        echo '<div class="user-card">' . $firstName . " " . $lastName;
        $currentUser = $teamUserRepository->findByTeamIdAndUserId($teamId, $userId);
        if ($thisUser->getRole() < $currentUser->getRole())
            echo '<form method="post" action="/handlers/TeamHandler.php">
                    <input type="hidden" name="team_user_id" value="' . $currentUser->getId() . '" required/>
                    <button type="submit" value="Delete">Delete</button>
                </form>';
        echo '</div>';
    }

}

function addTeamUserAddForm($teamId): void
{
    $teamUserRepository = TeamUserRepository::getInstance();
    $teamUser = $teamUserRepository->findByTeamIdAndUserId($teamId, $_SESSION['user_id']);
    if ($teamUser->getRole() < 3)
        echo 'Add user to the team<br>
            <form class="useful-from" method="post" action="/handlers/TeamHandler.php">
                <label for="email">User email:</label>
                <input type="text" name="email" required>
                
                <input type="hidden" name="team_id" value="' . $teamId . '" required>
                
                <button type="submit">Add user</button>
            </form>';
}

function printAllBoards($teamId): void
{
    $teamUserRepository = TeamUserRepository::getInstance();
    $teamUser = $teamUserRepository->findByTeamIdAndUserId($teamId, $_SESSION['user_id']);
    if ($teamUser->getRole() < 3)
        echo '<div class="board-tile"><a href="boards/create.php?team=' . $teamId . '">Create a board for the team</a></div><br>';

    $boardRepository = BoardRepository::getInstance();
    $result = $boardRepository->fetchAllBoardsTeamId($teamId);
    $array = pg_fetch_all($result, PGSQL_NUM);

    $boardIds = [];
    foreach ($array as $subArray)
        $boardIds[] = $subArray[0];

    echo '<h4>Available boards:</h4>';
    foreach ($boardIds as $boardId) {
        $board = $boardRepository->findById($boardId);
        $boardName = $board->getName();

        echo '<div class="board-tile"><a href="boards/board.php?board=' . $boardId . '">' . $boardName . '</a>';
        if ($teamUser->getRole() < 3)
            echo '<form method="post" action="/handlers/BoardHandler.php">
                    <input type="hidden" name="board_id" value="' . $boardId . '" required/>
                    <button type="submit" value="Delete">Delete</button>
                </form>';
        echo '</div>';
    }
}

?>

<?php if (isset($_GET['team'])) {
    $teamRepository = TeamRepository::getInstance();
    $teamName = $teamRepository->findById($_GET['team'])->getName();
    echo '<h2 align="center">' . $teamName . '</h2>';

    echo '<div class = columns>';
    echo '<div class="left-column">';
    printAllBoards($_GET['team']);
    echo '</div>';

    echo '<div class="right-column">';
    printTeamUsersOverview($_GET['team']);
    echo '</div>';
    echo '</div>';

} else {
    printAllTeams();
}
?>

<?php include '../../templates/footer.php' ?>