<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $src;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $alt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $caption;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ImageAlbum", mappedBy="image", orphanRemoval=true)
     */
    private $imageAlbums;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->imageInAlbums = new ArrayCollection();
        $this->imageAlbums = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->src;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function setSrc(string $src): self
    {
        $this->src = $src;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|ImageAlbum[]
     */
    public function getImageAlbums(): Collection
    {
        return $this->imageAlbums;
    }

    public function addImageAlbum(ImageAlbum $imageAlbum): self
    {
        if (!$this->imageAlbums->contains($imageAlbum)) {
            $this->imageAlbums[] = $imageAlbum;
            $imageAlbum->setImage($this);
        }

        return $this;
    }

    public function removeImageAlbum(ImageAlbum $imageAlbum): self
    {
        if ($this->imageAlbums->contains($imageAlbum)) {
            $this->imageAlbums->removeElement($imageAlbum);
            // set the owning side to null (unless already changed)
            if ($imageAlbum->getImage() === $this) {
                $imageAlbum->setImage(null);
            }
        }

        return $this;
    }
}
