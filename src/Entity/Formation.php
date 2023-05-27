<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Image;


/**
 * @ORM\Entity
 */
class Formation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $titre;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $duree;

    /**
     * @ORM\Column(type="date")
     */
    private $begin_at;

// /**
// * @ORM\OneToOne(targetEntity="Image",cascade={"persist"})
// * @ORM\JoinColumn(nullable=false)
// */
// private $image;



    /**
     * @ORM\OneToMany(targetEntity=Participnt::class, mappedBy="Formation")
     */
    private $participnts;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="Formation")
     */
    private $participants;

    public function __construct()
    {
        $this->participnts = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    // Ajoutez ici les getters et setters pour chaque attribut

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getduree(): ?int
    {
        return $this->duree;
    }

    public function setduree(int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->begin_at;
    }

    public function setBeginAt(\DateTimeInterface $begin_at): self
    {
        $this->begin_at = $begin_at;
        return $this;
    }

    // public function getImage(): ?string
    // {
    //     return $this->Image;
    // }

    // public function setImage(string $Image): self
    // {
    //     $this->Image = $Image;

    //     return $this;
    // }

    /**
     * @return Collection<int, Participnt>
     */
    public function getParticipnts(): Collection
    {
        return $this->participnts;
    }

    public function addParticipnt(Participnt $participnt): self
    {
        if (!$this->participnts->contains($participnt)) {
            $this->participnts[] = $participnt;
            $participnt->setFormation($this);
        }

        return $this;
    }

    public function removeParticipnt(Participnt $participnt): self
    {
        if ($this->participnts->removeElement($participnt)) {
            // set the owning side to null (unless already changed)
            if ($participnt->getFormation() === $this) {
                $participnt->setFormation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setFormation($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getFormation() === $this) {
                $participant->setFormation(null);
            }
        }

        return $this;
    }

    // public function getImage(): ?Image
    // {
    //     return $this->image;
    // }

    // public function setImage(Image $image): self
    // {
    //     $this->image = $image;

    //     return $this;
    // }
}
