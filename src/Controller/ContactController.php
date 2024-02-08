<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// src/Controller/MailerController.php
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ContactType;


/**
 * @Route("/contacts")
 */
class ContactController extends AbstractController
{
   

   /**
    * @Route("/new", name="contact_new")
    */
   public function new(Request $request,EntityManagerInterface $em,ContactRepository $contactRepository ): Response
   {

      $contact = new Contact();

        // On crée le formulaire
        $contactForm = $this->createForm(ContactType::class, $contact);

        // On traite la requête du formulaire
        $contactForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($contactForm->isSubmitted() && $contactForm->isValid()){
            // On stocke
            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'Votre demande ajouté avec succès');

            // On redirige
            return $this->redirectToRoute('app_main');

        }
        return $this->renderForm('contact/index.html.twig', [
            'contactForm' => $contactForm,
        ]);
   }


   


}
