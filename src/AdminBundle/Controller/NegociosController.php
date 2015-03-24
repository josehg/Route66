<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Negocio;
use Symfony\Component\HttpFoundation\Request;

class NegociosController extends Controller
{

    public function listadoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $negocios = $em->getRepository('AppBundle:Negocio')->findAll();
        return $this->render('AdminBundle:Negocios:listado.html.twig' , array('negocios' => $negocios));
    }

    public function altaAction(  Request $request ){
        $entity = new Negocio();
        $entity->setLatitud(41.386717);
        $entity->setLongitud(2.169989);
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder($entity)
            ->add('nombre', 'text' )
            ->add('direccion', 'text')
            ->add('municipio', 'text')
            ->add('estado', 'text' , array('label' => 'Provincia'))
            ->add('codigo_postal', 'text', array('label' => 'Código postal'))
            ->add('latitud', 'hidden')
            ->add('longitud', 'hidden')
            ->add('telefono', 'text',array('label' => 'Teléfono'))
            ->add('telefono_movil', 'text', array('label' => 'Teléfono móvil'))
            ->add('descripcion', 'textarea', array('label' => 'Descripción'))
            ->add('categorias', 'entity', array(
                'label' => 'Categoría',
                'class' => 'AppBundle\Entity\Categoria', 'property' => 'nombre',
                'empty_value' =>'Escoje una categoria del negocio',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ))
            ->add('fileupload','file' , array('label'=>'Fotos', 'mapped'=>false , 'required' => false))
            ->add('save', 'submit', array('label' => 'Alta negocio'))
            ->getForm();
        $form->handleRequest( $request );
        if( $form->isValid() )
        {
            $entity->setEnabled(true);
            $em->persist( $entity );
            $em->flush();
            return $this->redirect($this->generateUrl('listado_negocios'));
        }

        return $this->render( 'AdminBundle:Negocios:alta_negocio.html.twig', array(
            'form' => $form->createView(),
            'negocio' => $entity
        ) );
    }

    public function editarAction(  Request $request , $id_negocio ){
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Negocio')->find($id_negocio);
        $form = $this->createFormBuilder($entity)
            ->add('id', 'hidden' , array('mapped'=> false) )
            ->add('nombre', 'text' )
            ->add('direccion', 'text')
            ->add('municipio', 'text')
            ->add('latitud', 'hidden')
            ->add('longitud', 'hidden')
            ->add('estado', 'text' , array('label' => 'Provincia'))
            ->add('codigo_postal', 'text', array('label' => 'Código postal'))
            ->add('telefono', 'text',array('label' => 'Teléfono'))
            ->add('telefono_movil', 'text', array('label' => 'Teléfono móvil'))
            ->add('descripcion', 'textarea', array('label' => 'Descripción'))
            ->add('categorias', 'entity', array(
                'label' => 'Categoría',
                'class' => 'AppBundle\Entity\Categoria', 'property' => 'nombre',
                'empty_value' =>'Escoje una categoria del negocio',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ))
            ->add('fileupload','file' , array('label'=>'Fotos', 'mapped'=>false , 'required' => false))
            ->add('save', 'submit', array('label' => 'Editar negocio'))
            ->getForm();
        $form->handleRequest( $request );
        if( $form->isValid() )
        {
            $entity->setEnabled(true);
            $em->persist( $entity );
            $em->flush();
            return $this->redirect($this->generateUrl('listado_negocios'));
        }

        return $this->render( 'AdminBundle:Negocios:editar_negocio.html.twig', array(
            'form' => $form->createView(),
            'negocio' => $entity
        ) );
    }

    public function activarAction( $id_negocio ){
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository('AppBundle:Negocio')->find($id_negocio);

        if (!$negocio) {
            throw $this->createNotFoundException(
                'No se ha encontrado el negocio con id '.$id_negocio
            );
        }
        $negocio->setEnabled(true);
        $em->persist( $negocio );
        $em->flush();
        return $this->redirect($this->generateUrl('listado_negocios'));
    }

    public function desactivarAction( $id_negocio ){
        $em = $this->getDoctrine()->getManager();
        $negocio = $em->getRepository('AppBundle:Negocio')
            ->find($id_negocio);

        if (!$negocio) {
            throw $this->createNotFoundException(
                'No se ha encontrado el negocios con id '.$id_negocio
            );
        }
        $negocio->setEnabled(false);
        $em->persist( $negocio );
        $em->flush();
        return $this->redirect($this->generateUrl('listado_negocios'));

    }
}
