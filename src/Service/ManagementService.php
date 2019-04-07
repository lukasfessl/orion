<?php

namespace App\Service;

use App\Entity\Bookmark;
use App\Entity\Link;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Repository\BookmarkRepository;
use App\Repository\LinkRepository;

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

    public function deleteBookmark(Bookmark $bookmark) {
        $this->getBookmarkRepository()->delete($bookmark);
    }



    public function saveLink(Link $link) {
        return $this->getLinkRepository()->save($link);
    }

    public function deleteLink(Link $link) {
        return $this->getLinkRepository()->delete($link);
    }

    public function getLinkById($id) {
        return $this->getLinkRepository()->find($id);
    }

    /**
     * @return BookmarkRepository
     */
    public function getBookmarkRepository() {
        return $this->entityManager->getRepository(Bookmark::class);
    }

    /**
     * @return LinkRepository
     */
    public function getLinkRepository() {
        return $this->entityManager->getRepository(Link::class);
    }
}