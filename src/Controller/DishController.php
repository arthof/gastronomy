<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Dish;
use App\Utils;

class DishController extends AbstractController
{
    
    /**
     * @Route("/dish/{id}", name="dish_read", requirements={"id"="\d+"}, methods={"GET"})
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
            return Utils::prepareJsonResponse($responseContent);
        }
        
        $em = $this->getDoctrine()->getManager();
        $dish = $em->getRepository(Dish::class)->find($id);
        
        if(is_null($dish))
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Dish by given ID doesn\'t exist.';
            return Utils::prepareJsonResponse($responseContent);
        }
        
        $responseContent['dish'] = [
            'id' => $dish->getId(),
            'name' => $dish->getName(),
        ];
        
        return Utils::prepareJsonResponse($responseContent);
    }
    
    /**
     * @Route("/dish/list", name="dish_list", methods={"GET"})
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
                        
        return Utils::prepareJsonResponse($responseContent);
    }
    
    /**
     * @Route("/dish/create", name="dish_create", methods={"POST"})
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
            return Utils::prepareJsonResponse($responseContent);
        }
        
        if($request->getContent()=='')
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Request can\'t be empty.';
            return Utils::prepareJsonResponse($responseContent);
        }
        
        $data = json_decode($request->getContent());
        
        if(!property_exists($data, 'name'))
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Name field not found.';
            return Utils::prepareJsonResponse($responseContent);
        }
        
        $dish = new Dish();
        $dish->setName($data->name);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($dish);
        $result = $em->flush();
        
        if($dish->getId())
        {
            $responseContent['message'] = 'Dish created, its ID: '. $dish->getId();
        }
        else
        {
            $responseContent['message'] = 'Dish couln\'t be created';
        }
        
        return Utils::prepareJsonResponse($responseContent);
    }
    
    /**
     * @Route("/dish/create-bulk", name="dish_create_bulk", methods={"POST"})
     */
    public function createBulk(Request $request)
    {
        $responseContent = array(
            'error' => 0,
            'message' => '',
        );
        
        if($request->getContentType()!='json')
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Content type must by JSON.';
            return Utils::prepareJsonResponse($responseContent);
        }
        
        if($request->getContent()=='')
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Request can\'t be empty.';
            return Utils::prepareJsonResponse($responseContent);
        }
        
        $data = json_decode($request->getContent());
        
        $em = $this->getDoctrine()->getManager();
        $recordsCreated = 0;
        if($data)
        {
            foreach($data as $key => $val)
            {
                if(!(property_exists($val, 'name') && property_exists($val, 'price')))
                    continue;
                $dish = new Dish();
                $dish->setName($val->name);
                $dish->setPrice($val->price);
                $em->persist($dish);
                $recordsCreated++;
            }
        }
        
        $em->flush();
        
        if($recordsCreated)
        {
            $responseContent['message'] = 'Created records: '. $recordsCreated;
        }
        else
        {
            $responseContent['message'] = 'Couldn\'t create any records.';
        }
        
        return Utils::prepareJsonResponse($responseContent);
    }
    
    /**
     * @Route("/dish/update/{id}", name="dish_update", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function update($id, Request $request)
    {
        $responseContent = array(
            'error' => 0,
            'message' => '',
        );
        
        if($request->getContentType()!='json')
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Content type must by JSON.';
            return Utils::prepareJsonResponse($responseContent);
        }
        
        if((int)$id<=0)
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Id can\'t be negative';
            return Utils::prepareJsonResponse($responseContent);
        }
        
        $em = $this->getDoctrine()->getManager();
        $dish = $em->getRepository(Dish::class)->find($id);
        
        if(is_null($dish))
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Dish by given ID doesn\'t exist.';
            return Utils::prepareJsonResponse($responseContent);
        }
       
        if($request->getContent()=='')
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Request can\'t be empty.';
            return Utils::prepareJsonResponse($responseContent);
        }
        
        $data = json_decode($request->getContent());
        
        if(property_exists($data, 'name'))
            $dish->setName($data->name);
        
        if(property_exists($data, 'price'))
            $dish->setPrice($data->price);
        
        //save changes
        $em->flush();
        
        $responseContent['message'] = 'Dish updated.';
        
        return Utils::prepareJsonResponse($responseContent);
    }
    
}













