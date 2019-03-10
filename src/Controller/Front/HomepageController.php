<?php

namespace App\Controller\Front;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Tile;
use App\Repository\TileRepository;
use App\Form\TileType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Tilepack;
use App\Repository\TilePackRepository;
use App\Service\ManagementService;

/**
 * @Route("/hp/")
 */
class HomepageController extends AbstractController {

    /**
     * @Route("{id}", defaults={"id"=null})
     */
    public function homepageAction($id, ManagementService $managementService) {
        if ($id != null) {
            $tilePack = $managementService->getTilePackById($id);
        } else if ($this->getUser() != null) {
            $searchCriteria = ['active' => true, 'user' => $this->getUser()->getId()];
            $tilePack = $managementService->getTilePack($searchCriteria);
        } else {
            $tilePack = null;
        }

        return $this->render('front/homepage.html.twig', [
            'tilePack' => $tilePack,
        ]);
    }

}