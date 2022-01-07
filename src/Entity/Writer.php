<?php

namespace App\Entity;

use App\Repository\WriterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WriterRepository::class)]
class Writer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $firsname;

    #[ORM\Column(type: 'string', length: 255)]
    private $countrie;

    #[ORM\OneToMany(mappedBy: 'writer', targetEntity: Book::class)]
    private $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
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

    public function getFirsname(): ?string
    {
        return $this->firsname;
    }

    public function setFirsname(string $firsname): self
    {
        $this->firsname = $firsname;

        return $this;
    }

    public function getCountrie(): ?string
    {
        return $this->countrie;
    }

    public function setCountrie(string $countrie): self
    {
        $this->countrie = $countrie;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
            $book->setWriter($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getWriter() === $this) {
                $book->setWriter(null);
            }
        }

        return $this;
    }
}
