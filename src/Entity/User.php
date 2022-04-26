<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource()]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email(
        message: 'L\'adresse email {{ value }} n\'est pas valide.',
    )]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Length(
      min: 5,
      max: 100,
      minMessage: 'Le mot de passe est trop courtv({{ limit }})',
      maxMessage: 'Le mot de passe est trop longv({{ limit }})',
    ),
    Assert\Type(
      type:'string',
      message:'Le mot de passe doit être une chaine de caractère'
  )]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Length(
      min: 1,
      max: 100,
      minMessage: 'Le prénom est trop court ({{ limit }})',
      maxMessage: 'Le prénom est trop long ({{ limit }})',
    ),
    Assert\Type(
      type:'string',
      message:'Le prénom doit être une chaine de caractère'
  )]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Length(
      min: 1,
      max: 100,
      minMessage: 'Le nom est trop court ({{ limit }})',
      maxMessage: 'Le nom est trop long ({{ limit }})',
    ),
    Assert\Type(
      type:'string',
      message:'Le pnom doit être une chaine de caractère'
  )]
    private $lastName;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\DateTime(
        format:'d-m-Y',
        message: 'La date d\'anniversaire n\'est pas au bon format'
    ),
    Assert\LessThan(
        'today UTC',
        message:'La date d\'anniversaire doit inférieur à la date du jour')
    ]
    private $birthDate;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Length(
      min: 1,
      max: 100,
      minMessage: 'Le genre est trop court ({{ limit }})',
      maxMessage: 'Le genre est trop long ({{ limit }})',
    ),
    Assert\Type(
      type:'string',
      message:'Le genre doit être une chaine de caractère'
  )]
    private $gender;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
    private $reviews;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Command::class)]
    private $commands;

    #[ORM\ManyToMany(targetEntity: Adress::class, mappedBy: 'users')]
    private $adresses;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->commands = new ArrayCollection();
        $this->adresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Command>
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }

    public function addCommand(Command $command): self
    {
        if (!$this->commands->contains($command)) {
            $this->commands[] = $command;
            $command->setUser($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getUser() === $this) {
                $command->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Adress>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adress $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses[] = $adress;
            $adress->addUser($this);
        }

        return $this;
    }

    public function removeAdress(Adress $adress): self
    {
        if ($this->adresses->removeElement($adress)) {
            $adress->removeUser($this);
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
