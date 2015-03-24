<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Negocio;
use Symfony\Component\HttpFoundation\Request;

class NegociosController extends Controller
{

    public function detalleAction( $id_negocio )
    {
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository('AppBundle:Negocio')->find( $id_negocio );
        return $this->render('AppBundle:Negocios:detalle.html.twig' , array('negocio' => $negocio));
    }

}
