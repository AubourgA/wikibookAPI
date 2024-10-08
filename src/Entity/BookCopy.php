<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\BookCopyRepository;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookCopyRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection( security: "is_granted('ROLE_ADMIN')", normalizationContext: ['groups'=>'read:bookcopy:collection']),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Get( security: "is_granted('ROLE_USER')",normalizationContext: ['groups'=>'read:bookcopy:item']),
        new Patch( security: "is_granted('ROLE_USER')", denormalizationContext: ['groups'=> 'write:bookcopy:item']),
        new Delete(security: "is_granted('ROLE_ADMIN')")
    ]
)]
class BookCopy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:bookcopy:collection','read:bookcopy:item','read:book:item','read:loan:collection'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookCopies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:bookcopy:collection',
            'read:bookcopy:item',
            'read:status:item',
            'read:loan:item', 'read:loan:collection',
            'read:user:item'])]
    private ?Book $book = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank()]
    #[Groups(['read:bookcopy:collection','read:bookcopy:item','read:book:item'])]
    #[Assert\LessThanOrEqual('today')]
    private ?\DateTimeInterface $serviceDate = null;

    #[ORM\ManyToOne(inversedBy: 'bookCopies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank()]
    #[Groups(['read:loan:item','read:bookcopy:item','read:book:item','read:book:collection','write:bookcopy:item'])]
    private ?Status $status = null;

    #[ORM\OneToMany(targetEntity: Loan::class, mappedBy: 'bookCopy')]
    #[Groups(['read:bookcopy:item','read:book:item'])]
    private Collection $loans;

    #[ORM\ManyToOne(inversedBy: 'bookCopies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:book:item','read:user:item'])]
    private ?Language $Language = null;

    public function __construct()
    {
        $this->loans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getServiceDate(): ?\DateTimeInterface
    {
        return $this->serviceDate;
    }

    public function setServiceDate(\DateTimeInterface $serviceDate): static
    {
        $this->serviceDate = $serviceDate;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Loan>
     */
    public function getLoans(): Collection
    {
        return $this->loans;
    }

    public function addLoan(Loan $loan): static
    {
        if (!$this->loans->contains($loan)) {
            $this->loans->add($loan);
            $loan->setBookCopy($this);
        }

        return $this;
    }

    public function removeLoan(Loan $loan): static
    {
        if ($this->loans->removeElement($loan)) {
            // set the owning side to null (unless already changed)
            if ($loan->getBookCopy() === $this) {
                $loan->setBookCopy(null);
            }
        }

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->Language;
    }

    public function setLanguage(?Language $Language): static
    {
        $this->Language = $Language;

        return $this;
    }
}
