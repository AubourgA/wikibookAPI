<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    paginationItemsPerPage: 12,
    paginationMaximumItemsPerPage:12,
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['read:book:collection','read:book:global'] ]),
        new Post( security: "is_granted('ROLE_ADMIN')", 
                 denormalizationContext: ['groups'=>'write:book:collection'],
                 inputFormats:['multipart' => ['multipart/form-data']]),
        new Get(normalizationContext: ['groups' => ['read:book:item','read:book:global'] ]),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
    ]
)]

#[ApiFilter(OrderFilter::class, properties: ['title' => 'ASC', 'createdAt' => 'ASC'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'ISBN' => 'partial', 'YearPublished' => 'exact', 'genre.name' => 'exact','author.name' => 'partial'])]
#[UniqueEntity(fields: ['ISBN'], message: 'ISBN déja utilisé')]
#[Vich\Uploadable]
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
            'read:loan:collection',
            'read:bookcopy:collection',
            'read:bookcopy:item',
            'read:author:item',
            'write:book:collection',
            'read:user:item'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Assert\Length(max:750, maxMessage: 'Le message ne doit pas dépasser {{ limit }} caracteres')]
    #[Groups(['read:book:collection','read:book:item','write:book:collection'])]
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
                'read:book:collection',
                'read:book:item','write:book:collection'],
    ),
    Valid()]
    private ?Author $author = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:book:collection','read:book:item','write:book:collection']),
    Valid()]
    private ?Genre $genre = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:book:item','write:book:collection']),
    Valid()]
    private ?Editor $editor = null;

 

    #[ORM\OneToMany(targetEntity: BookCopy::class, mappedBy: 'book', cascade: ['remove'])]
    #[Groups(['read:book:item','read:book:collection'])]
    private Collection $bookCopies;

    #[ORM\Column]
    #[ApiFilter(BooleanFilter::class, properties: ['isOnLine'])]
    #[Groups(['write:book:collection', 'read:book:global'])]
    private ?bool $isOnLine = null;

    #[ORM\Column]
    #[Groups(['read:book:collection','read:book:item'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Vich\UploadableField(mapping: 'book', fileNameProperty: 'imageName')]
    #[Groups(['write:book:collection'])]
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Veuillez uploader une image au format JPG ou PNG.'
    )]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    public function __construct()
    {
        $this->bookCopies = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
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

    public function isIsOnLine(): ?bool
    {
        return $this->isOnLine;
    }

    public function setIsOnLine(bool $isOnLine): static
    {
        $this->isOnLine = $isOnLine;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

     /**
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    #[ApiProperty(readable: true)]
    #[Groups(['read:book:item','read:book:collection'])]
    public function getContentUrl(): ?string
    {
        return $this->imageName 
            ? '/media/' . $this->imageName 
            : null;
    }
}
