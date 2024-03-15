<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use ApiPlatform\Metadata\ApiFilter;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    paginationItemsPerPage: 10,
    paginationMaximumItemsPerPage:10,
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'read:book:collection']),
        new Post(denormalizationContext: ['groups'=>'write:book:collection']),
        new Get(normalizationContext: ['groups' => 'read:book:item']),
        new Delete()
    ]
)]
#[ApiFilter(OrderFilter::class, properties: ['title' => 'ASC'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'YearPublished' => 'exact', 'genre.name' => 'exact','author.name' => 'partial'])]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:book:collection','read:book:item','read:author:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Groups(['read:book:collection',
            'read:book:item',
            'read:editor:item',
            'read:genre:item',
            'read:language:item',
            'read:status:item',
            'read:loan:item',
            'read:bookcopy:collection',
            'read:bookcopy:item',
            'read:author:item',
            'write:book:collection'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Assert\Length(max:750, maxMessage: 'Le message ne doit pas dépasser {{ limit }} caracteres')]
    #[Groups(['read:book:item','write:book:collection'])]
    private ?string $synopsys = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Assert\Positive]
    #[Assert\Length(min:4, max:4)]
    #[Groups(['read:book:collection','read:book:item','read:author:item','write:book:collection'])]
    private ?int $YearPublished = null;

    #[ORM\Column(length: 255)]
    #[Assert\Isbn(
        type: null,
        message: 'Le champs doit etre un isbn 10 ou 13 caracteres',
    )]
    #[Assert\NotBlank()]
    #[Groups(['read:book:collection','read:book:item','write:book:collection'])]
    private ?string $ISBN = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Assert\Positive]
    #[Groups(['read:book:item','write:book:collection'])]
    private ?int $nbPage = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:editor:item',
                'read:genre:item',
                'read:language:item',
                'read:book:item','write:book:collection'],
    ),
    Valid()]
    private ?Author $author = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:book:item','write:book:collection']),
    Valid()]
    private ?Genre $genre = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:book:item','write:book:collection']),
    Valid()]
    private ?Editor $editor = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank()]
    #[Groups(['read:book:item','write:book:collection']),
    Valid()]
    private ?Language $language = null;

    #[ORM\OneToMany(targetEntity: BookCopy::class, mappedBy: 'book', cascade: ['remove'])]
    #[Groups(['read:book:item'])]
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
