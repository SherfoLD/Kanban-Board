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
    echo '<p> <a href="create.php">Create team</a></p>';

    $teamUserRepository = TeamUserRepository::getInstance();
    $teamRepository = TeamRepository::getInstance();

    $result = $teamUserRepository->fetchAllTeamsByUserId($_SESSION['user_id']);
    $array = pg_fetch_all($result, PGSQL_NUM);
    $teamIds = [];
    foreach ($array as $subArray)
        $teamIds[] = $subArray[0];

    foreach ($teamIds as $teamId) {
        $teamName = $teamRepository->findById($teamId)->getName();

        echo '<div class="team-tile"><a href="team.php?team=' . $teamId . '">' . $teamName . '</a></div>';
    }

}

function printTeamUsersOverview($team_id): void
{
    $teamUserRepository = TeamUserRepository::getInstance();
    $userRepository = UserRepository::getInstance();

    addTeamUserAddForm($team_id);

    $result = $teamUserRepository->fetchAllUsersByTeamId($team_id);
    $array = pg_fetch_all($result, PGSQL_NUM);

    $userIds = [];
    foreach ($array as $subArray)
        $userIds[] = $subArray[0];

    echo 'Users in team:<br>';
    foreach ($userIds as $userId) {
        $user = $userRepository->findById($userId);
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();

        echo '<div class="user-card">' . $firstName . " " . $lastName . '</div>';
    }

}

function addTeamUserAddForm($team_id): void
{
    echo 'Add user to the team<br>
        <form method="post" action="/handlers/TeamHandler.php">
            <label for="email">User email:</label>
            <input type="text" name="email" required>
            
            <input type="hidden" name="team_id" value="' . $team_id . '" required>
            
            <button type="submit">Add user</button>
        </form>';
}

function printAllBoards($team_id): void
{
    echo '<div><a href="boards/create.php?team=' . $team_id . '">Create a board for the team</a></div>';

    $boardRepository = BoardRepository::getInstance();
    $result = $boardRepository->fetchAllBoardsTeamId($team_id);
    $array = pg_fetch_all($result, PGSQL_NUM);

    $boardIds = [];
    foreach ($array as $subArray)
        $boardIds[] = $subArray[0];

    echo 'Available boards:<br>';
    foreach ($boardIds as $boardId) {
        $board = $boardRepository->findById($boardId);
        $boardName = $board->getName();

        echo '<div class="board-tile"><a href="boards/board.php?board=' . $boardId . '">' . $boardName . '</a></div>';
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