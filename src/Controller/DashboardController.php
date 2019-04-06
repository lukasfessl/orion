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
use App\Entity\Bookmark;
use App\Form\BookmarkType;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

        return $this->render('dashboard.html.twig', [
                'bookmarks' => $bookmarks,
                'tiles' => $bookmark->getTiles(),
                'form' => $form->createView()
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
        $data = $serializer->serialize($bookmark, 'json');

        return new JsonResponse([
                'data' => $data,
        ]);
    }

    /**
     * @Route("/deleteBookmark")
     */
    public function DeleteBookmarkAction(Request $reques, ManagementService $managementService) {
        // TODO add check
        $bookmarkId = $reques->get('bookmarkId');
        $userId = $this->getUser()->getId();
        $bookmark = $managementService->getBookmarkById($bookmarkId);
        // TODO add check to user
        $managementService->deleteBookmark($bookmark);

        return new JsonResponse([
            'data' => 'ok'
        ]);
    }

    protected function getErrorMessages($violations) {
        $errors = array();
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }

}
