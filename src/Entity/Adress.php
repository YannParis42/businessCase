<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
#[ApiResource()]
class Adress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Positive(
        message:'Le numéro doit être positif'
    ),
    Assert\Type(
        type:'string',
        message:'Le numéro de rue doit être une chaine de caractère'
    )]
    private $streetNumber;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Length(
      min: 2,
      max: 150,
      minMessage: 'Le nom de la rue est trop court ({{min}})',
      maxMessage: 'Le nom de la rue est trop long ({{max}})',
    ),
    Assert\Type(
        type: 'string',
        message: 'Le nom de rue doit être une chaine de caractère')]
    private $streetName;

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'adresses')]
    #[ORM\JoinColumn(nullable: false)]
    private $city;

    #[ORM\OneToMany(mappedBy: 'adress', targetEntity: Command::class)]
    private $commands;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'adresses')]
    private $users;

    public function __construct()
    {
        $this->commands = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(string $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

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
            $command->setAdress($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            // set the owning side to null (unless already changed)
            if ($command->getAdress() === $this) {
                $command->setAdress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
