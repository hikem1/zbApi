<?php
session_start();
date_default_timezone_set('Europe/Paris');
header("Access-Control-Allow-Origin: *");

require_once ('../vendor/autoload.php');
use App\Service\ZbGraphLinkInstrumentService;
use App\Service\ZbClient;
use App\Service\EncryptService;
use App\Service\ZbSearchInstrumentService;

if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['search'])){
        $zbSearchInstrumentService = new ZbSearchInstrumentService($_GET['search']);
        $zbInstruments = $zbSearchInstrumentService->getMatchInstruments();
        echo json_encode($zbSearchInstrumentService->expose($zbInstruments));
    }
    
    if(isset($_GET['id']) && isset($_GET['link'])){
        $zbGraphLinkInstrumentService = new ZbGraphLinkInstrumentService($_GET);
        $zbInstrumentGraphLink = $zbGraphLinkInstrumentService->findGraphLink();
        echo json_encode(["graph_link"=>$zbInstrumentGraphLink]);
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $post = json_decode(file_get_contents('php://input'), true);

    if(isset($post['email']) && isset($post['password'])){
        unset($_SESSION['session']);

        $encryptService = new EncryptService();
        $encryptService = $encryptService->encrypt($post['password']);

        $zbClient = new ZbClient();
        $status = $zbClient->log($post['email'], $encryptService->getEncrypt());
        
        $_SESSION['session']['status'] = $status;

        if($status){
            $_SESSION['session']['user']['email'] = $post['email'];
            $_SESSION['session']['user']['password'] = $encryptService->getEncrypt();
        }else{
            $_SESSION['session']['error'] = $zbClient->getLogError();
        }
        echo json_encode($_SESSION);
    }
}


?>
