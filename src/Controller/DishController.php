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
     * @Route("/dish/{id}", name="dish_read")
     */
    public function read($id, Request $request)
    {
        $responseContent = array(
            'error' => 0,
            'dish' => [],
        );
        
        if((int)$id<=0)
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Id can\'t be negative';
            return $this->prepareJsonResponse($responseContent);
        }
        
        $em = $this->getDoctrine()->getManager();
        $dish = $em->getRepository(Dish::class)->find($id);
        
        if(is_null($dish))
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Dish by given ID doesn\'t exist.';
            return $this->prepareJsonResponse($responseContent);
        }
        
        $responseContent['dish'] = [
            'id' => $dish->getId(),
            'name' => $dish->getName(),
        ];
        
        return $this->prepareJsonResponse($responseContent);
    }
    
    /**
     * @Route("/dish/list", name="dish_list")
     */
    public function list(Request $request)
    {
        $responseContent = array(
            'error' => 0,
            'dishes' => [],
        );
        
        $em = $this->getDoctrine()->getManager();
        $dishes = $em->getRepository(Dish::class)->findAll();
        
        for($i=0, $max_i = count($dishes); $i<$max_i; $i++)
        {
            $responseContent['dishes'][] = [
                'id' => $dishes[$i]->getId(),
                'name' => $dishes[$i]->getName(),
            ];
        }
                        
        return $this->prepareJsonResponse($responseContent);
    }
    
    /**
     * @Route("/dish/create", name="dish_create")
     */
    public function create(Request $request)
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













