<?php

namespace App\Controller\Settings;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Tile;
use App\Repository\TileRepository;
use App\Form\TileType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TilePack;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("settings/tile/")
 */
class TileController extends AbstractController {

    /**
     * @Route("{id}/add")
     */
    public function addTile(TilePack $tilePack, Request $request) {
        $form = $this->createForm(TileType::class, new Tile());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData()->setTilePack($tilePack);
            $this->getTileRepository()->save($form->getData());
        }

        return $this->render('settings/tile/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("edit/{id}")
     */
    public function editTile(Tile $tile, Request $request) {
        $form = $this->createForm(TileType::class, $tile);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getTileRepository()->save($form->getData());
        }

        return $this->render('settings/tile/edit.html.twig', [
                'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete/{id}")
     */
    public function deleteTile(Tile $tile, Request $request) {
        $this->getTileRepository()->delete($tile);
        return $this->redirect("/");
    }

    /**
     * @Route("scan")
     */
    public function scanPage(Request $request) {
        $url = $request->get('url');

        $title = null;
        $favicon = null;

        $urlShort = parse_url($url);
        $urlShort = $urlShort['scheme']."://".$urlShort['host'];

        if (get_headers($urlShort . "/favicon.ico")) {
            $favicon = $urlShort . "/favicon.ico";
        } else if (get_headers($urlShort . "/favicon.png")) {
            $favicon = $urlShort . "/favicon.png";
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close ($ch);

        $doc = new \DOMDocument();
        @$doc->loadHTML($result);

        $nodes = $doc->getElementsByTagName('title');
        if ($nodes->item(0) != null) {
            $title = trim($nodes->item(0)->nodeValue);
        }

        $nodes = $doc->getElementsByTagName('link');
        for ($i = 0; $i < $nodes->length; $i++) {
            $link = $nodes->item($i);
            if ($link->getAttribute("type") == "image/x-icon") {
                $favicon = $link->getAttribute("href");
                break;
            }
        }

        if ($favicon != null && substr($favicon, 0, 1 ) != "/" && substr($favicon, 0, 4 ) != "http") {
            $favicon =  $urlShort . "/" .$favicon;
        }

        return new JsonResponse([
            'title' => $title,
            'favicon' => $favicon,
        ]);
    }

    /**
     * @return TileRepository
     */
    public function getTileRepository() {
        return $this->getDoctrine()->getRepository(Tile::class);
    }

}