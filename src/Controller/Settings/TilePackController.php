<?php

namespace App\Controller\Settings;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Tile;
use App\Form\TileType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TilePackRepository;
use App\Entity\TilePack;
use App\Form\TilePackType;
use App\Service\ManagementService;

/**
 * @Route("settings/tile-group/")
 */
class TilePackController extends AbstractController {

    /**
     * @Route("list/{id}", defaults={"id"=null})
     */
    public function tilepackPack($id, Request $request, ManagementService $managementService) {
        if ($id == null) {
            $searchCriteria = ['active' => true, 'user' => $this->getUser()->getId()];
            $tilePack = $managementService->getTilePack($searchCriteria);
        } else {
            $tilePack = $managementService->getTilePackById($id);
        }

        $form = $this->createForm(TilePackType::class, $tilePack);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getTilePackRepository()->save($form->getData());
        }

        return $this->render('settings/tilePack/list.html.twig', [
            'tilePackList' => $managementService->getTilePacks([]),
            'tilePack' => $tilePack,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("add")
     */
    public function addTilePack(Request $request, ManagementService $managementService) {
        $form = $this->createForm(TilePackType::class, new TilePack());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $managementService->saveTilePack($form->getData());
        }

        return $this->render('settings/tilePack/add.html.twig', [
            'tilePackList' => $managementService->getTilePacks([]),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("edit/{id}")
     */
    public function editTilePack(TilePack $tilePack, Request $request) {
        $form = $this->createForm(TilePackType::class, $tilePack);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getTilePackRepository()->save($form->getData());
        }

        return $this->render('settings/tilePack/edit.html.twig', [
                'form' => $form->createView(),
        ]);
    }


    /**
     * @return TilePackRepository
     */
    public function getTilePackRepository() {
        return $this->getDoctrine()->getRepository(TilePack::class);
    }

}