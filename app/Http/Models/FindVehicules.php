<?php

namespace App\http\models;

use Illuminate\Database\Eloquent\Model;
use Goutte\Client;


class FindVehicules extends Model
{

    private $finalArray;

    public function crawlerAll($urlBase,$urlFinal){

        $client = new Client();

        $crawler = $client->request('GET', $urlFinal);

        $this->extractId($crawler);
        $this->extractNames($crawler);
        $this->extractVersion($crawler);
        $this->extractPrice($crawler);
        $this->extractLink($crawler,$urlBase);
        $this->extractYear($crawler);
        $this->extractKilometer($crawler);
        $this->extractGearBox($crawler);
        $this->extractAcessories($crawler);

        $page = $this->numberOfPages($crawler);

        return response(json_encode([
            "totalPages" => $page[1][0],
            "currentPage" => $page[0][0],
            "Result" => $this->getFinalArray(),
            ],JSON_UNESCAPED_SLASHES), 200);
    }

    private function extractId($crawler){

        $ids = $crawler->filter('meta[itemprop="productID"]')->each(function ($node) {
            $element = $node->extract(['content']);
            return $element[0];
        });

        foreach ($ids as $key => &$id){
            $this->finalArray[$key]['id'] = $id;
        }

    }

    private function extractNames($crawler){

        foreach ($crawler->filter('.card-title') as $key => &$name){
            $this->finalArray[$key]['name'] = $name->textContent;
        }
    }

    private function extractVersion($crawler){

        foreach ($crawler->filter('.card-subtitle') as $key => &$version){
            $this->finalArray[$key]['version'] = substr($version->textContent,10);
        }
    }

    private function extractPrice($crawler){

        foreach ($crawler->filter('.card-price') as $key => &$price){
            $this->finalArray[$key]['price'] = $price->textContent;
        }
    }

    private function extractLink($crawler,$urlBase){

        $links = $crawler->filter('.card-content > a')->filterXPath('//a[contains(@href, "")]')->each(function ($node) {
            return $element[] = $node->extract(['href'])[0];
        });

        foreach ($links as $key => &$link){
            $this->finalArray[$key]['link'] = $urlBase . $link;
        }
    }

    private function extractYear($crawler){

        foreach ($crawler->filter("ul.list-features > li[title='Ano de fabricação']") as $key => &$year){
            $this->finalArray[$key]['year'] = $year->textContent;
        }
    }

    private function extractKilometer($crawler){

        foreach ($crawler->filter("ul.list-features > li[title='Kilometragem atual'] > b") as $key => &$kilometer){
            $this->finalArray[$key]['kilometer'] = $kilometer->textContent;
        }
    }

    private function extractGearBox($crawler){

        foreach ($crawler->filter("ul.list-features > li[title='Tipo de câmbio']") as $key => &$gearBox){
            $this->finalArray[$key]['gearBox'] = $gearBox->textContent;
        }
    }

    private function extractAcessories($crawler){

        foreach ($crawler->filter("ul.list-inline") as $key => &$acessorie){
            $this->finalArray[$key]['acessories'] = $acessorie->textContent;
        }
    }

    private function numberOfPages($crawler)
    {

        $result = $crawler->filter('div.info > b')->each(function ($node) {
            $element[] = trim($node->text());
            return $element;
        });

        return $result;
    }

    public function getFinalArray()
    {
        return $this->finalArray;
    }

}
