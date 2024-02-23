<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface,PasswordAuthenticatedUserInterface

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Veuillez entrer votre nom.')]
    #[Assert\Regex(pattern: '/^[a-zA-Z]+$/', message: 'Only letters are allowed.')]
    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[Assert\NotBlank(message: 'Veuillez entrer votre prenom.')]
    #[Assert\Regex(pattern: '/^[a-zA-Z]+$/', message: 'Only letters are allowed.')]
    #[ORM\Column(length: 255)]
    private ?string $Prenom = null;

    #[Assert\NotBlank(message: 'Please enter your address.')]
    #[ORM\Column(length: 255)]
    private ?string $Adresse = null;

    #[Assert\NotBlank(message: 'Please enter your email address.')]
    #[Assert\Email(message: 'The email "{{ value }}" is not a valid email.')]
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[Assert\NotBlank(message: 'Please enter your phone number.')]
    #[Assert\Regex(pattern: "/^\d{8}$/", message: 'Phone number must be 8 digits.')]
    #[ORM\Column]
    private ?int $NumTel = null;

    #[Assert\NotBlank(message: 'Please enter your profession.')]
    #[Assert\Regex(pattern: '/^[a-zA-Z]+$/', message: 'Only letters are allowed.')]
    #[ORM\Column(length: 255)]
    private ?string $Profession = null;

    #[Assert\NotBlank(message: 'Please enter your password.')]
    #[Assert\Length(min: 6, minMessage: 'Password must be at least {{ limit }} characters long.')]
    #[ORM\Column(length: 255)]
    private ?string $Password = null;

    #[Assert\NotBlank(message: 'Enter votre cin.')]
    #[Assert\Length(min: 8, minMessage: 'cin doit etre {{ limit }} characters long.')]
    #[ORM\Column]
    private ?int $CIN = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): static
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->Adresse;
    }

    public function setAdresse(string $Adresse): static
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->NumTel;
    }

    public function setNumTel(int $NumTel): static
    {
        $this->NumTel = $NumTel;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->Profession;
    }

    public function setProfession(string $Profession): static
    {
        $this->Profession = $Profession;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }

    public function getCIN(): ?int
    {
        return $this->CIN;
    }

    public function setCIN(int $CIN): static
    {
        $this->CIN = $CIN;

        return $this;
    }

    public function eraseCredentials()
    {
        // Implement if you store any temporary, sensitive data on the user
    }

    public function getSalt()
    {
        // Implement if you are not using a modern algorithm for password hashing
        // This method is deprecated in Symfony 5.3 and removed in Symfony 6
    }
    public function getRoles()
    {
        return array('ROLE_USER');
    }
    public function getUsername(): string
    {
        // Implement to return the username of the user
        return $this->id;
    }

    private bool $activated = false;

    public function isActivated(): bool
    {
        return $this->activated;
    }

    public function setActivated(bool $activated): self
    {
        $this->activated = $activated;
        return $this;
    }
    private $isActive = true; // Assume all users are initially active

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
