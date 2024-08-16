<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\NationalityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NationalityRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection( normalizationContext: ['groups' => 'read:nationalities:collection']),
        new Get( normalizationContext:['groups'=> 'read:nationalities:item']),
        new Post(),
        new Patch( denormalizationContext:['groups' => 'write:nationalities:item'])
    ]
)]
class Nationality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:author:item','read:nationalities:collection','read:nationalities:item','write:nationalities:item'])] 
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\s]+$/',
        match:true,
        message: 'Le champs doit etre que des lettres')]
    private ?string $country = null;

    #[ORM\OneToMany(targetEntity: Author::class, mappedBy: 'nationality')]
    #[Groups(['read:nationalities:item'])]
    private Collection $authors;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->setNationality($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        if ($this->authors->removeElement($author)) {
            // set the owning side to null (unless already changed)
            if ($author->getNationality() === $this) {
                $author->setNationality(null);
            }
        }

        return $this;
    }
}
