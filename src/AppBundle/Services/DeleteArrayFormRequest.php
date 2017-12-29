<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Services;

/**
 * Description of DeleteArrayFormRequest
 *
 * @author Preira
 */
class DeleteArrayFormRequest {
    //on charge l'entity manager de doctrine et la requete 

    private $em;
    private $request;

    public function __construct($rt, $em) {
        $this->request = $rt->getCurrentRequest();
        $this->em = $em;
    }

    public function execute() {
        $repo = $this->em->getRepository('AppBundle:Personne');
        $tab = $this->request->request->get('form');
        //oon supprime la selection à l'aide de la requete et l'entity manager
        
        foreach ($tab as $key => $value) {
            if ($key != "Supprimer Selection" && $key != "_token") {
                $personne = $repo->findOneByPrenom($key);
                $this->em->remove($personne);
            }
        }
        $this->em->flush();
    }

}
