<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Dish;

class DishController extends AbstractController
{
    /**
     * @Route("/dish/list", name="dish_list")
     */
    public function dishList(Request $request)
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
    
    /**
     * @Route("/dish/create", name="dish_create")
     */
    public function dishCreate(Request $request)
    {
        $responseContent = array(
            'error' => 0,
            'message' => '',
        );
        
        if($request->getContentType()!='json')
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Content type must by JSON.';
            return $this->prepareJsonResponse($responseContent);            
        }
        
        if($request->getContent()=='')
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Request can\'t be empty.';
            return $this->prepareJsonResponse($responseContent);    
        }
        
        $data = json_decode($request->getContent());
        
        if(!property_exists($data, 'name'))
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Name field not found.';
            return $this->prepareJsonResponse($responseContent);
        }
        
        $dish = new Dish();
        $dish->setName($data->name);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($dish);
        $result = $em->flush();
        
        if($dish->getId())
        {
            $responseContent['message'] = 'Dish created, it\'s ID: '. $dish->getId();
        }
        else
        {
            $responseContent['message'] = 'Dish couln\'t be created';
        }
        
        return $this->prepareJsonResponse($responseContent);            
    }
    
    
    public function prepareJsonResponse($content)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($content));
        return $response;
    }
    
    
}













