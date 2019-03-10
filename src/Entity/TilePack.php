<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TilePackRepository")
 */
class TilePack {

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
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tile", mappedBy="tilePack")
     */
    private $tiles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="integer")
     */
    private $user;


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

    public function setSlug($slug) {
        $this->slug = $slug;
    }
    public function getSlug() {
        return $this->slug;
    }

    public function setIcon($icon) {
        $this->icon = $icon;
    }
    public function getIcon() {
        return $this->icon;
    }

    public function setTiles($tiles) {
        $this->tiles = $tiles;
    }
    public function getTiles() {
        return $this->tiles;
    }

    public function setActive($active) {
        $this->active = $active;
    }
    public function getActive() {
        return $this->active;
    }

    public function setUser($user) {
        $this->user = $user;
    }
    public function getUser() {
        return $this->user;
    }

}