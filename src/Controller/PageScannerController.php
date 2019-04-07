<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PageScannerController extends Controller {

    /**
     * @Route("/scan", name="scan")
     */
    public function scanAction(Request $request) {
        $url = $request->get('url');

        $title = null;
        $favicon = null;

        $urlShort = parse_url($url);
        $urlShort = $urlShort['scheme']."://".$urlShort['host'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close ($ch);

        $doc = new \DOMDocument();
        @$doc->loadHTML($result);

        $title = $this->getTitle($doc);
        $favicon = $this->getFavicon($doc, $urlShort);

        return new JsonResponse([
            'title' => $title,
            'favicon' => $favicon,
        ]);
    }

    private function getTitle($doc) {
        $nodes = $doc->getElementsByTagName('title');
        if ($nodes->item(0) != null) {
            return trim($nodes->item(0)->nodeValue);
        }
    }

    private function getFavicon($doc, $urlShort) {
        $favicon = null;
        $nodes = $doc->getElementsByTagName('link');
        for ($i = 0; $i < $nodes->length; $i++) {
            $link = $nodes->item($i);
            if ($link->getAttribute("rel") == "shortcut icon") {
                $favicon = $link->getAttribute("href");
                break;
            }
        }

        if ($favicon == null) {
            if (get_headers($urlShort . "/favicon.ico")) {
                $favicon = $urlShort . "/favicon.ico";
            } else if (get_headers($urlShort . "/favicon.png")) {
                $favicon = $urlShort . "/favicon.png";
            }
        }

        if ($favicon != null && substr($favicon, 0, 4 ) != "http") {
            if (substr($favicon, 0, 1 ) != "/") {
                $favicon =  $urlShort . "/" .$favicon;
            } else {
                $favicon =  $urlShort . $favicon;
            }
        }

        return $favicon;
    }
}
