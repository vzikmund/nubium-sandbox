<?php

declare(strict_types=1);

namespace App\Presenters;


use App\Component\Article\ArticleFactory;
use App\Model\ArticleRatingModel;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Utils\Validators;

final class HomepagePresenter extends BasePresenter
{

    /**
     * Stranka seznamu prispevku
     * @persistent
     */
    public int $page = 1;

    /**
     * Defaultni sloupec pro razeni
     * @var string
     */
    public string $order = "id";

    /**
     * Defaultni smer razeni
     * @var string
     */
    public string $direction = "ASC";

    /** @var ArticleFactory @inject */
    public ArticleFactory $articleFactory;

    /** @var ArticleRatingModel @inject */
    public ArticleRatingModel $articleRatingModel;

    public function startup()
    {
        parent::startup();

        # validace paramatru identifikujiciho stranku
        if (!Validators::isNumericInt($this->page) || $this->page < 1) {
            $this->page = 1;
        }

    }

    /**
     * Vykresleni default latte
     * @return void
     */
    public function renderDefault(): void
    {
        $this->template->articles = $this->articleFactory->getPageArticles(
            $this->page, $this->order, $this->direction
        );
        $this->template->paginator = $this->articleFactory->getPaginator($this->page);
    }

    /**
     * Zpracovani hodnoceni prispevku
     *
     * @param int $article
     * @param string $vote
     * @return void
     * @throws \Nette\Application\AbortException
     */
    public function handleVote(int $article, string $vote):void
    {

        # kontrola volani ajaxem
        if (!$this->isAjax()) {
            $this->redirect("this");
        }

        # kontrola prihlasenosti
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect("this");
        }

        $allowedVotes = ["up", "down"];
        # kontrola povolenych hodnot
        if (!in_array($vote, $allowedVotes)) {
            $this->redirect("this");
        }

        try {
            $value = $vote === "up" ? 1 : -1;
            $this->articleRatingModel->insertVote(
                $article, $this->getUser()->getId(), $value
            );
        } catch (UniqueConstraintViolationException $e) {
            # uzivatel jiz jednou hodnotil
        }

        $this->template->articles = $this->articleFactory->getPageArticles(
            $this->page, $this->order, $this->direction
        );
        $this->redrawControl("articles-area");

    }


}
