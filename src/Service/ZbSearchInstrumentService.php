<?php
namespace App\Service;

use PHPHtmlParser\Dom;
use App\Model\ZbInstrument;
use App\Service\StringUtilities;

class ZbSearchInstrumentService{

    private string $url = "https://www.zonebourse.com/recherche/instruments?q=";
    private string $searchEndUrl = '&vue=company';
    private array $matchInstruments = [];
    
    public function __construct($keyword)
    {
        $domPage = $this->getDomPage($keyword);
        $domRows = $this->getDomRows($domPage);
        $this->matchInstruments = $this->buildInstruments($domRows);
    }
    private function getDomPage($keyword){
        $dom = new Dom();
        return $dom->loadFromUrl($this->url . strtoupper($keyword) . $this->searchEndUrl);
    }
    private function getDomRows($domPage){
        $tableContainer = $domPage->getElementById('advanced-search__instruments');
        $table = $tableContainer->find('tbody');
        return $table->find('tr');
    }
    private function buildInstruments($domRows)
    {
        $instruments = [];
        foreach($domRows as $row){
            $instrument = new ZbInstrument();
            $instrument
            ->setId(intval($row->find('a')[1]->getAttribute('data-code')))
            ->setName(htmlspecialchars_decode($row->find('a')[0]->text()))
            ->setCode(StringUtilities::removeStartEndWhiteSpaces($row->find('td')[2]->text()))
            ->setLink($row->find('a')[0]->getAttribute('href'))
            ->setExchange_place(StringUtilities::removeStartEndWhiteSpaces($row->find('td')[3]->find('p')->text()));
            $instruments[] = $instrument;
        }
        return $instruments;
    }
    public function getMatchInstruments(){
        return $this->matchInstruments;
    }
        /** 
    * @param ZbInstrument[]
    * @return string[]
    **/
    public function serialize(array $zbInstruments): array
    {
        $serialized = [];
        foreach ($zbInstruments as $zbInstrument) {
            $serialized[] = serialize($zbInstrument);
        }
        return $serialized;
    }
        /** 
    * @param ZbInstrument[]
    * @return array[]
    **/
    public function expose(array $zbInstruments): array
    {
        $expozed = [];
        foreach ($zbInstruments as $zbInstrument) {
            $expozed[] = $zbInstrument->__toArray();
        }
        return $expozed;
    }
}