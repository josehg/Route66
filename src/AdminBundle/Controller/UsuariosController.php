<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UsuariosController extends Controller
{

    public function listadoAction()
    {
        $usuarios = $this->findNotByRole('ROLE_ADMIN');
        return $this->render('AdminBundle:Usuarios:listado.html.twig' , array('usuarios' => $usuarios));
    }

    protected function findNotByRole($role) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.roles NOT LIKE :roles')
            ->setParameter('roles', '%"' . $role . '"%');
        return $qb->getQuery()->getResult();
    }

    public function altaAction(  Request $request ){
        $entity = new User();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder($entity)
            ->add('username', 'text')
            ->add('email', 'text')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repetir Password'),
            ))
            ->add('save', 'submit', array('label' => 'Alta usuario'))
            ->getForm();

        $form->handleRequest( $request );

        if( $form->isValid() )
        {
            $factory = $this->get( 'security.encoder_factory' );
            $encoder = $factory->getEncoder( $entity );
            $password = $encoder->encodePassword( $entity->getPassword(), $entity->getSalt() );
            $entity->setPassword( $password );
            $entity->addRole('ROLE_USER');
            $entity->setEnabled(true);
            $em->persist( $entity );
            $em->flush();
            return $this->redirect($this->generateUrl('listado_usuarios'));
        }

        return $this->render( 'AdminBundle:Usuarios:alta_usuario.html.twig', array(
            'form' => $form->createView(),
        ) );
    }

    public function activarAction( $id_usuario ){
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('AppBundle:User')
            ->find($id_usuario);

        if (!$usuario) {
            throw $this->createNotFoundException(
                'No se ha encontrado el usuario con id '.$id_usuario
            );
        }
        $usuario->setEnabled(true);
        $em->persist( $usuario );
        $em->flush();
        return $this->redirect($this->generateUrl('listado_usuarios'));
    }

    public function desactivarAction( $id_usuario ){
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('AppBundle:User')
            ->find($id_usuario);

        if (!$usuario) {
            throw $this->createNotFoundException(
                'No se ha encontrado el usuario con id '.$id_usuario
            );
        }
        $usuario->setEnabled(false);
        $em->persist( $usuario );
        $em->flush();
        return $this->redirect($this->generateUrl('listado_usuarios'));

    }
}
