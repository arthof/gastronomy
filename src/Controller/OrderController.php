<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order;
use App\Entity\Dish;
use App\Entity\OrderDish;
use App\Utils;

class OrderController extends AbstractController
{
    /**
     * @Route("/order/create", name="order_create", methods={"POST"})
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
        
        $em = $this->getDoctrine()->getManager();
        $order = new Order();
        $em->persist($order);
        
        $repo = $em->getRepository(Dish::class);
        
        for($i=0, $max_i=count($data->dishes); $i<$max_i; $i++)
        {
            $dish = $repo->find($data->dishes[$i]->id);
            if(is_null($dish))
                continue;
            $orderDish = new OrderDish();
            $orderDish->setFromDish($dish);
            $em->persist($orderDish);
            $order->addOrderDish($orderDish);
        }
        
        $order->calculatePrice();
        
        $em->flush();
        
        if($order->getId())
        {
            $order->setName($order->getName() . $order->getId());
            $em->flush();
            
            $responseContent['message'] = 'Order created, its ID: '. $order->getId();
        }
        else
        {
            $responseContent['message'] = 'Dish couln\'t be created';
        }
        
        return Utils::prepareJsonResponse($responseContent);
    }
    
    /**
     * @Route("/order/{id}", name="order_read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id, Request $request)
    {
        $responseContent = array(
            'error' => 0,
            'order' => [],
        );
        
        if((int)$id<=0)
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Id can\'t be negative';
            return Utils::prepareJsonResponse($responseContent);
        }
        
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->find($id);
        
        if(is_null($order))
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Order by given ID doesn\'t exist.';
            return Utils::prepareJsonResponse($responseContent);
        }
        
        $responseContent['order'] = [
            'id' => $order->getId(),
            'name' => $order->getName(),
            'dish' => [],
        ];
        
        $orderDishes = $order->getOrderDishes();
        for($i=0, $max_i = count($orderDishes); $i<$max_i; $i++)
        {
            $responseContent['order']['dish'][] = $orderDishes[$i]->getData();
        }
        
        return Utils::prepareJsonResponse($responseContent);
    }
}
