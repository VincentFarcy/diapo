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
    private $path;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Album", mappedBy="images")
     */
    private $albums;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Album", mappedBy="featuredImage")
     */
    private $featuredOnAlbums;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->albums = new ArrayCollection();
        $this->featuredOnAlbums = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

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

    /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->addImage($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->contains($album)) {
            $this->albums->removeElement($album);
            $album->removeImage($this);
        }

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getFeaturedOnAlbums(): Collection
    {
        return $this->featuredOnAlbums;
    }

    public function addFeaturedOnAlbum(Album $featuredOnAlbum): self
    {
        if (!$this->featuredOnAlbums->contains($featuredOnAlbum)) {
            $this->featuredOnAlbums[] = $featuredOnAlbum;
            $featuredOnAlbum->setFeaturedImage($this);
        }

        return $this;
    }

    public function removeFeaturedOnAlbum(Album $featuredOnAlbum): self
    {
        if ($this->featuredOnAlbums->contains($featuredOnAlbum)) {
            $this->featuredOnAlbums->removeElement($featuredOnAlbum);
            // set the owning side to null (unless already changed)
            if ($featuredOnAlbum->getFeaturedImage() === $this) {
                $featuredOnAlbum->setFeaturedImage(null);
            }
        }

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
}
