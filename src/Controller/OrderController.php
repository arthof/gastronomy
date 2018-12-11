<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Order;
use App\Entity\Dish;
use App\Entity\OrderDish;

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
            return $this->prepareJsonResponse($responseContent);
        }
        
        if($request->getContent()=='')
        {
            $responseContent['error'] = 1;
            $responseContent['message'] = 'Request can\'t be empty.';
            return $this->prepareJsonResponse($responseContent);
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
            $orderDish->setAssociatedOrder($order);
            $em->persist($orderDish);
        }
                
        $em->flush();
        
        if($order->getId())
        {
            $responseContent['message'] = 'Order created, its ID: '. $order->getId();
        }
        else
        {
            $responseContent['message'] = 'Dish couln\'t be created';
        }
        
        return $this->prepareJsonResponse($responseContent);   
    }
}
