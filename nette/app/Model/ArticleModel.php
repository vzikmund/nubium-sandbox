<?php
declare(strict_types=1);

namespace App\Model;


use App\Component\Article\ArticleFactory;
use Nette\Database\Table\Selection;

final class ArticleModel extends BaseModel
{


    /** @var string */
    public const TABLE = "article";

    /**
     * Tabulka se vsemi clanky
     * @return Selection
     */
    public function getTable():Selection{
        return $this->explorer->table(self::TABLE);
    }

    /**
     * Ziskat clanky na stranku
     * @param int $page
     * @param int $perPageCount
     * @param string|array $status
     * @return Selection
     */
    public function getPage(int $page, int $perPageCount, string|array $status = ArticleFactory::STATUS_PUBLIC):Selection{

        return $this->getTable()
            ->select("article.*, user.first_name, user.last_name,SUM(:article_rating.value) AS rating")
            ->where("status", $status)
            ->group("id")
            ->page($page, $perPageCount);

    }


}