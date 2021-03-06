<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    normalizationContext:['groups'=>'categorySerialization'],
    collectionOperations:['get','post'],
    itemOperations:['get']
)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['productSerialization','categorySerialization'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(allowNull:FALSE),
      Assert\Length(
      min: 2,
      max: 100,
      minMessage: 'Le nom de categorie est trop court ({{ limit }})',
      maxMessage: 'Le nom de categorie est trop long ({{ limit }})'),
      Assert\Type(
        type:'string',
        message:'Le nom de catégorie doit être une chaine de caractère'
    )]
    #[Groups(['productSerialization','categorySerialization'])]
    private $label;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'categories')]
    #[Groups(['categorySerialization'])]
    private $products;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories')]
    private $categoryParent;

    #[ORM\OneToMany(mappedBy: 'categoryParent', targetEntity: self::class)]
    private $categories;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    public function getCategoryParent(): ?self
    {
        return $this->categoryParent;
    }

    public function setCategoryParent(?self $categoryParent): self
    {
        $this->categoryParent = $categoryParent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(self $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setCategoryParent($this);
        }

        return $this;
    }

    public function removeCategory(self $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCategoryParent() === $this) {
                $category->setCategoryParent(null);
            }
        }

        return $this;
    }

    
}
