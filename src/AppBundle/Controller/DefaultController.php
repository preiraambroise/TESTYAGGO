<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Personne;
use AppBundle\Form\PersonneType;

class DefaultController extends Controller {
    /**
     * @Route("/index", name="homepage")
     */
    public function indexAction(Request $request) {
        // definition entité à persister et du formulaire associé
        
        $personne = new Personne();
        $form = $this->get('form.factory')->createBuilder(PersonneType::class, $personne)->getForm();
        //si le formulaire vient d'être soumis on hydrate et on enregistre

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            $em->persist($personne);
            $em->flush();
        //redirection vers la page show
            
            return $this->redirectToRoute('show');
        }
        return $this->render('default/index.html.twig', [
                    'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show", name="show")
     */
    public function showAction(Request $rq) {
        //si un formulaire de a été soumis par un des deux boutons

        if ($rq->isMethod('POST')) {
            //si on vient de cliquer sur supprimer selection 

            if (isset($rq->request->get('form')['Supprimer Selection'])) 
            {
                $this->get('deletor')->execute();
            }
            //si on vient de cliquer sur export json

            if (isset($rq->request->get('form')['Export json'])) 
            {
                $jsonfilebuilder = $this->get('json_file_builder');
                $list = $jsonfilebuilder->createList();
                $jsonfilebuilder->createFile($list);
                return $jsonfilebuilder->createResponse();
            }
        }
        $form = $this->get('form_list_builder')->createForm();
        return $this->render('default/show.html.twig', [
                    'form' => $form->createView()
        ]);
    }

}
