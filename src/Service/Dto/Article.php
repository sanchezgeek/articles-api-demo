<?php

declare(strict_types=1);

namespace App\Service\Dto;

class Article
{
    private int $id;
    private string $title;
    private array $tags;

    /**
     * @param int $id
     * @param string $title
     * @param Tag[] $tags
     */
    public function __construct(int $id, string $title, array $tags)
    {
        $this->id = $id;
        $this->title = $title;
        $this->tags = $tags;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTags():array
    {
        return $this->tags;
    }
}
