<?php

namespace App\Service;

use App\Entity\Command;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CommandRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class BasketService 
{
    public function __construct(private CommandRepository $commandRepository, private EntityManagerInterface $em)
    {
       
    }
   
    public function getBasket(User $user){
        $basketEntity = $this->commandRepository->getBasketByUser($user);

        if($basketEntity===null){
            $basketEntity = new Command();
            $basketEntity->setCreatedAt(new DateTime());
            $basketEntity->setNumCommand(uniqid());
            $basketEntity->setTotalPrice(0);
            $basketEntity->setStatus(100);
            $basketEntity->setUser($user);
            $this->em->persist($basketEntity);
            $this->em->flush();
        }

    return $basketEntity;
    }

    public function addProduct(User $user, Product $productEntity){
        $basketEntity = $this->getBasket($user);
        $basketEntity->addProduct($productEntity);
        $this->em->persist($basketEntity);
        $this->em->flush();

    }

    public function removeProduct(User $user, Product $productEntity){
        $basketEntity = $this->getBasket($user);
        $basketEntity->removeProduct($productEntity);
        $this->em->persist($basketEntity);
        $this->em->flush();
    }
}