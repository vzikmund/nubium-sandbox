<?php
declare(strict_types=1);

namespace App\Component\Article;


use App\Model\ArticleRatingModel;
use Nette\Security\User;
use Nette\SmartObject;
use Nette\Utils\DateTime;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $link
 * @property-read string $excerpt
 * @property-read string $content
 * @property-read string $authorName
 * @property-read string $authorFirstName
 * @property-read string $authorLastname
 * @property-read DateTime $createdAt
 * @property-read DateTime|null $changedAt
 * @property-read int|null $rating
 */
final class Article
{

    use SmartObject;

    public function __construct(private array $row, private User $user, private ArticleRatingModel $articleRatingModel)
    {

    }

    protected function getTitle():string{
        return $this->row["title"];
    }

    protected function getLink():string{
        return $this->row["link"];
    }

    protected function getExcerpt():string{
        return $this->row["excerpt"];
    }

    protected function getContent():string{
        return $this->row["content"];
    }

    protected function getId(): int
    {
        return $this->row["id"];
    }

    protected function getAuthorName(): string
    {
        return sprintf("%s %s", $this->getAuthorFirstName(), $this->getAuthorLastName());
    }

    protected function getAuthorFirstName(): string
    {
        return $this->row["first_name"];
    }

    protected function getAuthorLastName(): string
    {
        return $this->row["last_name"];
    }

    protected function getCreatedAt(): DateTime
    {
        return $this->row["created_at"];
    }

    protected function getChangedAt(): ?DateTime
    {
        return $this->row["changed_at"];
    }

    protected function getRating():?int{
        return $this->row["rating"] ? (int)$this->row["rating"] : null;
    }

    /**
     * Clanek viditelny pouze pro prihlasene
     * @return bool
     */
    public function isVisibleForLoggedOnly():bool{
        return $this->row["visible_logged_only"] === 1;
    }

    /**
     * Informace, zda uzivatel uz hodnotil clanek
     * @return bool|null
     */
    public function userVoted():?bool{
        if(!$this->user->isLoggedIn()) return null;
        return $this->articleRatingModel->userVoted($this->user->getId(), $this->getId());
    }

}