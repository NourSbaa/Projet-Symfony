<?php

namespace App\Controller;

use App\Repository\StageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Stage;
use App\Form\StageType;
use App\Entity\Etudiants;
use App\Repository\EtudiantsRepository;

#[Route('/admin/stages', name: 'admin_stages_')]
class AdminStagesController extends AbstractController
{
   /**
     * @Route("/", name="index")
     */
    public function index(StageRepository $stageRepository,EtudiantsRepository $etudiantRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $stages = $stageRepository->findAll(); 
        $etudiant= $etudiantRepository->findAll();
        // Fetch stages data using your own logic
        return $this->render('admin/stages/index.html.twig', [
            'stages' => $stages,
            'etudiants'=>$etudiant
        ]);
    }

    /**
 * @Route("/new", name="new")
 */
public function new(Request $request,EntityManagerInterface $em): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');
    $stage = new Stage();
    // On crée le formulaire
    $stageForm = $this->createForm(StageType::class, $stage);

    // On traite la requête du formulaire
    $stageForm->handleRequest($request);

    $form = $this->createForm(StageType::class);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // On stocke
        $em->persist($stage);
        $em->flush();

        $this->addFlash('success', 'Votre stage ajouté avec succès');

        // On redirige
        return $this->redirectToRoute('admin_stages_index');
    }

    return $this->render('admin/stages/add.html.twig', [
        'stageForm' => $form->createView(),
    ]);
}

/**
 * @Route("/edit/{id}", name="edit")
 */
public function edit(int $id, Request $request): Response
{
    //On vérifie si l'utilisateur peut éditer avec le Voter
        $em = $this->getDoctrine()->getManager();
        $stage = $em->getRepository(Stage::class)->find($id);
        // On traite la requête du formulaire
        $stageForm = $this->createForm(StageType::class, $stage);
        $stageForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($stageForm->isSubmitted() && $stageForm->isValid()){

            // On stocke
            $em->persist($stage);
            $em->flush();

            $this->addFlash('success', 'stage modifié avec succès');

            // On redirige
            return $this->redirectToRoute('admin_stages_index');
        }


        return $this->render('admin/stages/edit.html.twig',[
            'stageForm' => $stageForm->createView(),
            'stages' => $stage,
        ]);
}

 /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(int $id, EtudiantsRepository $etudiantsRepository): Response
    {
        // On vérifie si l'utilisateur peut supprimer avec le Voter
        $em = $this->getDoctrine()->getManager();
        $stage = $em->getRepository(Stage::class)->find($id);
        $etudiant= new Etudiants();
        //set values to current user
        $etudiant = $etudiantsRepository->findAll();
        $length = count($etudiant); 
        for ($x = 0; $x < $length; $x++) {
                $etudiant[$x]->setStage(null);
                $em->persist($etudiant[$x]);
                $em->flush();
        }

        $em->remove($stage);
        $em->flush();
            return $this->redirectToRoute('admin_stages_index');
    }

}
