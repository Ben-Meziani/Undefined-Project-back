<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(message = "The email is not a valid email.")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Password can not be blank.")
     * @Assert\Regex(pattern="/^(?=.*[a-z])(?=.*\d).{6,}$/i",
     *  message="Password is required to be minimum 6 chars in length and to include at least one letter and one number.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=Pseudo::class, mappedBy="user_id")
     */
    private $pseudos;

    /**
     * @ORM\ManyToMany(targetEntity=Dice::class, mappedBy="user_id")
     */
    private $dices;

    public function __construct()
    {
        $this->pseudos = new ArrayCollection();
        $this->dices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
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

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Pseudo[]
     */
    public function getPseudos(): Collection
    {
        return $this->pseudos;
    }

    public function addPseudo(Pseudo $pseudo): self
    {
        if (!$this->pseudos->contains($pseudo)) {
            $this->pseudos[] = $pseudo;
            $pseudo->addUserId($this);
        }

        return $this;
    }

    public function removePseudo(Pseudo $pseudo): self
    {
        if ($this->pseudos->contains($pseudo)) {
            $this->pseudos->removeElement($pseudo);
            $pseudo->removeUserId($this);
        }

        return $this;
    }

    /**
     * @return Collection|Dice[]
     */
    public function getDices(): Collection
    {
        return $this->dices;
    }

    public function addDice(Dice $dice): self
    {
        if (!$this->dices->contains($dice)) {
            $this->dices[] = $dice;
            $dice->addUserId($this);
        }

        return $this;
    }

    public function removeDice(Dice $dice): self
    {
        if ($this->dices->contains($dice)) {
            $this->dices->removeElement($dice);
            $dice->removeUserId($this);
        }

        return $this;
    }
}