<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Categoria;
use Symfony\Component\HttpFoundation\Request;

class CategoriasController extends Controller
{

    public function listadoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categorias = $em->getRepository('AppBundle:Categoria')->findAll();
        return $this->render('AdminBundle:Categorias:listado.html.twig' , array('categorias' => $categorias));
    }

    public function addAction(  Request $request ){
        $entity = new Categoria();

        $em = $this->getDoctrine()->getManager();
        $categorias = $em->getRepository('AppBundle:Categoria')->findAll();
        $form = $this->createFormBuilder($entity)
            ->add('nombre', 'text', array(
                'label' => 'Nombre'
            ))
            ->add('parent', 'entity', array(
                'label' => 'Categoría padre',
                'class' => 'AppBundle\Entity\Categoria', 'property' => 'nombre',
                'empty_value' =>'Escoje una categoria padre',
                'required' => false,
                'choices' => $this->mostrarJerarquía($categorias)
            ))
            ->add('Guardar', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em->persist( $entity );
            $em->flush();
            return $this->redirect($this->generateUrl('listado_categorias'));
        }

        return $this->render( 'AdminBundle:Categorias:add_categoria.html.twig', array(
            'form' => $form->createView(),
        ) );
    }

    private function mostrarJerarquía($categorias) {
        $categorias_new = array();
        foreach ($categorias as $categoria) {
            $categorias_new[] = $categoria->setNombre($this->getDashes($categoria) . $categoria->getNombre());
        }
        return $categorias_new;
    }

    private function getDashes($categoria) {
        if ($categoria->getParent() == null) {
            return '';
        } else {
            return '- ' . $this->getDashes($categoria->getParent());
        }
    }


}
