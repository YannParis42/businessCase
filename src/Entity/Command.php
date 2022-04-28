<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\AbandonCountController;
use App\Controller\BasketCountController;
use App\Controller\CommandCountController;
use App\Controller\ConversionCountController;
use App\Controller\ConversionPaniersController;
use App\Controller\RecurrenceController;
use App\Controller\ValueBasketController;
use App\Controller\VenteCountController;
use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommandRepository::class)]
#[ApiResource(
    normalizationContext:['groups'=>'commandSerialization'],
    collectionOperations:['get','post',
    'getTotalCommandFromDates'=>[  //nombres de commandes
        'method'=>'GET',
        'path'=>'/command/getTotalCommand',
        'controller'=>CommandCountController::class],
    'getTotalBasketFromDates'=>[  //nombres de panier
        'method'=>'GET',
        'path'=>'/command/getTotalBasket',
        'controller'=>BasketCountController::class],
    'getTotalVenteFromDates'=>[  //total des ventes en valeurs
        'method'=>'GET',
        'path'=>'/command/getTotalVente',
        'controller'=>VenteCountController::class],
    'getTotalConversionFromDates'=>[ //pourcentage conversion panier
        'method'=>'GET',
        'path'=>'/command/getTotalConversion',
        'controller'=>ConversionCountController::class],
    'getBasketValueFromDates'=>[ //valeurs moyen panier
        'method'=>'GET',
        'path'=>'/command/getValueBasket',
        'controller'=>ValueBasketController::class],
    'getBasketValueFromDates'=>[ //valeurs conversion panier
        'method'=>'GET',
        'path'=>'/command/convPanier',
        'controller'=>ConversionPaniersController::class],
        'getRecurrenceFromDates'=>[ //% recurrence commandes
            'method'=>'GET',
            'path'=>'/command/Recurrence',
            'controller'=>RecurrenceController::class],
    'getTotalAbandonFromDates'=>[ //pourcentage abandon panier
            'method'=>'GET',
            'path'=>'/command/getAbandon',
            'controller'=>AbandonCountController::class]             
           
    ],   
    
    itemOperations:['get']
)]
#[ApiFilter(DateFilter::class, properties:['createdAt'],)]
#[ApiFilter(SearchFilter::class, properties:['user.firstName'=>'exact'])]
class Command
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['adressSerialization','commandSerialization', 'productSerialization','userSerialization'])]
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
    #[Groups(['adressSerialization','commandSerialization', 'productSerialization','userSerialization'])]
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
    #[Groups(['adressSerialization','commandSerialization', 'productSerialization','userSerialization'])]
    private $numCommand;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Type(
        type:'datetime',
        message: 'La date n\'est pas au bon format'
    ),
    ]
    #[Groups(['adressSerialization','commandSerialization', 'productSerialization','userSerialization'])]
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
    #[Groups(['adressSerialization','commandSerialization', 'productSerialization','userSerialization'])]
    private $status;

    #[ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'commands')]
    #[Groups(['commandSerialization'])]
    private $products;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['commandSerialization'])]
    private $user;

    #[ORM\ManyToOne(targetEntity: Adress::class, inversedBy: 'commands')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['commandSerialization'])]
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
