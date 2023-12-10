<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
require_once "$root/data/repositories/ListRepository.php";
require_once "$root/data/repositories/CardRepository.php";
require_once "$root/data/repositories/TeamUserRepository.php";


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
            if ($userRole < 3)
                $arrayOfLists[$listKey]['editable'] = 1;
            else
                $arrayOfLists[$listKey]['editable'] = 0;

            $result = $cardRepository->fetchAllByListId($list['id']);
            $arrayOfCards = pg_fetch_all($result);

            $cardPos = 0;
            foreach ($arrayOfCards as $card) {
                if ($userRole < 3 || $card['created_by'] == $this->teamUserId)
                    $card['editable'] = 1;
                else
                    $card['editable'] = 0;

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

