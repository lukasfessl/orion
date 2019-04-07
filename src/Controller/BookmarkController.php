<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Service\ManagementService;
use App\Utils\JsonFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class BookmarkController extends Controller {

      /**
     * @Route("/saveBookmark", name="saveBookmark")
     */
    public function saveBookmarkAction(Request $request, ManagementService $managementService, ValidatorInterface $validator) {
        $userId = $this->getUser()->getId();
        $data = $request->getContent();
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $bookmark = $serializer->deserialize($data, Bookmark::class, 'json');
        $bookmark->setUser($userId);
        $errors = $validator->validate($bookmark);
        if (count($errors) > 0) {
            return new JsonResponse([
                    'errors' => json_encode(JsonFormatter::getErrorMessages($errors)),
            ], 400);
        }
        $bookmark = $managementService->saveBookmark($bookmark);

        return new JsonResponse();
    }

    /**
     * @Route("/getBookmark", name="getBookmark")
     */
    public function getBookmarkAction(Request $reques, ManagementService $managementService) {
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
     * @Route("/deleteBookmark", name="deleteBookmark")
     */
    public function deleteBookmarkAction(Request $request, ManagementService $managementService) {
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

}
