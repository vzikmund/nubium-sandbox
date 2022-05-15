<?php

declare(strict_types=1);

namespace App\Presenters;


use App\Component\Article\ArticleFactory;
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

    public function startup()
    {
        parent::startup();

        # validace paramatru identifikujiciho stranku
        if (!Validators::isNumericInt($this->page) || $this->page < 1) {
            $this->page = 1;
        }

    }

    public function renderDefault(): void
    {
        $this->template->articles = $this->articleFactory->getPageArticles(
            $this->page, $this->order, $this->direction
        );
    }


}
