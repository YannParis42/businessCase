<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
#[ApiResource()]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Positive(
        message: 'Le total doit être positive'
    ),
    Assert\Type(
        type:'integer',
        message:'Le total doit être un nombre'
    )
    ]
    private $totalPrice;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Positive(
        message: 'Le numéro de commande doit être positive'
    ),
    Assert\Type(
        type:'integer',
        message:'Le numéro de commande doit être un nombre'
    )
    ]
    private $numCommand;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Type(
        type:'datetime',
        message: 'La date n\'est pas au bon format'
    ),
    ]
    private $createdAt;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Positive(
        message: 'Le status de commande doit être positif'
    ),
    Assert\Type(
        type:'integer',
        message:'Le status de commande doit être un nombre'
    )
    ]
    private $status;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'commands')]
    private $products;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: Adress::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    private $adress;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalPrice(): ?int
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(int $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getNumCommand(): ?int
    {
        return $this->numCommand;
    }

    public function setNumCommand(int $numCommand): self
    {
        $this->numCommand = $numCommand;

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): self
    {
        $this->adress = $adress;

        return $this;
    }
}
