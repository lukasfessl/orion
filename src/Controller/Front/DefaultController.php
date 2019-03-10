<?php

namespace App\Controller\Front;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Tile;
use App\Repository\TileRepository;
use App\Form\TileType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TilePack;
use App\Repository\TilePackRepository;
use App\Service\ManagementService;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController {

    /**
     * @Route("")
     */
    public function defaultAction() {
        return $this->redirectToRoute('app_front_homepage_homepage');
    }

}