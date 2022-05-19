<?php

namespace App\Twig;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CategoryExtension extends AbstractExtension
{
    public function __construct(private CategoryRepository $categoryRepository)
    {
       
    }


    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('category', [$this, 'getParCategory']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getParentCategory', [$this, 'getParentCategory']),
        ];
    }

    public function getParentCategory(): array
    {
       $categoryEntities = $this->categoryRepository->getParentCategories();
       return $categoryEntities;
    }

}
