<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\OneToMany(targetEntity=StockHistoric::class, mappedBy="product")
     */
    private $stockHistorics;

    public function __construct()
    {
        $this->stockHistorics = new ArrayCollection();
        // Al crearlo seteamos la fecha en el constructor
        $this->created_at = new \DateTime();
        $this->stock = 0;
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

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(?Categories $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, StockHistoric>
     */
    public function getStockHistorics(): Collection
    {
        return $this->stockHistorics;
    }

    public function addStockHistoric(StockHistoric $stockHistoric): self
    {
        if (!$this->stockHistorics->contains($stockHistoric)) {
            $this->stockHistorics[] = $stockHistoric;
            $stockHistoric->setProductId($this);
        }

        return $this;
    }

    public function removeStockHistoric(StockHistoric $stockHistoric): self
    {
        if ($this->stockHistorics->removeElement($stockHistoric)) {
            // set the owning side to null (unless already changed)
            if ($stockHistoric->getProductId() === $this) {
                $stockHistoric->setProductId(null);
            }
        }

        return $this;
    }
}
