<?php
namespace App\Service;

use Symfony\Component\Panther\Client;

abstract class ZbClient{
    protected $client;
    protected $crawler;
    protected $baseUrl = "https://www.zonebourse.com";

    public function __construct(string $additionalUrl = "")
    {
        $this->client = Client::createFirefoxClient("../drivers/geckodriver.exe", [
            // '--headless'
            ]); 
        $this->client->manage()->timeouts()->pageLoadTimeout(30);
        $this->crawler = $this->client->request('GET', $this->baseUrl . $additionalUrl);
    }
}