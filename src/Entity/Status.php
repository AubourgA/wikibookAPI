<?php

namespace App\Entity;

use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StatusRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Patch()
    ]
)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        match:true,
        message: 'Le champs doit etre que des lettres')]
    private ?string $type = null;

    #[ORM\OneToMany(targetEntity: BookCopy::class, mappedBy: 'status')]
    private Collection $bookCopies;

    public function __construct()
    {
        $this->bookCopies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, BookCopy>
     */
    public function getBookCopies(): Collection
    {
        return $this->bookCopies;
    }

    public function addBookCopy(BookCopy $bookCopy): static
    {
        if (!$this->bookCopies->contains($bookCopy)) {
            $this->bookCopies->add($bookCopy);
            $bookCopy->setStatus($this);
        }

        return $this;
    }

    public function removeBookCopy(BookCopy $bookCopy): static
    {
        if ($this->bookCopies->removeElement($bookCopy)) {
            // set the owning side to null (unless already changed)
            if ($bookCopy->getStatus() === $this) {
                $bookCopy->setStatus(null);
            }
        }

        return $this;
    }
}
