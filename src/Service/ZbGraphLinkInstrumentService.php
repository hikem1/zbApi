<?php
namespace App\Service;

class ZbGraphLinkInstrumentService extends ZbClient{

    public function __construct(string $link)
    {
        $additionalUrl = $link . "graphiques/";
        parent::__construct($additionalUrl);
    }
    
    public function findGraphLink(int $id): string
    {
        $iframeSelector = "#prt_dynamic_chart_" . $id;
        $this->client->waitFor($iframeSelector);

        try {
            $graphLink = $this->crawler->filter($iframeSelector)->getAttribute("src");
            return htmlspecialchars_decode($graphLink);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }

}