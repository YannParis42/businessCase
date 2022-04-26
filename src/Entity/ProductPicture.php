<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductPictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductPictureRepository::class)]
#[ApiResource(
    normalizationContext:['groups'=>'productPictureSerialization'],
    collectionOperations:['get','post'],
    itemOperations:['get']
)]
class ProductPicture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['productSerialization', 'productPictureSerialization'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(allowNull:FALSE),
    Assert\Type(
        type:'string',
        message:'Le chemin de l\'image doit être une chaine de caractère'
    )]
    #[Groups(['productSerialization', 'productPictureSerialization'])]
    private $path;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(allowNull:FALSE),
      Assert\Length(
        min: 2,
        max: 150,
        minMessage: 'Le nom d\'image est trop court ({{ limit }})',
        maxMessage: 'Le nom d\'image est trop long ({{ limit }})',
      ),
      Assert\Type(
        type:'string',
        message:'Le libele doit être une chaine de caractère'
    )
    ]
    #[Groups(['productSerialization', 'productPictureSerialization'])]
    private $libele;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'productPictures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['productPictureSerialization'])]
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getLibele(): ?string
    {
        return $this->libele;
    }

    public function setLibele(string $libele): self
    {
        $this->libele = $libele;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
