<?php
namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Foto;

class AjaxController extends Controller
{
    public function uploadPictureAction()
    {
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        $form_files = $request->files->get('form');
        $uploaded_file = $form_files['fileupload'];
        $form = $request->request->get('form');
        $id_negocio = $form["id"];
        $picture = new Foto();
        $negocio = $em->getRepository('AppBundle:Negocio')->find($id_negocio);
        if ($uploaded_file) {
            $picture->setNegocio($negocio);
            $picture->setNombre($uploaded_file->getClientOriginalName());
            $picture->setFile($uploaded_file);
            $em->persist($picture);
            $em->flush();
            $response = 'success';
        } else $response = 'error';

        $response = new Response(json_encode(array('response' => $response )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}