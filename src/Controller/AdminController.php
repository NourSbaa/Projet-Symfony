<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use App\Repository\DocumentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{

     /**
     * @Route("/contact", name="contact")
     */
    public function contact(ContactRepository $contactRepository): Response
    {
        $contact = $contactRepository->findAll(); // Fetch contact data using your own logic
        return $this->render('admin/contact/index.html.twig', [
            'contacts' => $contact,
        ]);
    }

    /**
     * @Route("/document", name="document")
     */
    public function document(DocumentsRepository $documentsRepository): Response
    {
        $document = $documentsRepository->findAll(); // Fetch contact data using your own logic
        return $this->render('admin/documents/index.html.twig', [
            'documents' => $document,
        ]);
    }
}
