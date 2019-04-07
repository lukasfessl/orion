<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookmarkRepository")
 */
class Bookmark {

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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Link", mappedBy="bookmark")
     */
    private $links;

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

    public function setIcon($icon) {
        $this->icon = $icon;
    }
    public function getIcon() {
        return $this->icon;
    }

    public function setLinks($links) {
        $this->links = $links;
    }
    public function getLinks() {
        return $this->links;
    }

    public function setUser($user) {
        $this->user = $user;
    }
    public function getUser() {
        return $this->user;
    }

}