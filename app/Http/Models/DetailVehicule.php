<?php

namespace App\http\models;

use Illuminate\Database\Eloquent\Model;
use Goutte\Client;


class DetailVehicule extends Model
{

    private $finalArray;


    public function crawler($urlBase,$idCar){

        $client = new Client();

        $link = $urlBase.'/'.$idCar;

        $crawler = $client->request('GET', $link);

        if($this->verifyResult($crawler) == 'Opss!'){
            return response(json_encode([
                "Result" => 'This vehicle does not exist in the registration or may have already been sold.',
                "link" => $link,
            ],JSON_UNESCAPED_SLASHES), 400);
        }

        $this->extractName($crawler);
        $this->extractDescription($crawler);
        $this->extractValue($crawler);
        $this->extractDetails($crawler);
        $this->extractAccessories($crawler);
        $this->extractComments($crawler);
        $this->extractImages($crawler);

        return response(json_encode([
            "Result" => $this->getFinalArray(),
            "link" => $link,
        ],JSON_UNESCAPED_SLASHES), 200);
    }

    private function verifyResult($crawler){

        $result = $crawler->filter('div.opss-text > b')->each(function ($node) {
            $element = $node->text();
            return $element;
        });

        return !empty($result) ? $result[0] : $result;
    }

    private function extractName($crawler){

        $name = $crawler->filter('div.item-info > h1')->each(function ($node) {
            $element = $node->text();
            return $element;
        });

        $this->finalArray['name'] = $name[0];

    }

    private function extractDescription($crawler){

        $description = $crawler->filter('div.item-info > div > p.desc')->each(function ($node) {
            $element = $node->text();
            return $element;
        });

        $this->finalArray['description'] = $description[0];

    }

    private function extractValue($crawler){

        $value = $crawler->filter('div.item-info > span.price')->each(function ($node) {
            $element = $node->text();
            return $element;
        });

        $this->finalArray['value'] = $value[0];

    }

    private function extractDetails($crawler){

        $details = $crawler->filter('div.attr-list > dl.row-print > dd > span')->each(function ($node) {
            $element = $node->extract(['title'])[0] . ': '.$node->text();
            return $element;
        });

        $this->finalArray['details'] = $details;

    }

    private function extractAccessories($crawler){

        $accessories = $crawler->filter('div.full-features > ul.list-styled > li > span')->each(function ($node) {
            $element = $node->text();
            return $element;
        });

        $this->finalArray['accessories'] = $accessories;

    }

    private function extractComments($crawler){

        $comment = $crawler->filter('div.full-content > p.description-print')->each(function ($node) {
            $element = $node->text();
            return $element;
        });

        $this->finalArray['comment'] = $comment[0];

    }

    private function extractImages($crawler){

        $images = $crawler->filter('div.gallery-thumbs > ul > li > img')->each(function ($node) {
            $element = $node->extract(['data-src']);
            return $element;
        });

        foreach ($images as &$img){
            $imagesFinal[] = $img[0];
        }

        $this->finalArray['img'] = $imagesFinal;
    }

    public function getFinalArray()
    {
        return $this->finalArray;
    }

}
