<?php include '../../../templates/header.php';
require_once '../../../controllers/BoardController.php';
require_once '../../../data/repositories/TeamUserRepository.php' ?>

<?php
$teamUserRepository = TeamUserRepository::getInstance();
$teamUser = $teamUserRepository->findByTeamIdAndUserId($_GET['team'], $_SESSION['user_id']);

$boardController = new BoardController($_GET['board'], $teamUser->getId());
$kanbanData = $boardController->getBoard();
?>

    <div class="kanban-board">
        <?php foreach ($kanbanData as $list): ?>
            <div class="list">
                <b><?= htmlspecialchars($list['name']) ?></b>
                <button onclick="toggleEdit()">Edit</button>
                <?php if ($list['editable']): ?>
                    <form method="post" action="/handlers/BoardHandler.php">
                        <input id="cardId" class="display" type="hidden" name="list_id" value="<?= $list['id'] ?>"/>
                        <button type="submit">Delete</button>
                    </form>
                    <form method="post" action="/handlers/BoardHandler.php">
                        <input class="display" type="text" name="list_name"
                               value="<?= htmlspecialchars($list['name']) ?>"/>
                        <input class="display" type="hidden" name="list_id" value="<?= $list['id'] ?>"/>
                        <button class="display" type="submit">Save</button>
                    </form>
                <?php endif; ?>

                <?php foreach ($list as $item): ?>
                    <?php if (is_array($item)): ?>
                        <?php if ($item['editable']): ?>
                            <div class="card editable-card">
                                <form method="post" action="/handlers/BoardHandler.php">
                                    <input class="readonly" type="text" readonly="readonly" name="card_name"
                                           value="<?= htmlspecialchars($item['name']) ?>"/>
                                    <input class="display" type="hidden" name="card_id" value="<?= $item['id'] ?>"/>
                                    <button type="submit">Save</button>
                                </form>
                                <form method="post" action="/handlers/BoardHandler.php">
                                    <input class="display" type="hidden" name="card_id" value="<?= $item['id'] ?>"/>
                                    <button type="submit">Delete</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="card">
                                <p><?= htmlspecialchars($item['name']) ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="card">
                    <form method="post" action="/handlers/BoardHandler.php">
                        <input type="text" name="name" placeholder="Card name" required/>
                        <input type="hidden" name="list_id" value="<?= $list['id'] ?>" required/>
                        <input type="hidden" name="created_by" value="<?= $teamUser->getId() ?>" required/>
                        <button type="submit">Add card</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if ($kanbanData == null || $kanbanData[0]['editable']): ?>
            <div class="list">
                <form method="post" action="/handlers/BoardHandler.php">
                    <input type="text" name="name" placeholder="List name" required/>
                    <input type="hidden" name="board_id" value="<?= $_GET['board'] ?>" required/>
                    <input type="hidden" name="created_by" value="<?= $teamUser->getId() ?>" required/>
                    <button type="submit">Add list</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .readonly {
            background-color: #f0f0f0;
        }

        .display {
            display: none;
        }
    </style>
    <script>
        function toggleEdit() {
            var inputs = document.getElementsByClassName('readonly');
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].style.backgroundColor = (inputs[i].style.backgroundColor === 'white') ? '#f0f0f0' : 'white';

                if (inputs[i].hasAttribute('readonly'))
                    inputs[i].removeAttribute('readonly');
                else
                    inputs[i].setAttribute('readonly', 'readonly');
            }

            var buttons = document.getElementsByClassName('display');
            for (var j = 0; j < buttons.length; j++)
                buttons[j].style.display = (buttons[j].style.display === 'block') ? 'none' : 'block';
        }
    </script>

    <pre>
        <?php print_r($kanbanData) ?>
    </pre>

<?php include '../../../templates/footer.php' ?>