<?php

namespace App;

use Symfony\Component\HttpFoundation\Response;

class Utils
{
    public static function prepareJsonResponse($content)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($content));
        return $response;
    }
    
}