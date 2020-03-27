<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index")
     */
    public function index(SessionInterface $session, ProductRepository $productRepository)
    {
            try {
        $panier = $session->get('panier', []);

        $panierInfo =[];

        foreach($panier as $id => $quantity) {
            $panierInfo[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;

        foreach($panierInfo as $item) {
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }
    } catch (\Exception $e){ 
        return $this->render('cart/index.html.twig', [
            'items' => $panierInfo,
            'total' => $total
        ]);
    }
    }

    /**
     * @Route("/panier/add/{id}", name="cart_add")
     */
    public function add($id, SessionInterface $session) 
    {

        try {
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])) {
            $panier[$id]++;
        } else {$panier[$id] = 1;}

        $session->set('panier', $panier);
    } catch (\Exception $e){
        return $this->redirectToRoute("cart_index");
    }
    }

    /**
     * @Route("/panier/remove/{id}", name="cart_remove")
     */

    public function remove($id, SessionInterface $session) {

        try {
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);
    } catch (\Exception $e){
        return $this->redirectToRoute("cart_index");
     }
    }
}
