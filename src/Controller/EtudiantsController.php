<?php

namespace App\Controller;

use App\Entity\Etudiants;
use App\Form\EtudiantsType;
use App\Repository\CertificationsRepository;
use App\Repository\EtudiantsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/etudiants")
 */
class EtudiantsController extends AbstractController
{
    private function serializeEtudiant(Etudiants $etudiant): array
    {
        return [
            'id' => $etudiant->getId(),
            'nom_etudiants' => $etudiant->getNomEtudiants(),
            'prenom_etudiants' => $etudiant->getPrenomEtudiants(),
            'email' => $etudiant->getEmail(),
        ];
    }
}
