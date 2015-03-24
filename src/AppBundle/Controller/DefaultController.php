<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Negocio;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $negocios = $em->getRepository('AppBundle:Negocio')->findAll();
        $categorias = $em->getRepository('AppBundle:Categoria')->findAll();
        return $this->render('AppBundle:Default:index.html.twig' , array( 'negocios' => $negocios, 'categorias' => $categorias ));

    }
}
