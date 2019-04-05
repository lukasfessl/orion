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
use App\Form\TilePackType;
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
        $tilePacks = $managementService->getTilePacks(['user' => $this->getUser()->getId()]);
        return $this->render('dashboard.html.twig', [
                'tilePacks' => $tilePacks
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id"="\d+"})
     */
    public function DetailAction(TilePack $tilePack, ManagementService $managementService): Response {
        $tilePacks = $managementService->getTilePacks(['user' => $this->getUser()->getId()]);

        $form = $this->createForm(TilePackType::class, new TilePack());

        return $this->render('dashboard.html.twig', [
                'tilePacks' => $tilePacks,
                'tiles' => $tilePack->getTiles(),
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

        $bookmark = $serializer->deserialize($data, TilePack::class, 'json');
        $bookmark->setUser($userId);
        $errors = $validator->validate($bookmark);
        if (count($errors) > 0) {
            return new JsonResponse([
                'errors' => json_encode($this->getErrorMessages($errors)),
            ], 400);
        }
        $bookmark = $managementService->saveTilePack($bookmark);

        return new JsonResponse();
    }

    protected function getErrorMessages($violations) {
        $errors = array();
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }

}
