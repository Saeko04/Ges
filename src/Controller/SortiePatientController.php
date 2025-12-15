<?php

namespace App\Controller;

use App\Entity\Sejour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiePatientController extends AbstractController
{
    #[Route('/infirmier/sortie-patient', name: 'sortie_patient')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        // Récupérer les séjours en cours (arrivés mais non sortis)
        $sejours = $em->getRepository(Sejour::class)->findBy([
            'arrive' => true,
            'sorti' => false
        ]);

        // Traitement formulaire de sortie
        if ($request->isMethod('POST')) {
            $sejourId = $request->request->get('sejour_id');
            $commentaire = $request->request->get('commentaire', null);

            $sejour = $em->getRepository(Sejour::class)->find($sejourId);

            if ($sejour) {
                $sejour->setSorti(true);
                if ($commentaire) {
                    $sejour->setCommentaire($commentaire);
                }
                $em->flush();

                $this->addFlash('success', 'Sortie du patient validée !');

                return $this->redirectToRoute('sortie_patient');
            }
        }

        return $this->render('sortie_patient/index.html.twig', [
            'sejours' => $sejours
        ]);
    }
}
