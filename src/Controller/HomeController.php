<?php

namespace App\Controller;
use App\Entity\Admin\Messages;
use App\Entity\Admin\Setting;
use App\Entity\User;
use App\Form\Admin\MessagesType;
use App\Form\UsersType;
use App\Repository\Admin\CategoryRepository;
use App\Repository\Admin\ProductRepository;
use App\Repository\Admin\SettingRepository;
use App\Repository\Admin\ImageRepository;
use App\Repository\UserRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\Cloner\Data;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @param $settingRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SettingRepository $settingRepository,CategoryRepository $categoryRepository)
    {
        $data = $settingRepository->findAll();
        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT * FROM product WHERE status='True' AND encoksatanlar='True' ORDER BY ID DESC LIMIT 3";
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $sliders = $statement->fetchAll();

        $sorgu = "SELECT * FROM product WHERE status='True' AND encoksatanlar='True' ORDER BY ID ";
        $statement = $em->getConnection()->prepare($sorgu);
        $statement->execute();
        $tercih = $statement->fetchAll();


        $cats = $this->categorytree();
        $cats[0]='<ul id="menu-v">';

        return $this->render('home/index.html.twig', [
            'data' => $data,
            'cats'=>$cats,
            'sliders'=>$sliders,
            'tercih'=> $tercih,
        ]);
    }

    public function categorytree($parent = 0 ,$user_tree_array = '')
    {
        if(!is_array($user_tree_array))
            $user_tree_array=array();
        $em = $this->getDoctrine()->getManager();
        $sql = "SELECT * FROM category WHERE status='True' AND parentid=".$parent;
        $statement = $em->getConnection()->prepare($sql);
        //  $statement->bindValue('parentid',$parent);
        $statement->execute();
        $result = $statement->fetchAll();

        if(count($result) > 0)
        {
            $user_tree_array[]="<ul>";
            foreach ($result as $row)
            {
                $user_tree_array[] = "<li><a href='/category/".$row['id']."'>".$row['title']."</a>";
                $user_tree_array = $this->categorytree($row['id'], $user_tree_array);
            }
            $user_tree_array[]= "</li></ul>";
        }
        return $user_tree_array;
    }
    /**
     * @Route("/category/{catid}", name="category_products", methods="GET")
     */
    public function CategoryProducts( $catid, CategoryRepository $categoryRepository)
    {
        $cats = $this->categorytree();
        $cats[0]='<ul id="menu-v">';
        $data = $categoryRepository->findBy(
            ['id' => $catid]
        );
        $em = $this->getDoctrine()->getManager();
        $sql = 'SELECT * FROM product WHERE status="True" AND categoryid = :catid';
        $statement = $em->getConnection()->prepare($sql);
        $statement->bindValue('catid' , $catid);
        $statement->execute();
        $products=$statement->fetchAll();

        return $this->render('home/products.html.twig', [
            'data' => $data,
            'products'=>$products,
            'cats'=>$cats,

        ]);
    }

    /**
     * @Route("/product/{id}", name="product_detail", methods="GET")
     */
    public function ProductDetail( $id, ProductRepository $productrepository,ImageRepository $imageRepository)
    {
        $data = $productrepository->findBy(
            ['id' => $id]
        );
        $images= $imageRepository->findBy(
            ['product_id' => $id]
        );

        $cats = $this->categorytree();
        $cats[0]='<ul id="menu-v">';
        return $this->render('home/product_detail.html.twig', [
            'data' => $data,
            'images' => $images,
            'cats' => $cats,

        ]);
    }

    /**
     * @Route("/hakkimizda", name="hakkimizda")
     * @param SettingRepository $settingRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function hakkimizda(SettingRepository $settingRepository)
    {

        $cats = $this->categorytree();
        $cats[0]='<ul id="menu-v">';
        $data=$settingRepository->findAll();
        return $this->render('home/hakkimizda.html.twig', [
            'data' => $data,
            'cats' => $cats,

        ]);
    }

    /**
     * @Route("/referanslar", name="referanslar")
     * @param SettingRepository $settingRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function referanslar(SettingRepository $settingRepository)
    {
        $cats = $this->categorytree();
        $cats[0]='<ul id="menu-v">';
        $data=$settingRepository->findAll();
        return $this->render('home/referanslar.html.twig', [
            'cats' => $cats,
            'data' => $data,
        ]);
    }
    /**
     * @Route("/iletisim", name="iletisim")
     */
    public function iletisim(SettingRepository $settingRepository,Request $request)
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);
        $submittedToken =$request ->request->get('token');
        if($form->isSubmitted()) {
            if ($this->isCsrfTokenValid('form-messeage',$submittedToken)){
                $em = $this->getDoctrine()->getManager();
                $em->persist($message);
                $em->flush();
                $this->addFlash('success', 'Kaydetme İşlemi Başarıyla Tamamlanmıştır.');
                return $this->redirectToRoute('iletisim');
            }
        }
        $cats = $this->categorytree();
        $cats[0]='<ul id="menu-v">';
        $data=$settingRepository->findAll();
        return $this->render('home/iletisim.html.twig', [
            'data' => $data,
            'cats' => $cats,
            'message' => $message,
        ]);
    }
    /**
     * @Route("/newuser", name="new_user")
     */
    public function newuser(Request $request,UserRepository $usersRepository):Response
    {
        $cats = $this->categorytree();
        $cats[0]='<ul id="menu-v">';
        $users = new User();
        $form = $this->createForm(UsersType::class,$users);
        $form->handleRequest($request);

        $submittedToken = $request->request->get('token');
        if($this->isCsrfTokenValid('user-form',$submittedToken)) {
            if ($form->isSubmitted()) {
                $emaildata = $usersRepository->findBy(
                    ['email' => $users->getEmail()]
                );
                if($emaildata==null)
                {
                    $em = $this->getDoctrine()->getManager();
                    $users->setRoles("ROLE_USER");
                    $users->setStatus("true");
                    $em->persist($users);
                    $em->flush();
                    $this->addFlash('success', 'Üye Olundu!');
                    return $this->redirectToRoute('app_login');
                }
                else
                {
                    $this->addFlash('error', $users->getEmail()." email adresi var.");
                    //return $this->redirectToRoute('new_user');
                }
            }
        }
        return $this->render('home/newuser.html.twig', [
            'users' => $users,
            'cats' => $cats,
            'form' => $form->createView(),
        ]);
    }
}
