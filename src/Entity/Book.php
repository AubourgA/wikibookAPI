<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;

use App\Repository\BookRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Patch(),
        new Delete()
    ]
)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $synopsys = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Assert\Positive]
    #[Assert\Length(min:4, max:4)]
    private ?int $YearPublished = null;

    #[ORM\Column(length: 255)]
    #[Assert\Isbn()]
    #[Assert\NotBlank()]
    private ?string $ISBN = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Assert\Positive]
    private ?int $nbPage = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genre $genre = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Editor $editor = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Language $language = null;

    #[ORM\OneToMany(targetEntity: BookCopy::class, mappedBy: 'book')]
    private Collection $bookCopies;

    public function __construct()
    {
        $this->bookCopies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSynopsys(): ?string
    {
        return $this->synopsys;
    }

    public function setSynopsys(string $synopsys): static
    {
        $this->synopsys = $synopsys;

        return $this;
    }

    public function getYearPublished(): ?int
    {
        return $this->YearPublished;
    }

    public function setYearPublished(int $YearPublished): static
    {
        $this->YearPublished = $YearPublished;

        return $this;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): static
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getNbPage(): ?int
    {
        return $this->nbPage;
    }

    public function setNbPage(int $nbPage): static
    {
        $this->nbPage = $nbPage;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getEditor(): ?Editor
    {
        return $this->editor;
    }

    public function setEditor(?Editor $editor): static
    {
        $this->editor = $editor;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): static
    {
        $this->language = $language;

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
            $bookCopy->setBook($this);
        }

        return $this;
    }

    public function removeBookCopy(BookCopy $bookCopy): static
    {
        if ($this->bookCopies->removeElement($bookCopy)) {
            // set the owning side to null (unless already changed)
            if ($bookCopy->getBook() === $this) {
                $bookCopy->setBook(null);
            }
        }

        return $this;
    }
}
