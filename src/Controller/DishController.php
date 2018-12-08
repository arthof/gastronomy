<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DishController extends AbstractController
{
    /**
     * @Route("/dish/list", name="dish_list")
     */
    public function dish_lis(Request $request)
    {
        
        
        $response = new Response();
        $response->setContent(json_encode([
            [
                'id' => 1,
                'name' => 'Salmon',
            ],
            [
                'id' => 2,
                'name' => 'Pizza',
            ],
            
        ]));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
}
