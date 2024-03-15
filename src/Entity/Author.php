<?php

namespace App\Entity;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuthorRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ApiResource(
    paginationItemsPerPage: 10,
    paginationMaximumItemsPerPage:10,
    operations: [
        new GetCollection( normalizationContext:['groups' => ['read:author:collection']
             ]
        ),
        new Get(normalizationContext:['groups' => ['read:author:item']                                                   
            ]
        ),
        new Post(
            denormalizationContext:['groups' => ['create:author:collection'] ]                                         
        )
    ]

)]

#[UniqueEntity(fields: ['name', 'firstname'], message: 'Le nom et le prénom doivent être uniques.')]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:author:collection','read:author:item'])] 
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:author:collection',
              'read:author:item',
              'create:author:collection',
              'read:editor:item',
              'read:genre:item',
              'read:language:item',
              'read:nationalities:item',
              'read:book:item',
              'write:book:collection'
            ]),
      Length(min:3)
    ]  
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        match:true,
        message: 'Le champs doit etre que des lettres')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        match:true,
        message: 'Le champs doit etre que des lettres')]
    #[Groups(['read:author:collection',
              'read:author:item',
              'create:author:collection',
              'read:editor:item',
              'read:genre:item',
              'read:language:item',
              'read:nationalities:item',
              'read:book:item','write:book:collection'])] 
    private ?string $firstname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['read:author:item','create:author:collection'])] 
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\ManyToOne(inversedBy: 'authors')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:author:item','create:author:collection'])] 
    private ?Nationality $nationality = null;

    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'author')]
    #[Groups(['read:author:item'])]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getNationality(): ?Nationality
    {
        return $this->nationality;
    }

    public function setNationality(?Nationality $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }
}
