<?php

namespace App\Service;

use App\Repository\BookmarkRepository;
use App\Repository\TileRepository;
use Symfony\Component\Security\Core\Security;
use App\Entity\Bookmark;
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

    public function getBookmarks($criteria) {
        return $this->getBookmarkRepository()->findBy($criteria);
    }

    public function getBookmark($criteria) {
        return $this->getBookmarkRepository()->findOneBy($criteria);
    }

    public function getBookmarkById($id) {
        return $this->getBookmarkRepository()->find($id);
    }

    public function saveBookmark(Bookmark $bookmark) {
        $bookmark->setUser($this->tokenStorage->getToken()->getUser()->getId());
        return $this->getBookmarkRepository()->save($bookmark);
    }

    /**
     * @return BookmarkRepository
     */
    public function getBookmarkRepository() {
        return $this->entityManager->getRepository(Bookmark::class);
    }

    /**
     * @return TileRepository
     */
    public function getTileRepository() {
        return $this->entityManager->getRepository(Tile::class);
    }
}