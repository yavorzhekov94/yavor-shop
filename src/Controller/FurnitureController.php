<?php

namespace App\Controller;

use App\Entity\Furniture;
use App\Form\FurnitureType;
use App\Repository\FurnitureRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/furniture', name: 'furniture.')]
class FurnitureController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function index(FurnitureRepository $furniture): Response
    {
        $products = $furniture->findAll();
        return $this->render('furniture/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, ManagerRegistry $doctrine) {
        $furniture = new Furniture();
        $form = $this->createForm(FurnitureType::class, $furniture);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $image = $request->files->get('furniture')['image'];
            if ($image) {
                $dateName = md5(uniqid()). '.'. $image->guessClientExtension();
                $image->move($this->getParameter('image_folder'), $dateName);
                $furniture->setImage($dateName);
            }
            $em->persist($furniture);
            $em->flush();
            $this->addFlash('success','The product has been created');
            return $this->redirect($this->generateUrl('furniture.products'));
        }
         return $this->render('furniture/create.html.twig', [
            'createForm' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, FurnitureRepository $fr, ManagerRegistry $doctrine) {
        $em = $doctrine->getManager();
        $product = $fr->find($id);
        $em->remove($product);
        $em->flush();
        $this->addFlash('success','The product with id "'.$id.'" has been deleted');
        return $this->redirect($this->generateUrl('furniture.products'));
    }

    #[Route('/detail/{id}', name: 'detail')]
    public function detail(Furniture $furniture) {
        return $this->render('furniture/detail.html.twig', [
            'product' => $furniture,
        ]);
    }
}
