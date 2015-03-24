<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;

class InstallerController extends Controller
{

    public function installAction( Request $request )
    {
        $entity = new User();
        $em = $this->getDoctrine()->getManager();
        if( $this->isAdminRegistered() )
        {
            return $this->render( 'AppBundle:Installer:install.html.twig', array(
                'form' => null,
                'thereIsAnAdminRegistered' => TRUE
            ) );
        }
        $form = $this->createFormBuilder($entity)
            ->add('username', 'text')
            ->add('email', 'text')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('save', 'submit', array('label' => 'Create Admin'))
            ->getForm();

        $form->handleRequest( $request );

        if( $form->isValid() )
        {
            $factory = $this->get( 'security.encoder_factory' );
            $encoder = $factory->getEncoder( $entity );
            $password = $encoder->encodePassword( $entity->getPassword(), $entity->getSalt() );
            $entity->setPassword( $password );
            $entity->addRole('ROLE_ADMIN');
            $entity->setEnabled(true);
            $em->persist( $entity );
            $em->flush();
            return $this->render( 'AppBundle:Installer:install.html.twig', array(
                'form' => $form->createView(),
                'thereIsAnAdminRegistered' => TRUE
            ) );
        }

        return $this->render( 'AppBundle:Installer:install.html.twig', array(
            'form' => $form->createView(),
        ) );
    }

    protected function findByRole($role) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('u')
            ->from('AppBundle:User', 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"' . $role . '"%');
        return $qb->getQuery()->getResult();
    }

    protected function isAdminRegistered()
    {
        $users = $this->findByRole( 'ROLE_ADMIN' );
        if( count( $users ) == 0 )
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}