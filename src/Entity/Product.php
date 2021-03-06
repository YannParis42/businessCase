<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\BestSellingProductController;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    normalizationContext:['groups'=>'productSerialization'],
    collectionOperations:['get','post',
    'getTotalProductSoldDesc'=>[  //meilleurs produits vendus
        'method'=>'GET',
        'path'=>'/product/getTotalProductSoldDesc',
        'controller'=>BestSellingProductController::class],],
    itemOperations:['get']
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['commandSerialization', 'productSerialization','brandSerialization', 'productPictureSerialization','categorySerialization','reviewSerializtation'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Length(
    min: 2,
    max: 150,
    minMessage: 'Le nom du produit est trop court ({{ limit }})',
    maxMessage: 'Le nom du produit est trop long ({{ limit }})'),
    Assert\Type(
        type:'string',
        message:'Le nom du produit doit être une chaine de caractère'
    )]
    #[Groups(['commandSerialization', 'productSerialization','brandSerialization', 'productPictureSerialization','categorySerialization','reviewSerializtation'])]
    private $label;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(allowNull:FALSE)]
    #[Groups(['commandSerialization', 'productSerialization','brandSerialization', 'productPictureSerialization','categorySerialization','reviewSerializtation'])]
    private $description;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Positive(
        message:'le prix doit être positif'
    ),
    Assert\Type(
        type:'integer',
        message:'Le prix doit être un nombre'
    )]
    #[Groups(['commandSerialization', 'productSerialization','brandSerialization', 'productPictureSerialization','categorySerialization','reviewSerializtation'])]
    private $price;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\PositiveOrZero,
    Assert\Type(
        type:'integer',
        message:'Le stock doit être un nombre'
    )]
    #[Groups(['commandSerialization', 'productSerialization','brandSerialization', 'productPictureSerialization','categorySerialization','reviewSerializtation'])]
    private $stock;

    #[ORM\Column(type: 'boolean')]
    #[Assert\NotBlank(allowNull:FALSE)]
    #[Groups(['commandSerialization', 'productSerialization','brandSerialization', 'productPictureSerialization','categorySerialization','reviewSerializtation'])]
    private $isActif;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    #[Groups(['productSerialization'])]
    private $categories;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductPicture::class, cascade:['persist', 'remove'])]
    #[Groups(['productSerialization'])]
    private $productPictures;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['productSerialization'])]
    private $brand;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Review::class)]
    #[Groups(['productSerialization'])]
    private $reviews;

    #[ORM\ManyToMany(targetEntity: Command::class, mappedBy: 'products')]
    #[Groups(['productSerialization'])]
    private $commands;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->productPictures = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->commands = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getIsActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): self
    {
        $this->isActif = $isActif;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductPicture>
     */
    public function getProductPictures(): Collection
    {
        return $this->productPictures;
    }

    public function addProductPicture(ProductPicture $productPicture): self
    {
        if (!$this->productPictures->contains($productPicture)) {
            $this->productPictures[] = $productPicture;
            $productPicture->setProduct($this);
        }

        return $this;
    }

    public function removeProductPicture(ProductPicture $productPicture): self
    {
        if ($this->productPictures->removeElement($productPicture)) {
            // set the owning side to null (unless already changed)
            if ($productPicture->getProduct() === $this) {
                $productPicture->setProduct(null);
            }
        }

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

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
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
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
            $command->addProduct($this);
        }

        return $this;
    }

    public function removeCommand(Command $command): self
    {
        if ($this->commands->removeElement($command)) {
            $command->removeProduct($this);
        }

        return $this;
    }
}
