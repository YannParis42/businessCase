<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Twig\CategoryExtension;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository, private CategoryRepository $categoryRepository)
    {
    }

    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        $product = $this->productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $product,
        ]);
    }

    #[Route('/product/{id}', name: 'app_product_category')]
    public function showByCategory($id): Response
    {
        $categoryParent = $this->categoryRepository->find($id);

        return $this->render('product/byCategories.html.twig', [
            'categoryParent' => $categoryParent
        ]);
    }

    #[Route('/productDetail/{id}', name: 'productDetail')]
    public function findOne($id): Response
    {
        // $similarProducts = ($this->productRepository->getSimilarProduct($gameEntity));
        $product = $this->productRepository->find($id);
        dump($product);        

        return $this->render('product/productDetail.html.twig', [
            'product'=>$product,
            // 'similar'=> $similarProducts,
        ]);
    }
}



// #[Route('/genre/{slug}', name:'app_game_by_genre')]
// public function showByGenre($slug): Response
// {
//     $genreEntity = $this->genreRepository->getGamesByGenre($slug);

//     return $this->render('game/gameGenre.html.twig', [
//         'genre' => $genreEntity
//     ]);
// }