<?php

namespace App\Twig;

use App\Entity\Command;
use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CommandExtension extends AbstractExtension
{
    public function __construct(private ProductRepository $productRepository){}

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('totalProduct', [$this, 'getTotalbasket']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function getTotalbasket(Command $command){
        $productsEntities =  $command->getProducts();
        $total = 0;

        foreach($productsEntities as $product){
            $total = $total +$product->getPrice();
        }

        return $total;
    }
}
