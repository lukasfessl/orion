<?php

namespace App\Service;

use App\Repository\TilePackRepository;
use App\Repository\TileRepository;
use Symfony\Component\Security\Core\Security;
use App\Entity\TilePack;
use App\Entity\Tile;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ManagementService {

    private $entityManager;

    private $tokenStorage;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function getTilePacks($criteria) {
        return $this->getTilePackRepository()->findBy($criteria);
    }

    public function getTilePack($criteria) {
        return $this->getTilePackRepository()->findOneBy($criteria);
    }

    public function getTilePackById($id) {
        return $this->getTilePackRepository()->find($id);
    }

    public function saveTilePack(TilePack $tilePack) {
        $tilePack->setUser($this->tokenStorage->getToken()->getUser()->getId());
        return $this->getTilePackRepository()->save($tilePack);
    }

    /**
     * @return TilePackRepository
     */
    public function getTilePackRepository() {
        return $this->entityManager->getRepository(TilePack::class);
    }

    /**
     * @return TileRepository
     */
    public function getTileRepository() {
        return $this->entityManager->getRepository(Tile::class);
    }
}