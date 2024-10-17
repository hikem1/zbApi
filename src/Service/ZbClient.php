<?php

namespace App\Service;

use Symfony\Component\Panther\Client;

class ZbClient {

    protected $client;
    protected $crawler;
    protected $url = "https://www.zonebourse.com";
    private $logStatus = false;
    private $logError = "";

    public function __construct()
    {
        $this->client = Client::createFirefoxClient("../../drivers/geckodriver.exe", [
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
            $this->client->waitFor('#dropdownLoged', 1);
            $this->logStatus = true;
        } catch (\Throwable) {
            $this->logError = $this->crawler->filter('.notification__title')->getText();
        }
    }
    
}