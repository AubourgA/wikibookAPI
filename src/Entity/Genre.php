<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GenreRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Patch;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups'=> 'read:genre:collection']),
        new Get(normalizationContext: ['groups' => 'read:genre:item']),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'] )]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:genre:collection','read:genre:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min:4)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\s]+$/',
        match:true,
        message: 'Le champs doit etre que des lettres')]
    #[Groups(['read:genre:collection','read:genre:item','write:genre:item','read:book:collection','read:book:item'])]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Book::class, mappedBy: 'genre')]
    #[Groups(['read:genre:item'])]
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
            $book->setGenre($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getGenre() === $this) {
                $book->setGenre(null);
            }
        }

        return $this;
    }
}
