<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Form\DocumentsType;
use App\Repository\DocumentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/documents")
 */
class DocumentsController extends AbstractController
{

   
   
    /**
     * @Route("/new", name="documents_new")
     */
    public function new(Request $request,EntityManagerInterface $em): Response
    {
        $document = new Documents();

        // On crée le formulaire
        $documentForm = $this->createForm(DocumentsType::class, $document);

        // On traite la requête du formulaire
        $documentForm->handleRequest($request);

        $form = $this->createForm(DocumentsType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // On stocke
            $em->persist($document);
            $em->flush();

            $this->addFlash('success', 'Votre demande ajouté avec succès');

            // On redirige
            return $this->redirectToRoute('app_main');
        }

        return $this->render('documents/add.html.twig', [
            'documentForm' => $form->createView(),
        ]);
    }
   

    
}
