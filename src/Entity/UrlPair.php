<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UrlPairRepository")
 */
class UrlPair
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $short_url;

    /**
     * @ORM\Column(type="text")
     */
    private $long_url;

    /**
     * @ORM\Column(type="integer")
     */
    private $used_times = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShortUrl(): ?string
    {
        return $this->short_url;
    }

    public function setShortUrl(string $short_url): self
    {
        $this->short_url = $short_url;

        return $this;
    }

    public function getUsedTimes(): ?int
    {
        return $this->used_times;
    }

    public function setUsedTimes(?int $used_times): self
    {
        $this->used_times = $used_times;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getLongUrl(): ?string
    {
        return $this->long_url;
    }

    public function setLongUrl(string $long_url): self
    {
        $this->long_url = $long_url;

        return $this;
    }
}
