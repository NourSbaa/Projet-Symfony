<?php

namespace App\Entity;

use App\Repository\EtudiantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=EtudiantsRepository::class)
 * @UniqueEntity(fields={"email"}, message="Email already taken")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Etudiants implements UserInterface
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
    private $nom_etudiants;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom_etudiants;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

     /**
     * @ORM\Column(type="array")
     */
    private $roles = [];


   /**
     * @ORM\OneToOne(targetEntity="App\Entity\Stage", mappedBy="etudiant")
     */
    private $stage;
    

    /**
     * Many Users have Many Groups.
     * @ManyToMany(targetEntity="Certifications")
     * @JoinTable(name="Etudiants_Certifications",
     *      joinColumns={@JoinColumn(name="etudiant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="certification_id", referencedColumnName="id")}
     *      )
     * @var Collection<int,Certifications>
     */
    private $certifications;


    
    public function __construct()
    {
      // Si vous aviez déjà un constructeur, ajoutez juste cette ligne :
      $this->certifications = new \Doctrine\Common\Collections\ArrayCollection();
      $this->roles=['ROLE_USER'];
    }

    public function getStage(): ?Stage
    {
        return $this->stage;
    }

    public function setStage(?Stage $stage): self
    {
        $this->stage = $stage;

        return $this;
    }
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    public function getSalt()
    {  

      }

    

    public function addCertifications(Certifications $certifications): self
    {
        if (!$this->certifications->contains($certifications)) {
            $this->certifications[] = $certifications;
        }

        return $this;
    }

    public function removeCertifications(Certifications $certifications): self
    {
        if ($this->certifications->contains($certifications)) {
            $this->certifications->removeElement($certifications);
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEtudiants(): ?string
    {
        return $this->nom_etudiants;
    }

    public function setNomEtudiants(string $nom_etudiants): self
    {
        $this->nom_etudiants = $nom_etudiants;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPrenomEtudiants(): ?string
    {
        return $this->prenom_etudiants;
    }

    public function setPrenomEtudiants(string $prenom_etudiants): self
    {
        $this->prenom_etudiants = $prenom_etudiants;

        return $this;
    }
 public function getUsername(): ?string
                   {
                       return $this->username;
                   }

public function getUserIdentifier(): ?string
{
    return $this->email;
}
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCertifications()
    {
        return $this->certifications;
    }

    public function addCertification(Certifications $certification): static
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
        }

        return $this;
    }

    public function removeCertification(Certifications $certification): static
    {
        $this->certifications->removeElement($certification);

        return $this;
    }
   
}
