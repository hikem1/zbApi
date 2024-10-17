<?php
namespace App\Service;

use Symfony\Component\Panther\Client;
use App\Service\EncryptService;

class ZbGraphLinkInstrumentService{

    protected $client;
    protected $crawler;
    protected $baseUrl = "https://www.zonebourse.com";
    private $url;
    private $iframeSelector;
    private $logStatus = false;
    private $logError = "";
    private $link;
    private $id;

    public function __construct($params)
    {
        $this->link = $params['link'];
        $this->id = $params['id'];
        $this->iframeSelector = "#prt_dynamic_chart_" . $this->id;
        $this->url = $this->baseUrl . $this->link . "graphiques/";

        $this->client = Client::createFirefoxClient("../drivers/geckodriver.exe", [
        // // '--headless'
        ]); 
        $this->crawler = $this->client->request('GET', $this->url);
    }

    
    public function getLogError(): string
    {
        return $this->logError;
    }

    public function log(string $email, string $password): bool
    {
        $encryptService = new EncryptService();
        $decryptedPass = $encryptService->decrypt($password);

        $this->hideCookiesIframe();
        $this->submitCredentials($email, $decryptedPass);
        $this->setLogStatus();

        return $this->logStatus;
    }

    private function hideCookiesIframe(): void
    {
        $this->client->waitFor('#appconsent', 1)->children('iframe');
        $iframe = $this->crawler->filter('#appconsent')->children('iframe');
        $this->client->executeScript('arguments[0].style.display = "none";', [$iframe->getElement(0)]);
    }

    private function submitCredentials(string $email, string $password): void
    {
        $loginIpt = $this->crawler->filter('[name=login]');
        $this->client->executeScript('arguments[0].setAttribute("value", "' . $email . '");', [$loginIpt->getElement(0)]);
        
        $passwordIpt = $this->crawler->filter('[name=password]');
        $this->client->executeScript('arguments[0].setAttribute("value", "' . $password . '");', [$passwordIpt->getElement(0)]);
        
        $connexionBtn = $this->crawler->filter('.btn--white--outter');
        $connexionBtn->click();
        
        $submitBtn = $this->crawler->filter('#loginForm')->children('button');
        $submitBtn->click();
    }

    private function setLogStatus(): void
    {
        try {
            $this->client->waitFor('#dropdownLoged');
            $this->logStatus = true;
        } catch (\Throwable) {
            $this->logError = $this->crawler->filter('.notification__title')->getText();
        }
    }
    public function findGraphLink(): string
    {
        try {
            $graphLink = $this->crawler->filter($this->iframeSelector)->getAttribute("src");
            return htmlspecialchars_decode($graphLink);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }

}