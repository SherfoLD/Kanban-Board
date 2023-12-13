<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/repositories/ListRepository.php";
require_once "$root/data/repositories/CardRepository.php";
require_once "$root/data/repositories/TeamUserRepository.php";
require_once "$root/data/repositories/UserRepository.php";
require_once "$root/data/repositories/BoardRepository.php";


class BoardController
{

    private $boardId;
    private $teamUserId;
    private $board;

    public function __construct($boardId, $teamUserId)
    {
        $this->boardId = $boardId;
        $this->teamUserId = $teamUserId;
    }

    public function getBoard(): array
    {
        $listRepository = ListRepository::getInstance();
        $cardRepository = CardRepository::getInstance();

        $userRole = $this->getCurrentUserRole();

        $result = $listRepository->fetchAllByBoardId($this->boardId);
        $arrayOfLists = pg_fetch_all($result);

        foreach ($arrayOfLists as $listKey => $list) {
            $userRole < 3 ? $arrayOfLists[$listKey]['editable'] = 1 : $arrayOfLists[$listKey]['editable'] = 0;

            $arrayOfLists[$listKey]['created_by'] = $this->getFirstAndLastName($arrayOfLists[$listKey]['created_by']);

            $result = $cardRepository->fetchAllByListId($list['id']);
            $arrayOfCards = pg_fetch_all($result);
            $cardPos = 0;

            foreach ($arrayOfCards as $card) {
                ($userRole < 3 || $card['created_by'] == $this->teamUserId) ?
                    $card['editable'] = 1 : $card['editable'] = 0;

                $card['created_by'] = $this->getFirstAndLastName($card['created_by']);

                $arrayOfLists[$listKey][$cardPos] = $card;
                $cardPos++;
            }


        }
        $this->board = $arrayOfLists;


        return $this->board;
    }

    private function getCurrentUserRole()
    {
        $teamUserRepository = TeamUserRepository::getInstance();

        $teamUser = $teamUserRepository->findById($this->teamUserId);

        return $teamUser->getRole();
    }

    private function getFirstAndLastName($teamUserId): string
    {
        $teamUserRepository = TeamUserRepository::getInstance();
        $userRepository = UserRepository::getInstance();

        $teamUser = $teamUserRepository->findById($teamUserId);
        $user = $userRepository->findById($teamUser->getUserId());

        return $user->getFirstName() . " " . $user->getLastName();
    }


}

/*
 * lists -> ({
 *      id =
 *      name =
 *      created by =
 *      editable =
 *      cards -> ({
 *          id =
 *          name =
 *          created by =
 *          editable =
 *      },
 *      {
 *          id =
 *          name =
 *          created by =
 *          editable =
 *      })
 * },
 * {
 *      id =
 *      name =
 *      created by =
 *      editable =
 *      cards -> ({
 *          id =
 *          name =
 *          created by =
 *          editable =
 *      },
 *      {
 *          id =
 *          name =
 *          created by =
 *          editable =
 *      })
 * })
 */

