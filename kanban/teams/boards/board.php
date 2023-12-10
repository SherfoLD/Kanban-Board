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
                <h3><?= htmlspecialchars($list['name']) ?></h3>

                <?php foreach ($list as $item): ?>
                    <?php if (is_array($item)): ?>
                        <?php if ($item['editable']): ?>
                            <div class="card editable-card">
                                <input type="text" value="<?=htmlspecialchars($item['name'])?>" />
                            </div>
                        <?php else: ?>
                            <div class="card">
                                <p><?= htmlspecialchars($item['name']) ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

            </div>
        <?php endforeach; ?>
    </div>


<?php function drawForm()
{
    echo '
    <form id="hiddenForm" method="post" style="margin-top: 20px;">
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <input type="submit" name="submit" value="Submit">
    </form>';
    } ?>


    <script>
        function toggleForm() {
            var form = document.getElementById('hiddenForm');
            form.style.display = (form.style.display == 'none') ? 'block' : 'none';
        }
    </script>

    <pre>
        <?php print_r($kanbanData) ?>
    </pre>

<?php include '../../../templates/footer.php' ?>