<?php
declare(strict_types=1);

namespace App\Component\Article;


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
 */
final class Article
{

    use SmartObject;

    public function __construct(private array $row, private User $user)
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

    /**
     * Clanek viditelny pouze pro prihlasene
     * @return bool
     */
    public function isVisibleForLoggedOnly():bool{
        return $this->row["visible_logged_only"] === 1;
    }

}