<?php
namespace App\Controller\Admin;

use App\Entity\Patient;
use App\Form\PatientType;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/patient')]
class PatientController extends AbstractController
{
    #[Route('/', name: 'gestion_patients')]
    public function index(PatientRepository $repo): Response
    {
        $patients = $repo->findAll();

        return $this->render('admin/patient/index.html.twig', [
            'patients' => $patients
        ]);
    }

    #[Route('/new', name: 'patient_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($patient);
            $em->flush();
            $this->addFlash('success', 'Patient ajouté !');
            return $this->redirectToRoute('gestion_patients');
        }

        return $this->render('admin/patient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'patient_edit')]
    public function edit(Patient $patient, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Patient modifié !');
            return $this->redirectToRoute('gestion_patients');
        }

        return $this->render('admin/patient/edit.html.twig', [
            'form' => $form->createView(),
            'patient' => $patient
        ]);
    }
}
