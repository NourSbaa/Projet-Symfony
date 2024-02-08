<?php

namespace App\Controller;

use App\Entity\Certifications;
use App\Repository\CertificationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Etudiants;
use App\Repository\EtudiantsRepository;

/**
 * @Route("/certifications")
 */
class CertificationsController extends AbstractController
{
    private $certificationsRepository;


    public function __construct(CertificationsRepository $certificationsRepository)
    {
        $this->certificationsRepository = $certificationsRepository;
    }

    /**
     * @Route("/", name="certifications")
     */
    public function index(CertificationsRepository $certificationsRepository,EtudiantsRepository $etudiantsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $etudiant = new Etudiants();
        $currentLoggedInUser = $this->getUser();  
        $etudiant->setEmail($currentLoggedInUser->getUserIdentifier());
        //set values to current user
        $etudiant = $etudiantsRepository->findOneBy(['email' => $etudiant->getEmail()]);
        $certifications = $certificationsRepository->findAll(); // Fetch certifications data using your own logic
        return $this->render('certifications/index.html.twig', [
            'certifications' => $certifications,
            'etudiant'=>$etudiant

        ]);
    }
    /**
     * @Route("/subscribe/{id}", name="certif_subscribe")
     */
    public function subscription(int $id,EtudiantsRepository $etudiantsRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $em = $this->getDoctrine()->getManager();
        $certifications = $em->getRepository(Certifications::class)->find($id);
        $etudiant = new Etudiants();
        $currentLoggedInUser = $this->getUser();  
        $etudiant->setEmail($currentLoggedInUser->getUserIdentifier());
        //set values to current user
        $etudiant = $etudiantsRepository->findOneBy(['email' => $etudiant->getEmail()]);
        $etudiant->addCertification($certifications);
        $em->persist($etudiant);
        $em->flush();
        return $this->redirectToRoute('certifications');
    }
    
}
