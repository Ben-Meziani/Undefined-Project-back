<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;



/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{
    
    /**
     * @var \Ramsey\Uuid\UuidInterface
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $uuid;

 
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $theme;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="rooms")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="rooms")
     * @ORM\Column(type="string", length=255)
     */
    private $gameMaster;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $files;

    /**
     * @ORM\Column(type="integer")
     */
    private $playerNumber;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=Pseudo::class, mappedBy="room_id")
     */
    private $pseudos;

    /**
     * @ORM\ManyToMany(targetEntity=Dice::class, mappedBy="room_id")
     */
    private $dices;
   /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    public function __construct()
    {
        $this->pseudos = new ArrayCollection();
        $this->dices = new ArrayCollection();
        $min="1"; $max="1000";
        $this->uuid = uniqid(random_int($min, $max));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getPlayer(): ?string
    {
        return $this->player;
    }

    public function setPlayer(string $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getGameMaster(): ?string
    {
        return $this->gameMaster;
    }

    public function setGameMaster(string $gameMaster): self
    {
        $this->gameMaster = $gameMaster;

        return $this;
    }

    public function getFiles(): ?string
    {
        return $this->files;
    }

    public function setFiles(string $files): self
    {
        $this->files = $files;

        return $this;
    }

    public function getPlayerNumber(): ?int
    {
        return $this->playerNumber;
    }

    public function setPlayerNumber(int $playerNumber): self
    {
        $this->playerNumber = $playerNumber;

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

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
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
            $pseudo->addRoomId($this);
        }

        return $this;
    }

    public function removePseudo(Pseudo $pseudo): self
    {
        if ($this->pseudos->contains($pseudo)) {
            $this->pseudos->removeElement($pseudo);
            $pseudo->removeRoomId($this);
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
            $dice->addRoomId($this);
        }

        return $this;
    }

    public function removeDice(Dice $dice): self
    {
        if ($this->dices->contains($dice)) {
            $this->dices->removeElement($dice);
            $dice->removeRoomId($this);
        }

        return $this;
    }


    /**
     * Get the value of uuid
     *
     * @return  \Ramsey\Uuid\UuidInterface
     */ 
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set the value of uuid
     *
     * @param  \Ramsey\Uuid\UuidInterface  $uuid
     *
     * @return  self
     */ 
    
    public function setUuid(\Ramsey\Uuid\UuidInterface $uuid)
    {

        return $this;
    }
}
