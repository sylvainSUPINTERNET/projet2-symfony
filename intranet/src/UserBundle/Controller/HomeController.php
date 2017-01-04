<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        $currentUserRole = $this->get('security.token_storage')->getToken()->getUser()->getRoles();
        $role = end($currentUserRole);
        if($role == "ROLE_ADMIN"){
            var_dump("teacher");
        }else if($role == "ROLE_SUPER_ADMIN"){
            var_dump("admin");
        }else{
            var_dump("student");
        }
        return $this->render('UserBundle:Home:index.html.twig',array("role" => $role));
    }
}
