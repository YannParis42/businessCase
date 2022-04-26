<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ApiResource(
    normalizationContext:['groups'=>['citySerialization']],
    collectionOperations:['get','post'],
    itemOperations:['get']
)]
#[ApiFilter(SearchFilter::class, properties:['id'=>'exact', 'name'=>'partial', 'cp'=>'exact'] )]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['citySerialization'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(allowNull:FALSE),
      Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le nom de ville est trop court ({{ limit }})',
        maxMessage: 'Le nom de ville est trop long ({{ limit }})',
      ),
      Assert\Type(
        type:'string',
        message:'Le nom de ville doit être une chaine de caractère'
    )]
    #[Groups(['citySerialization'])]
    private $name;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(),
    Assert\Positive(
        message: 'Le code postal doit être positif'
    ),
    Assert\Type(
        type:'integer',
        message:'Le code postal doit être un nombre'
    )]
    #[Groups(['citySerialization'])]
    private $cp;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Adress::class)]
    #[Groups(['citySerialization'])]
    private $adresses;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(int $cp): self
    {
        $this->cp = $cp;

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
            $adress->setCity($this);
        }

        return $this;
    }

    public function removeAdress(Adress $adress): self
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getCity() === $this) {
                $adress->setCity(null);
            }
        }

        return $this;
    }
}
