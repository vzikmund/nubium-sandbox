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
     * Ziskat uzivatele podle jeho loginu
     * @param string $login
     * @param bool $activeOnly
     * @return Selection
     */
    public function getByLogin(string $login, bool $activeOnly = false): Selection
    {
        $selection = $this->getTable()->where("login", $login);
        if ($activeOnly) {
            $selection->where("is_active", 1);
        }

        return $selection;
    }


    /**
     * Kontrola, jestli je aktualne prihlaseny uzivatel stale aktivni
     * @param int $idUser
     * @return bool
     */
    public function isUserActive(int $idUser): bool
    {
        $row = $this->getTable()->where("id", $idUser)
            ->where("is_active", 1)->fetch();

        return $row !== null;

    }


}