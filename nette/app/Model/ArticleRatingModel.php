<?php
declare(strict_types=1);

namespace App\Model;


use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;

final class ArticleRatingModel extends BaseModel
{

    /** @var string */
    public const TABLE = "article_rating";

    /**
     * @return Selection
     */
    private function getTable(): Selection
    {
        return $this->explorer->table(self::TABLE);
    }

    /**
     * Kontrola, zda clanek byl uzivatelem hodnocen
     * @param int $idUser
     * @param int $idArticle
     * @return bool
     */
    public function userVoted(int $idUser, int $idArticle): bool
    {
        $row = $this->getTable()
            ->where("user_id", $idUser)
            ->where("article_id", $idArticle)->fetch();

        return $row !== null;

    }

    /**
     * Vlozeni hodnoceni
     * @param int $idArticle
     * @param int $idUser
     * @param int $value
     * @return ActiveRow
     */
    public function insertVote(int $idArticle, int $idUser, int $value): ActiveRow
    {
        return $this->getTable()
            ->insert([
                "article_id" => $idArticle,
                "user_id" => $idUser,
                "value" => $value
            ]);
    }

}