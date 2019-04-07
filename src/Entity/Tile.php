<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TileRepository")
 */
class Tile {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bookmark", inversedBy="tile")
     * @Assert\NotBlank
     */
    private $bookmark;

    /**
     * @ORM\Column(name="new_window", type="boolean")
     */
    private $newWindow;

    public function __construct() {
        $this->newWindow = true;
    }

    public function getId() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }

    public function setId($id) {
        $this->id = $id;
    }
    public function setName($name) {
        $this->name = $name;
    }

    public function setLink($link) {
        $this->link = $link;
    }
    public function getLink() {
        return $this->link;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }
    public function getIcon() {
        return $this->icon;
    }

    public function setNewWindow($newWindow) {
        $this->newWindow = $newWindow;
    }
    public function getNewWindow() {
        return $this->newWindow;
    }

    public function setBookmark($bookmark) {
        $this->bookmark = $bookmark;
    }
    public function getBookmark() {
        return $this->bookmark;
    }

}