<?php

namespace App\Controller\Admin;

use App\Entity\Orders;
use App\Repository\OrderDetailRepository;
use App\Repository\OrdersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/admin/orders/{slug}", name="admin_orders_index")
     */
    public function orders($slug, OrdersRepository $ordersRepository)
    {
        $orders = $ordersRepository->findBy(['status'=> $slug]);

        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orders,
        ]);
    }
    /**
     * @Route("/admin/orders/show/{id}", name="admin_orders_show" )
     */
    public function show($id,Orders $order,OrderDetailRepository $orderDetailRepository):Response
    {
        $orderdetail = $orderDetailRepository->findBy(['orderid'=> $id]);
        return $this->render('admin/orders/show.html.twig', [
            'order' => $order,
            'orderdetail' => $orderdetail,
        ]);
    }

    /**
     * @Route("/admin/orders/update/{id}", name="admin_orders_update" ,methods="POST")
     */
    public function order_update($id,Request $request,Orders $order):Response
    {
        $shipinfo = $request->get('shipinfo');
        $note = $request->get('note');
        $status = $request ->get('status');
        $em = $this->getDoctrine()->getManager();
        $sql= "UPDATE orders SET  shipinfo=:shipinfo, note=:note, status=:status WHERE id=:id";
        $statement = $em->getConnection()->prepare($sql);
        $statement -> bindValue('shipinfo', $shipinfo);
        $statement -> bindValue('note', $note);
        $statement -> bindValue('status', $status);
        $statement -> bindValue('id', $id);
        $statement->execute();
        $this->addFlash('success','Sipariş Bilgileri Güncellenmiştir.');
        return $this->redirectToRoute('admin_orders_show',array('id' => $id));
    }

}
