<?php

namespace App\Controller;

use App\Entity\Certifications;
use App\Entity\Etudiants;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CertificationsType;
use App\Repository\CertificationsRepository;
use App\Repository\EtudiantsRepository;

#[Route('/admin/certifications', name: 'admin_certifications_')]
class AdminCertificationsController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CertificationsRepository $certificationsRepository,EtudiantsRepository $etudiantRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $certifications = $certificationsRepository->findAll(); // Fetch certifications data using your own logic
        $etudiant= $etudiantRepository->findAll();
        return $this->render('admin/certifications/index.html.twig', [
            'certifications' => $certifications,
            'etudiants'=>$etudiant
        ]);
    }

/**
 * @Route("/new", name="new")
 */
    public function new(Request $request,EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $certification = new Certifications();
        // On crée le formulaire
        $certificationForm = $this->createForm(CertificationsType::class, $certification);

        // On traite la requête du formulaire
        $certificationForm->handleRequest($request);

        $form = $this->createForm(CertificationsType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // On stocke
            $em->persist($certification);
            $em->flush();

            $this->addFlash('success', 'Votre certification ajouté avec succès');

            // On redirige
            return $this->redirectToRoute('admin_certifications_index');
        }

        return $this->render('admin/certifications/add.html.twig', [
            'certificationForm' => $form->createView(),
        ]);
    }



/**
 * @Route("/edit/{id}", name="edit")
 */
public function edit(int $id, Request $request): Response
{
    //On vérifie si l'utilisateur peut éditer avec le Voter
        $em = $this->getDoctrine()->getManager();
        $certifications = $em->getRepository(Certifications::class)->find($id);
        // On traite la requête du formulaire
        $certificationForm = $this->createForm(CertificationsType::class, $certifications);
        $certificationForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($certificationForm->isSubmitted() && $certificationForm->isValid()){

            // On stocke
            $em->persist($certifications);
            $em->flush();

            $this->addFlash('success', 'certifications modifié avec succès');

            // On redirige
            return $this->redirectToRoute('admin_certifications_index');
        }


        return $this->render('admin/certifications/edit.html.twig',[
            'certificationForm' => $certificationForm->createView(),
            'certifications' => $certifications,
        ]);
}


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(int $id, EtudiantsRepository $etudiantsRepository): Response
    {
        // On vérifie si l'utilisateur peut supprimer avec le Voter
        $em = $this->getDoctrine()->getManager();
        $certifications = $em->getRepository(Certifications::class)->find($id);
        $etudiant= new Etudiants();
        //set values to current user
        $etudiant = $etudiantsRepository->findAll();
        $length = count($etudiant); 
        for ($x = 0; $x < $length; $x++) {
                $etudiant[$x]->removeCertification($certifications);
                $em->persist($etudiant[$x]);
                $em->flush();
        }
        
        $em->remove($certifications);
        $em->flush();
            return $this->redirectToRoute('admin_certifications_index');
    }
    
    
}