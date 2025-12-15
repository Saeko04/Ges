<?php

namespace App\Controller;

use App\Entity\Sejour;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SejoursAVenirController extends AbstractController
{
    #[Route('/infirmier/sejours-a-venir', name: 'sejours_a_venir')]
    public function index(EntityManagerInterface $em): Response
    {
        $today = new \DateTime('today');

        // Séjours à venir (dateDebut > aujourd'hui)
        $sejours = $em->getRepository(Sejour::class)
            ->createQueryBuilder('s')
            ->where('s.dateDebut > :today')
            ->setParameter('today', $today->format('Y-m-d'))
            ->orderBy('s.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('sejours_a_venir/index.html.twig', [
            'sejours' => $sejours
        ]);
    }
}
