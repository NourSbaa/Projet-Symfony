<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Form\StageType;
use App\Repository\StageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EtudiantsRepository;
use App\Entity\Etudiants;

/**
 * @Route("/stages")
 */
class StageController extends AbstractController
{

    /**
     * @Route("/", name="stage")
     */
    public function index(StageRepository $stageRepository,EtudiantsRepository $etudiantsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $stages = $stageRepository->findAll();
        $etudiant = new Etudiants();
        $currentLoggedInUser = $this->getUser();  
        $etudiant->setEmail($currentLoggedInUser->getUserIdentifier());
        //set values to current user
        $etudiant = $etudiantsRepository->findOneBy(['email' => $etudiant->getEmail()]);
        return $this->render('stage/index.html.twig', [
            'offresStage' => $stages,
            'etudiant'=>$etudiant
        ]);
    }

   


    /**
     * @Route("/subscribe/{id}", name="stage_subscribe")
     */
    public function subscription(int $id,EtudiantsRepository $etudiantsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $stages = $em->getRepository(Stage::class)->find($id);
        $etudiant = new Etudiants();
        $currentLoggedInUser = $this->getUser();  
        $etudiant->setEmail($currentLoggedInUser->getUserIdentifier());
        //set values to current user
        $etudiant = $etudiantsRepository->findOneBy(['email' => $etudiant->getEmail()]);
        $etudiant->setStage($stages);
        $em->persist($etudiant);
        $stages->setEtudiant($etudiant);
        $em->persist($stages);
        $em->flush();
        return $this->redirectToRoute('stage');
    }
}
