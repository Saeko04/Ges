<?php

namespace App\Controller;

use App\Entity\Sejour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConsultationSejourController extends AbstractController
{
    #[Route('/infirmier/consultation-sejours', name: 'consultation_sejours')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        // Date par défaut = aujourd'hui
        $dateParam = $request->query->get('date');
        $date = $dateParam ? new \DateTime($dateParam) : new \DateTime('today');

        // Récupérer les séjours actifs à cette date
        $sejours = $em->getRepository(Sejour::class)
            ->createQueryBuilder('s')
            ->where(':date BETWEEN s.dateDebut AND COALESCE(s.dateFin, :date)')
            ->setParameter('date', $date->format('Y-m-d'))
            ->orderBy('s.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('consultation_sejour/index.html.twig', [
            'sejours' => $sejours,
            'date' => $date
        ]);
    }
}
