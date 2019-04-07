<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Entity\Link;
use App\Form\BookmarkType;
use App\Form\LinkType;
use App\Service\ManagementService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends Controller {

    /**
     * @Route("/")
     */
    public function DefaultAction(ManagementService $managementService): Response {
        $bookmarks = $managementService->getBookmarks(['user' => $this->getUser()->getId()]);
        $form = $this->createForm(BookmarkType::class, new Bookmark());

        return $this->render('dashboard.html.twig', [
                'bookmarks' => $bookmarks,
                'form' => $form->createView()
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
                'selectedBookmark' => $bookmark,
                'links' => $bookmark->getLinks(),
                'form' => $form->createView(),
                'formLink' => $formLink->createView()
        ]);
    }

    /**
     * @Route("/createBookmark")
     */
    public function CreateBookmarkAction(Request $request, ManagementService $managementService, ValidatorInterface $validator) {
        $userId = $this->getUser()->getId();
        $data = $request->getContent();
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $bookmark = $serializer->deserialize($data, Bookmark::class, 'json');
        $bookmark->setUser($userId);
        $errors = $validator->validate($bookmark);
        if (count($errors) > 0) {
            return new JsonResponse([
                'errors' => json_encode($this->getErrorMessages($errors)),
            ], 400);
        }
        $bookmark = $managementService->saveBookmark($bookmark);

        return new JsonResponse();
    }

    /**
     * @Route("/getBookmark")
     */
    public function GetBookmarkAction(Request $reques, ManagementService $managementService) {
        // TODO add check
        $bookmarkId = $reques->get('bookmarkId');
        $userId = $this->getUser()->getId();
        $bookmark = $managementService->getBookmarkById($bookmarkId);
        // TODO add check to user
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $bookmark->setLinks(null);
        $data = $serializer->serialize($bookmark, 'json');

        return new JsonResponse([
                'data' => $data,
        ]);
    }

    /**
     * @Route("/deleteBookmark")
     */
    public function DeleteBookmarkAction(Request $request, ManagementService $managementService) {
        // TODO add check
        $bookmarkId = $request->get('bookmarkId');
        $userId = $this->getUser()->getId();
        $bookmark = $managementService->getBookmarkById($bookmarkId);
        // TODO add check to user
        $managementService->deleteBookmark($bookmark);

        return new JsonResponse([
            'data' => 'ok'
        ]);
    }

    /**
     * @Route("/createLink")
     */
    public function CreateLinkAction(Request $request, ManagementService $managementService, ValidatorInterface $validator) {

//         var_dump($request->attributes);
//         die;

        $userId = $this->getUser()->getId();
        $data = $request->getContent();


        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $link = $serializer->deserialize($data, Link::class, 'json');
//         var_dump($link);
        $errors = $validator->validate($link);
//         var_dump($errors);
        if (count($errors) > 0) {
            return new JsonResponse([
                    'errors' => json_encode($this->getErrorMessages($errors)),
            ], 400);
        }
        $bookmark = $managementService->getBookmarkById($link->getBookmark());
        $link->setBookmark($bookmark);

        $bookmark = $managementService->saveLink($link);

        return new JsonResponse();
    }

    /**
     * @Route("/getLink")
     */
    public function GetLinkAction(Request $reques, ManagementService $managementService) {
        // TODO add check
        $linkId = $reques->get('linkId');
        $userId = $this->getUser()->getId();
        $link = $managementService->getLinkById($linkId);
        // TODO add check to user
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $link->setBookmark(null);
        $data = $serializer->serialize($link, 'json');

        return new JsonResponse([
            'data' => $data,
        ]);
    }

    /**
     * @Route("/deleteLink")
     */
    public function DeleteLinkAction(Request $reques, ManagementService $managementService) {
        // TODO add check
        $linkId = $reques->get('linkId');
        $userId = $this->getUser()->getId();
        $link = $managementService->getLinkById($linkId);
        // TODO add check to user
        $managementService->deleteLink($link);

        return new JsonResponse([
            'data' => 'ok'
        ]);
    }

    protected function getErrorMessages(ConstraintViolationListInterface $violations) {
        $errors = array();
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }

}
