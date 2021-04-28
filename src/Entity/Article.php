<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="article")
 * @ORM\Entity()
 */
class Article
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(name="title", type="string", length=100)
     */
    private string $title;

    /**
     * @var Collection|Tag[]
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     */
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return Tag[]|Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Tag[]|Collection $tags
     */
    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }
}
