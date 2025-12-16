<?php

namespace App\Controller;

use App\Entity\Sejour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArriveePatientController extends AbstractController
{
    #[Route('/infirmier/arrivee-patient', name: 'arrivee_patient')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $today = new \DateTime('today');

        // Récupère les séjours non encore arrivés dont la date de début est aujourd'hui ou dans le passé
        $sejours = $em->getRepository(Sejour::class)->createQueryBuilder('s')
            ->where('s.arrive = false')
            ->andWhere('s.dateDebut <= :today')
            ->setParameter('today', $today)
            ->orderBy('s.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();

        // Traitement formulaire pour valider l'arrivée
        if ($request->isMethod('POST')) {
            $sejourId = $request->request->get('sejour_id');
            $commentaire = $request->request->get('commentaire', null);

            $sejour = $em->getRepository(Sejour::class)->find($sejourId);

            if ($sejour) {
                $sejour->setArrive(true);
                $sejour->setCommentaire($commentaire);
                $em->flush();

                $this->addFlash('success', 'Arrivée du patient validée !');

                return $this->redirectToRoute('arrivee_patient');
            }
        }

        return $this->render('arrivee_patient/index.html.twig', [
            'sejours' => $sejours
        ]);
    }
}
