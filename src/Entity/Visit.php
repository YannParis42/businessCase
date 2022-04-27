<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\VisitCountController;
use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisitRepository::class)]
#[ApiResource(
    collectionOperations:['get','post',
        'getTotalVisitFromDates'=>[
            'method'=>'GET',
            'path'=>'/visit/getTotalVisit',
            'controller'=>VisitCountController::class
        ]
    ],
    itemOperations:['get']
)]
class Visit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $visitedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitedAt(): ?\DateTimeInterface
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(\DateTimeInterface $visitedAt): self
    {
        $this->visitedAt = $visitedAt;

        return $this;
    }
}
