<?php
session_start();
date_default_timezone_set('Europe/Paris');

header("Access-Control-Allow-Origin: http://192.168.1.38:4200");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once ('../vendor/autoload.php');
use App\Service\ZbGraphLinkInstrumentService;
use App\Service\ZbLoginService;
use App\Service\ZbSearchInstrumentService;
use App\Service\EncryptService;

if($_SERVER['REQUEST_METHOD'] === 'GET'){

    if(isset($_GET['search'])){
        $zbSearchInstrumentService = new ZbSearchInstrumentService($_GET['search']);
        $zbInstruments = $zbSearchInstrumentService->getMatchInstruments();
        echo json_encode($zbSearchInstrumentService->expose($zbInstruments));
    }
    
    if(isset($_GET['id']) && isset($_GET['link'])){
        $zbGraphLinkInstrumentService = new ZbGraphLinkInstrumentService($_GET['link']);
        $zbInstrumentGraphLink = $zbGraphLinkInstrumentService->findGraphLink($_GET['id']);
        echo json_encode(["graph_link"=>$zbInstrumentGraphLink]);
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $post = $_POST ? $_POST : json_decode(file_get_contents('php://input'), true);

    if(isset($post['email'], $post['password'])){
        unset($_SESSION);
        $zbLoginService = new ZbLoginService();
        $encryptService = new EncryptService();
        $status = $zbLoginService->login($post['email'], $post['password'])->getLogStatus();
        
        $_SESSION['status'] = $status;
        if($status){
            $_SESSION['user'] = $zbLoginService->getEmail();
            $_SESSION['password'] = $encryptService->encrypt($post['password']);
        }else{
            $_SESSION['error'] = $zbLoginService->getLogError();
        }
        $session = $_SESSION;
        unset($session['password']);
        echo json_encode($session);
    }
}


?>
