<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminBrandController extends AbstractController
{
public function __construct(private BrandRepository $brandRepository, private EntityManagerInterface $em)
{
    
}

    #[Route('/admin/brand', name: 'app_admin_brand')]
    public function index(): Response
    {
        $brand = $this->brandRepository->findAll();

        return $this->render('admin_brand/index.html.twig', [
            'controller_name' => 'AdminBrandController',
            'brands' =>$brand,
        ]);
    }

    #[Route('/admin/brand/ajouter', name: 'app_admin_brand_add')]
    public function add(Request $request, SluggerInterface $slugger): Response
    {
        $brandEntity = new Brand;
        $form = $this->createForm(BrandType::class, $brandEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid()) {
            $image = $form->get('imagePath')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('image_brand_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $brandEntity->setImagePath($newFilename);

            }
            

            $this->em->persist($brandEntity);
            $this->em->flush();
            return $this->redirectToRoute('app_admin_brand');
        }

        return $this->render('admin_brand/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/brand/modifier/{label}', name: 'app_admin_brand_modify')]
    public function modify(string $label, Request $request, SluggerInterface $slugger): Response
    {
        $brandEntity = $this->brandRepository->findOneBy(['label'=>$label]);
        $form = $this->createForm(BrandType::class, $brandEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imagePath')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('image_brand_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $brandEntity->setImagePath($newFilename);

            }

            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirectToRoute('app_admin_brand');
        }
    
        return $this->render('admin_brand/modify.html.twig', [
            'form' => $form->createView(),
            'brand' => $brandEntity
        ]);
    }

    #[Route('/admin/brand/delete/{id}', name: 'app_admin_brand_delete')]
    public function delete(int $id): Response
    {
        $brandEntity = $this->brandRepository->find($id);
        $this->em->remove($brandEntity);
        $this->em->flush();
    
        return $this->redirectToRoute('app_admin_brand');
    }
}


