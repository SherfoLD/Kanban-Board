<?php include '../../../templates/header.php';
require_once '../../../controllers/BoardController.php';
require_once '../../../data/repositories/BoardRepository.php';
require_once '../../../data/repositories/TeamUserRepository.php';
?>

<?php
$boardRepository = BoardRepository::getInstance();
$teamUserRepository = TeamUserRepository::getInstance();

$teamId = $boardRepository->findById($_GET['board'])->getTeamId();
$teamUser = $teamUserRepository->findByTeamIdAndUserId($teamId, $_SESSION['user_id']);

$boardController = new BoardController($_GET['board'], $teamUser->getId());
$kanbanData = $boardController->getBoard();
?>

    <div class="kanban-board">
        <?php foreach ($kanbanData as $list): ?>
            <div class="list">
                <b><?= htmlspecialchars($list['name']) ?></b>
                <button onclick="toggleEdit()">Edit</button>

                <?php if ($list['editable']): ?>
                    <form class="small-form" method="post" action="/handlers/BoardHandler.php">
                        <input class="display" type="text" name="list_name"
                               value="<?= htmlspecialchars($list['name']) ?>"/>
                        <input class="display" type="hidden" name="list_id" value="<?= $list['id'] ?>"/>
                        <button class="display" type="submit">Save</button>
                    </form>
                    <form class="small-form" method="post" action="/handlers/BoardHandler.php">
                        <input class="display" type="hidden" name="list_id" value="<?= $list['id'] ?>"/>
                        <button class="display" type="submit">Delete</button>
                    </form>
                    <form class="small-form" method="post" action="/handlers/BoardHandler.php">
                        <input class="display" type="hidden" name="list_id" value="<?= $list['id'] ?>"/>
                        <input class="display" type="hidden" name="list_position_increment" value="-1"/>
                        <button class="display" type="submit">Left</button>
                    </form>
                    <form class="small-form" method="post" action="/handlers/BoardHandler.php">
                        <input class="display" type="hidden" name="list_id" value="<?= $list['id'] ?>"/>
                        <input class="display" type="hidden" name="list_position_increment" value="1"/>
                        <button class="display" type="submit">Right</button>
                    </form>
                <?php endif; ?>

                <?php foreach ($list as $item): ?>
                    <?php if (is_array($item)): ?>
                        <?php if ($item['editable']): ?>

                            <div class="card editable-card">
                                <div class="display-card"> <?= htmlspecialchars($item['name']) ?> </div>
                                <div class="display-card"
                                     style="float: right"> <?= htmlspecialchars($item['created_by']) ?> </div>
                                <form class="small-form" method="post" action="/handlers/BoardHandler.php">
                                    <input class="display" type="text" name="card_name"
                                           value="<?= htmlspecialchars($item['name']) ?>"/>
                                    <input class="display" type="hidden" name="card_id" value="<?= $item['id'] ?>"/>
                                    <button class="display" type="submit">Save</button>
                                </form>
                                <form class="small-form" method="post" action="/handlers/BoardHandler.php">
                                    <input class="display" type="hidden" name="card_id" value="<?= $item['id'] ?>"/>
                                    <button class="display" type="submit">Delete</button>
                                </form>
                                <form class="small-form" method="post" action="/handlers/BoardHandler.php">
                                    <input class="display" type="hidden" name="card_id" value="<?= $item['id'] ?>"/>
                                    <input class="display" type="hidden" name="card_position_increment" value="1"/>
                                    <button class="display" type="submit">Down</button>
                                </form>
                                <form class="small-form" method="post" action="/handlers/BoardHandler.php">
                                    <input class="display" type="hidden" name="card_id" value="<?= $item['id'] ?>"/>
                                    <input class="display" type="hidden" name="card_position_increment" value="-1"/>
                                    <button class="display" type="submit">Up</button>
                                </form>
                            </div>

                        <?php else: ?>
                            <div class="card">
                                <div><?= htmlspecialchars($item['name']) ?></div>
                                <div style="float: right"> <?= htmlspecialchars($item['created_by']) ?> </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <div class="card editable-card">
                    <form class="small-form" method="post" action="/handlers/BoardHandler.php">
                        <input type="text" name="card_name" placeholder="Card name" required/>
                        <input type="hidden" name="list_id" value="<?= $list['id'] ?>" required/>
                        <input type="hidden" name="created_by" value="<?= $teamUser->getId() ?>" required/>
                        <button type="submit">Add card</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if ($kanbanData == null || $kanbanData[0]['editable']): ?>
            <div class="list">
                <form class="small-form" method="post" action="/handlers/BoardHandler.php">
                    <input type="text" name="list_name" placeholder="List name" required/>
                    <input type="hidden" name="board_id" value="<?= $_GET['board'] ?>" required/>
                    <input type="hidden" name="created_by" value="<?= $teamUser->getId() ?>" required/>
                    <button type="submit">Add list</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .display {
            display: none;
        }

        .display-card {
            display: block;
        }
    </style>
    <script>
        function toggleEdit() {
            var cardsText = document.getElementsByClassName('display-card');
            for (var i = 0; i < cardsText.length; i++)
                cardsText[i].style.display = (cardsText[i].style.display === 'none') ? 'block' : 'none';

            var buttons = document.getElementsByClassName('display');
            for (var j = 0; j < buttons.length; j++)
                buttons[j].style.display = (buttons[j].style.display === 'block') ? 'none' : 'block';
        }
    </script>

    <pre>
        <?php print_r($kanbanData) ?>
    </pre>

<?php include '../../../templates/footer.php' ?>