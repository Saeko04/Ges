<?php
namespace App\Controller\Admin;

use App\Entity\Sejour;
use App\Form\SejourType;
use App\Repository\SejourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/sejour')]
class SejourController extends AbstractController
{
    #[Route('/', name: 'gestion_sejours')]
    public function index(SejourRepository $repo): Response
    {
        $sejours = $repo->findAll();

        return $this->render('admin/sejour/index.html.twig', [
            'sejours' => $sejours
        ]);
    }

    #[Route('/new', name: 'sejour_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $sejour = new Sejour();
        $form = $this->createForm(SejourType::class, $sejour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($sejour);
            $em->flush();
            $this->addFlash('success', 'Séjour ajouté !');
            return $this->redirectToRoute('gestion_sejours');
        }

        return $this->render('admin/sejour/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'sejour_edit')]
    public function edit(Sejour $sejour, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SejourType::class, $sejour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Séjour modifié !');
            return $this->redirectToRoute('gestion_sejours');
        }

        return $this->render('admin/sejour/edit.html.twig', [
            'form' => $form->createView(),
            'sejour' => $sejour
        ]);
    }
}

