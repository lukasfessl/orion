<?php

namespace App\Controller;

use App\Entity\Link;
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

class LinkController extends Controller {


    /**
     * @Route("/saveLink", name="saveLink")
     */
    public function saveLinkAction(Request $request, ManagementService $managementService, ValidatorInterface $validator) {
        $userId = $this->getUser()->getId();
        $data = $request->getContent();
        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $link = $serializer->deserialize($data, Link::class, 'json');
        $errors = $validator->validate($link);
        if (count($errors) > 0) {
            return new JsonResponse([
                    'errors' => json_encode(JsonFormatter::getErrorMessages($errors)),
            ], 400);
        }
        $bookmark = $managementService->getBookmarkById($link->getBookmark());
        $link->setBookmark($bookmark);

        $bookmark = $managementService->saveLink($link);
        $this->get('session')->getFlashBag()->add('success', "Link was saved.");
        return new JsonResponse();
    }

    /**
     * @Route("/getLink", name="getLink")
     */
    public function getLinkAction(Request $reques, ManagementService $managementService) {
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
     * @Route("/deleteLink", name="deleteLink")
     */
    public function deleteLinkAction(Request $reques, ManagementService $managementService) {
        // TODO add check
        $linkId = $reques->get('linkId');
        $userId = $this->getUser()->getId();
        $link = $managementService->getLinkById($linkId);
        // TODO add check to user
        $managementService->deleteLink($link);
        $this->get('session')->getFlashBag()->add('success', "Link was deleted.");
        return new JsonResponse([
            'data' => 'ok'
        ]);
    }

}
