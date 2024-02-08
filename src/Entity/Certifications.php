<?php

namespace App\Entity;

use App\Repository\CertificationsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CertificationsRepository::class)
 */
class Certifications
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre_certifications;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description_certifications;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_certifications;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreCertifications(): ?string
    {
        return $this->titre_certifications;
    }

    public function setTitreCertifications(string $titre_certifications): self
    {
        $this->titre_certifications = $titre_certifications;

        return $this;
    }

    public function getDescriptionCertifications(): ?string
    {
        return $this->description_certifications;
    }

    public function setDescriptionCertifications(string $description_certifications): self
    {
        $this->description_certifications = $description_certifications;

        return $this;
    }

    public function getImageCertifications(): ?string
    {
        return $this->image_certifications;
    }

    public function setImageCertifications(string $image_certifications): self
    {
        $this->image_certifications = $image_certifications;

        return $this;
    }
}
