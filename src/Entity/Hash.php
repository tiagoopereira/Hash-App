<?php

namespace App\Entity;

use App\Repository\HashRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HashRepository::class)]
class Hash implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $batch;

    #[ORM\Column(type: 'integer')]
    private $block;

    #[ORM\Column(type: 'string', length: 255)]
    private $string;

    #[ORM\Column(type: 'string', length: 255)]
    private $key;

    #[ORM\Column(type: 'string', length: 255)]
    private $generated_hash;

    #[ORM\Column(type: 'integer')]
    private $attempts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBatch(): ?\DateTimeInterface
    {
        return $this->batch;
    }

    public function setBatch(\DateTimeInterface $batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    public function getBlock(): ?int
    {
        return $this->block;
    }

    public function setBlock(int $block): self
    {
        $this->block = $block;

        return $this;
    }

    public function getString(): ?string
    {
        return $this->string;
    }

    public function setString(string $string): self
    {
        $this->string = $string;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getGeneratedHash(): ?string
    {
        return $this->generated_hash;
    }

    public function setGeneratedHash(string $generated_hash): self
    {
        $this->generated_hash = $generated_hash;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): self
    {
        $this->attempts = $attempts;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'batch' => $this->getBatch()->format('Y-m-d H:i:s'),
            'block' => $this->getBlock(),
            'string' => $this->getString(),
            'key' => $this->getKey(),
            'generated_hash' => $this->getGeneratedHash(),
            'attempts' => $this->getAttempts()
        ];
    }
}
