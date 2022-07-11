<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UserCountController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    normalizationContext:['groups'=>'userSerialization'],
    collectionOperations:['get','post',
    'getTotalUserFromDates'=>[
        'method'=>'GET',
        'path'=>'/user/getTotalUser',
        'controller'=>UserCountController::class]
    ],
    itemOperations:['get']
)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['adressSerialization','commandSerialization','reviewSerializtation','userSerialization'])]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email(
        message: 'L\'adresse email {{ value }} n\'est pas valide.',
    )]
    #[Groups(['adressSerialization','commandSerialization','reviewSerializtation','userSerialization'])]
    private $email;

    #[ORM\Column(type: 'json')]
    // #[Groups(['userSerialization'])]
    private $roles = [];

    #[ORM\Column(type: 'string')]
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
    #[Groups(['adressSerialization','commandSerialization','reviewSerializtation','userSerialization'])]
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
      message:'Le nom doit être une chaine de caractère'
    )]
    #[Groups(['adressSerialization','commandSerialization','reviewSerializtation','userSerialization'])]
    private $lastName;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Type(
        type:'datetime',
        message: 'La date d\'anniversaire n\'est pas au bon format'
    ),
    Assert\LessThan('today UTC')
    ]
    #[Groups(['adressSerialization','commandSerialization','reviewSerializtation','userSerialization'])]
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
    #[Groups(['adressSerialization','commandSerialization','reviewSerializtation','userSerialization'])]
    private $gender;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
    #[Groups(['userSerialization'])]
    private $reviews;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Command::class)]
    #[Groups(['userSerialization'])]
    private $commands;

    #[ORM\ManyToMany(targetEntity: Adress::class, mappedBy: 'users')]
    #[Groups(['userSerialization'])]
    private $adresses;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['adressSerialization','commandSerialization','reviewSerializtation','userSerialization'])]
    private $createdAt;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ResetPassword::class)]
    private $resetPasswords;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->commands = new ArrayCollection();
        $this->adresses = new ArrayCollection();
        $this->resetPasswords = new ArrayCollection();
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

    public function getUsername(): string
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

    /**
     * @return Collection<int, ResetPassword>
     */
    public function getResetPasswords(): Collection
    {
        return $this->resetPasswords;
    }

    public function addResetPassword(ResetPassword $resetPassword): self
    {
        if (!$this->resetPasswords->contains($resetPassword)) {
            $this->resetPasswords[] = $resetPassword;
            $resetPassword->setUser($this);
        }

        return $this;
    }

    public function removeResetPassword(ResetPassword $resetPassword): self
    {
        if ($this->resetPasswords->removeElement($resetPassword)) {
            // set the owning side to null (unless already changed)
            if ($resetPassword->getUser() === $this) {
                $resetPassword->setUser(null);
            }
        }

        return $this;
    }
}