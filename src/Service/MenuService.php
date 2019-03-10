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

class MenuService {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getTilePacks() {
        return $this->getTilePackRepository()->findBy([]);
    }

    /**
     * @return TilePackRepository
     */
    public function getTilePackRepository() {
        return $this->entityManager->getRepository(TilePack::class);
    }
}