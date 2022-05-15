<?php
declare(strict_types=1);

namespace App\Model;


use App\Exceptions\User\AuthenticationException;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

final class UserModel extends BaseModel
{

    /**
     * Tabulka s uzivateli
     * @var string
     */
    public const TABLE_NAME = "user";


    /**
     * Tabulka s uzivateli
     * @return Selection
     */
    private function getTable(): Selection
    {
        return $this->explorer->table(self::TABLE_NAME);
    }

    /**
     * Vytvoreni noveho uzivatele
     * @param $values
     * @return ActiveRow
     */
    public function createUser($values): ActiveRow
    {
        return $this->getTable()->insert($values);
    }

    /**
     * @param string $login
     * @return Selection
     */
    public function getByLogin(string $login):Selection{
        return $this->getTable()->where("login", $login);
    }


}