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
     * @ORM\Column(type="string")
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

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="rooms")
     */
    private $players;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="roomsGameMaster")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gameMaster;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $roomPassword;

    public function __construct()
    {
        $this->pseudos = new ArrayCollection();
        $this->dices = new ArrayCollection();
        $this->uuid = uniqid('id');
        $this->players = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(User $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->addRoom($this);
        }

        return $this;
    }

    public function removePlayer(User $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            $player->removeRoom($this);
        }

        return $this;
    }

    public function getGameMaster(): ?User
    {
        return $this->gameMaster;
    }

    public function setGameMaster(?User $gameMaster): self
    {
        $this->gameMaster = $gameMaster;

        return $this;
    }

    public function getRoomPassword(): ?string
    {
        return $this->roomPassword;
    }

    public function setRoomPassword(string $roomPassword): self
    {
        $this->roomPassword = $roomPassword;

        return $this;
    }
}
