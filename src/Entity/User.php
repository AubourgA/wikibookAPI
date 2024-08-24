<?php

namespace App\Entity;

use DateTimeImmutable;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\State\UserPasswordHasher;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_ADMIN')", normalizationContext: ['groups' => ['read:user:collection','read:user:item'] ]),
        new Post(processor: UserPasswordHasher::class, denormalizationContext: ['groups' => ['create:user:item']]),
        new Get(security: "is_granted('ROLE_USER') and object.isUser()", normalizationContext: ['groups' => ['read:user:item'] ]),
        new Put(security: "is_granted('ROLE_USER') and object.isUser()", denormalizationContext:['groups' => ['write:user:item']]),
        new Delete(security: "is_granted('ROLE_ADMIN')",)
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:user:collection','read:user:item' ])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email(
        message: 'La valeur : {{ value }} n\' est pas un email valide.',
    )]
    #[Groups(['read:user:collection','read:user:item', 'write:user:item','create:user:item' ])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['read:user:item'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(('create:user:item'))]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\s]+$/',
        match:true,
        message: 'Le champs doit etre que des lettres')]
    #[Groups(['read:loan:collection','read:loan:item','read:bookcopy:item','read:user:item','create:user:item'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\s]+$/',
        match:true,
        message: 'Le champs doit etre que des lettres')]
    #[Groups(['read:loan:collection','read:loan:item','read:bookcopy:item','read:user:item','create:user:item'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Regex(
        pattern: '/^(?:\+33|0)[1-9](?:\d\s?){8}$/',
        match:true,
        message: 'Le champs doit etre que de type numero de telehpone')]
    #[Groups(['read:user:item', 'write:user:item','create:user:item' ])]
    private ?string $numPortable = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\s]+$/',
        match:true,
        message: 'Le champs doit etre que des lettres')]
    #[Groups(['read:user:item', 'write:user:item','create:user:item' ])]   
    private ?string $city = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Groups(['read:user:collection'])]
    private ?\DateTimeImmutable $subscribedAt = null;

    #[ORM\Column]
    #[Groups(['read:user:collection'])]
    private ?bool $isActive = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\OneToMany(targetEntity: Loan::class, mappedBy: 'user')]
    private Collection $loans;

    public function __construct(private ?Security $security = null)
    {
        $this->isActive = 1;
        $this->loans = new ArrayCollection();
        $this->subscribedAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getNumPortable(): ?string
    {
        return $this->numPortable;
    }

    public function setNumPortable(?string $numPortable): static
    {
        $this->numPortable = $numPortable;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getSubscribedAt(): ?\DateTimeImmutable
    {
        return $this->subscribedAt;
    }

    public function setSubscribedAt(\DateTimeImmutable $subscribedAt): static
    {
        $this->subscribedAt = $subscribedAt;

        return $this;
    }

    public function isUser(?UserInterface $user = null): bool
    {
        return $user instanceof self && $user->id === $this->id;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

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
            $loan->setUser($this);
        }

        return $this;
    }

    public function removeLoan(Loan $loan): static
    {
        if ($this->loans->removeElement($loan)) {
            // set the owning side to null (unless already changed)
            if ($loan->getUser() === $this) {
                $loan->setUser(null);
            }
        }

        return $this;
    }
}
