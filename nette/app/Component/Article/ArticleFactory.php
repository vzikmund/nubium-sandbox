<?php
declare(strict_types=1);

namespace App\Component\Article;


use App\Model\ArticleModel;
use App\Model\ArticleRatingModel;
use Nette\Security\User;
use Nette\Utils\Paginator;

final class ArticleFactory
{

    /** @var string */
    public const
        STATUS_PUBLIC = "public",
        STATUS_PRIVATE = "private",
        STATUS_ARCHIVED = "archived";


    public function __construct(
        private int                $articlesPerPage,
        private ArticleModel       $articleModel,
        private ArticleRatingModel $articleRatingModel, private User $user)
    {
    }


    /**
     * Vytvoreni instanci pro clanek
     * @param int $page
     * @param string $orderCol
     * @return Article[]
     */
    public
    function getPageArticles(int $page, string $orderCol): array
    {

        # vyber jak radit
        $order = match ($orderCol){
            "date" =>  "created_at DESC",
            "title" => "title DESC",
            "vote" => "SUM(`article_rating`.`value`) IS NULL, SUM(`article_rating`.`value`) DESC",
            default => "id DESC"
        };


        $rows = $this->articleModel->getPage($page, $this->articlesPerPage)
            ->order($order)
            ->fetchAssoc("id");

        $articles = [];

        foreach ($rows as $id => $row) {
            $articles[$id] = new Article($row, $this->user, $this->articleRatingModel);
        }
        return $articles;

    }

    /**
     * Vytvoreni utility pro strankovani
     * @param int $currentPage
     * @return Paginator
     */
    public
    function getPaginator(int $currentPage): Paginator
    {
        $articlesCount = $this->articleModel->getTable()->where("status", self::STATUS_PUBLIC)->count("id");
        $paginator = new Paginator();
        $paginator->setPage($currentPage)->setItemsPerPage($this->articlesPerPage)->setItemCount($articlesCount);
        return $paginator;
    }

}