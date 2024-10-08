<?php

namespace App\Entity;




use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LoanRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LoanRepository::class)]
#[ApiResource(
    paginationItemsPerPage: 10,
    paginationMaximumItemsPerPage:10,
    operations: [
        new GetCollection( security: "is_granted('ROLE_ADMIN')", normalizationContext:['groups'=>'read:loan:collection']),
        new Post(security: "is_granted('ROLE_USER')"),
        new Get( security: "is_granted('ROLE_USER') and object.getUser() == user", normalizationContext:['groups'=>'read:loan:item']),
        new Patch( security: "is_granted('ROLE_ADMIN') or object.getUser() == user", denormalizationContext:['groups'=> 'write:loan:item'])
    ]
)]
#[ApiFilter(ExistsFilter::class, properties: ['returnDate'])]
class Loan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:loan:collection','read:loan:item'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['read:loan:collection','read:loan:item','read:bookcopy:item','read:user:item','read:book:item'])]
    #[Assert\LessThanOrEqual('tomorrow')]
    private ?\DateTimeInterface $borrowDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['write:loan:item','read:loan:item','read:loan:collection','read:bookcopy:item','read:user:item'])]
    #[Assert\LessThanOrEqual('tomorrow')]
    #[Assert\Expression(
        "this.getReturnDate() === null or this.getReturnDate() > this.getBorrowDate()",
        message : "La date de retour doit être postérieure à la date d'emprunt."
    )]
    private ?\DateTimeInterface $returnDate = null;

    #[ORM\ManyToOne(inversedBy: 'loans')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:loan:item','read:loan:collection','write:loan:item','read:user:item'])]
    private ?BookCopy $bookCopy = null;

    #[ORM\ManyToOne(inversedBy: 'loans')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:loan:collection','read:loan:item','read:bookcopy:item','read:book:item'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorrowDate(): ?\DateTimeInterface
    {
        return $this->borrowDate;
    }

    public function setBorrowDate(?\DateTimeInterface $borrowDate): static
    {
        $this->borrowDate = $borrowDate;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setReturnDate(?\DateTimeInterface $returnDate): static
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    public function getBookCopy(): ?BookCopy
    {
        return $this->bookCopy;
    }

    public function setBookCopy(?BookCopy $bookCopy): static
    {
        $this->bookCopy = $bookCopy;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
