<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
#[ApiResource]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
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
