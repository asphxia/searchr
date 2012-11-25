<?php
require_once __DIR__.'/../vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;

$app = new Silex\Application();

$app['debug'] = true;

$app->get('/', function(Silex\Application $app, Request $request) {
    $decryptors = array('Web\Noisette','Web\Myaddr','Web\Decrypt','Web\Md5decrypter','Web\Google', 'Web\Gromweb');

    $result = array();
    $result['hash'] = $request->get('hash');
    foreach($decryptors as $decrytor)
    {
        if (NULL !== ($plain = Asphxia\Md5Decryptor\Md5Decryptor::plain($hash, $decrytor))) {
            $result['plain'] = $plain;
            $result['decryptor'] = $decryptor;
            break;
        }
    }
    return $app->json($result);
});

$app->run();