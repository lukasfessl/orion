<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Entity\Link;
use App\Form\BookmarkType;
use App\Form\LinkType;
use App\Service\ManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends Controller {

    /**
     * @Route("/")
     */
    public function DefaultAction(ManagementService $managementService): Response {
        $bookmarks = $managementService->getBookmarks(['user' => $this->getUser()->getId()]);

        $formLink = $this->createForm(LinkType::class, new Link(), ['data' => ['bookmarks' => $bookmarks]]);
        $form = $this->createForm(BookmarkType::class, new Bookmark());

        return $this->render('dashboard.html.twig', [
            'bookmarks' => $bookmarks,
            'form' => $form->createView(),
            'formLink' => $formLink->createView()
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"})
     */
    public function DetailAction(Bookmark $bookmark, ManagementService $managementService): Response {
        $bookmarks = $managementService->getBookmarks(['user' => $this->getUser()->getId()]);

        $form = $this->createForm(BookmarkType::class, new Bookmark());
        $formLink = $this->createForm(LinkType::class, new Link(), ['data' => ['bookmarks' => $bookmarks]]);

        return $this->render('dashboard.html.twig', [
                'bookmarks' => $bookmarks,
                'links' => $bookmark->getLinks(),
                'form' => $form->createView(),
                'formLink' => $formLink->createView()
        ]);
    }
}
