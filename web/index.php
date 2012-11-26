<?php
require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app['debug'] = true;

$app->get('/', function(Silex\Application $app, Request $request) {
    $decryptors = array('Web\Noisette','Web\Myaddr','Web\Decrypt','Web\Md5decrypter','Web\Google', 'Web\Gromweb');

    $result = array();
    $splitter = $request->get('separator') ? $request->get('separator') : '|';
    $hashes = $request->get('hash');
    
    if (strpos($hashes, $splitter) !== false) {
        $hashes = explode($splitter, $hashes);
    }else{
        $hashes = array($hashes);
    }

    foreach ($hashes as $hash)
    {
        $result[]['hash'] = $hash;
        foreach($decryptors as $decryptor)
        {
            if (NULL !== ($plain = Asphxia\Md5Decryptor\Md5Decryptor::plain($hash, $decryptor))) {
                $result[]['plain'] = $plain;
                $result[]['decryptor'] = $decryptor;
                break;
            }
        }
    }
    return $app->json($result);
});

$app->run();