<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\EnableTrait;
use App\Entity\Traits\DateTimeTrait;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[UniqueEntity(fields: ['titre'], message: "Ce titre est déjà utilisé par un autre article")]
#[ORM\HasLifecycleCallbacks]
class Categorie
{
    use DateTimeTrait,
        EnableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 3,
        max: 255,
    )]
    private ?string $titre = null;

    #[ORM\ManyToMany(targetEntity: article::class, inversedBy: 'categories')]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * @return Collection<int, article>
     */
    public function getArticle(): Collection
    {
        return $this->articles;
    }

    public function addArticle(article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
        }

        return $this;
    }

    public function removeArticle(article $article): static
    {
        $this->articles->removeElement($article);

        return $this;
    }
}
