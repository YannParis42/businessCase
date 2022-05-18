<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductPicture;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository, private EntityManagerInterface $em)
    {
    }

    #[Route('/admin/product', name: 'app_admin_product')]
    public function index(): Response
    {
        $product = $this->productRepository->findAll();

        return $this->render('admin_product/index.html.twig', [
            'controller_name' => 'AdminProductController',
            'products' => $product,
        ]);
    }

    #[Route('/admin/product/ajouter', name: 'app_admin_product_add')]
    public function add(Request $request, SluggerInterface $slugger): Response
    {
        $productEntity = new Product();
        $form = $this->createForm(ProductType::class, $productEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('image_product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $productPictureEntity = new ProductPicture();
                $productPictureEntity->setPath($newFilename);
                $productPictureEntity->setLibele($originalFilename);
                $productEntity->addProductPicture($productPictureEntity);
            }
            

            $this->em->persist($productEntity);
            $this->em->flush();
            return $this->redirectToRoute('app_admin_product');
        }

        return $this->render('admin_product/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/product/modifier/{label}', name: 'app_admin_product_modify')]
    public function modify(string $label, Request $request, SluggerInterface $slugger): Response
    {
        $productEntity = $this->productRepository->findOneBy(['label'=>$label]);
        $form = $this->createForm(ProductType::class, $productEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('image_product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $productPictureEntity = $productEntity->getProductPictures()[0];
                $productPictureEntity->setPath($newFilename);
                $productPictureEntity->setLibele($originalFilename);
                $productEntity->addProductPicture($productPictureEntity);
            }


            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirectToRoute('app_admin_product');
        }
    
        return $this->render('admin_product/modify.html.twig', [
            'form' => $form->createView(),
            'product' => $productEntity
        ]);
    }

    #[Route('/admin/product/delete/{id}', name: 'app_admin_product_delete')]
    public function delete(int $id): Response
    {
        $productEntity = $this->productRepository->find($id);
        $this->em->remove($productEntity);
        $this->em->flush();
    
        return $this->redirectToRoute('app_admin_product');
    }
}


