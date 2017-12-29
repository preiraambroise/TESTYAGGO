<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Services;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Description of FormListBuilder
 *
 * @author Preira
 */
class FormListBuilder {
    //on charge la requete l'entity manager et le formbuilder

    private $request;
    private $em;
    private $formbuilder;

    public function __construct($rt, $em, $formbuilder) {
        $this->request = $rt->getCurrentRequest();
        $this->em = $em;
        $this->formbuilder = $formbuilder;
    }

    public function createForm() {
        //on recupere toutes les entités
        
        $repo = $this->em->getRepository('AppBundle:Personne');
        $list = $repo->findAll();
        $fb = $this->formbuilder->createBuilder(FormType::class);
        
        //on crée les champs du formulaire avec le prenom des personnes
        for ($i = 0; $i < count($list); $i++) {
            $fb->add($list[$i]->getPrenom() , CheckboxType::class, ['required' => false]);
        }
        if (count($list) != 0) {
            $fb->add('Supprimer Selection', SubmitType::class);
            $fb->add('Export json', SubmitType::class);
        }
        return $fb->getForm();
    }

}
