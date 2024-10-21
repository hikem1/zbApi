<?php

namespace App\Service;

class ZbLoginService extends ZbClient {

    private $logStatus = false;
    private $logError = "";
    private $email;


    public function __construct()
    {
        parent::__construct();
    }

    public function getEmail(){
        return $this->email;
    }

    public function getLogError(): string
    {
        return $this->logError;
    }

    public function login(string $email, string $password): self
    {
        $this->email = $email;
        $this->hideCookiesIframe();
        $this->submitCredentials($this->email, $password);

        return $this;
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

    public function getLogStatus(): bool
    {
        try {
            $this->client->waitFor('#dropdownLoged', 1);
            $this->logStatus = true;
        } catch (\Throwable) {
            $this->logError = $this->crawler->filter('.notification__title')->getText();
        }
        return $this->logStatus;
    }
    
}