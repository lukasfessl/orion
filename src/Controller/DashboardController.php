<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\ManagementService;
use App\Entity\Tile;
use App\Entity\TilePack;

class DashboardController extends Controller {

    /**
     * @Route("/")
     */
    public function DefaultAction(ManagementService $managementService): Response {
        $tilePacks = $managementService->getTilePacks(['user' => $this->getUser()->getId()]);
        return $this->render('dashboard.html.twig', [
                'tilePacks' => $tilePacks
        ]);
    }

    /**
     * @Route("/{id}")
     */
    public function DetailAction(TilePack $tilePack, ManagementService $managementService): Response {
        $tilePacks = $managementService->getTilePacks(['user' => $this->getUser()->getId()]);
        return $this->render('dashboard.html.twig', [
                'tilePacks' => $tilePacks,
                'tiles' => $tilePack->getTiles(),
        ]);
    }

}
