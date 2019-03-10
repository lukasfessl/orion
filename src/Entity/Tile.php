<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TilePack", inversedBy="tile")
     */
    private $tilePack;

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

    public function setTilePack($tilePack) {
        $this->tilePack = $tilePack;
    }
    public function getTilePack() {
        return $this->tilePack;
    }

}