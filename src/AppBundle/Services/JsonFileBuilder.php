<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\Response;

/**
 * Description of ListExport
 *
 * @author Preira
 */
class JsonFileBuilder {
    //on charge l'entity manager la requete jms_serializer chemin et le nom du fichier

    private $em;
    private $request;
    private $serializer;
    private $filepath;
    private $filename;

    public function __construct($rt, $em, $serializer, $filename) {
        $this->request = $rt->getCurrentRequest();
        $this->em = $em;
        $this->serializer = $serializer;
        $this->filename = $filename;
        $this->filepath = dirname(__DIR__) . "/../../web/";
    }

    public function createList() {
        //on crée la liste à des personnes selectionnées
        
        $list = [];
        $repo = $this->em->getRepository('AppBundle:Personne');
        $tab = $this->request->get('form');
        foreach ($tab as $key => $value) {
            if ($key != "Supprimer Selection" && $key != "_token") {
                $personne = $repo->findOneByPrenom($key);
                $list[] = $personne;
            }
        }
        return $list;
    }

    public function createFile($list) {
        //si le fichier existe (d'un export precedent je le supprime
        
        if (file_exists($this->filepath . $this->filename)) {
            unlink($this->filepath . $this->filename);
        }
        // je serialize ma liste et l'insere dans le fichier
        $listjson1 = $this->serializer->serialize($list, 'json');
        $listjson = str_replace(["[", "]"], ["{", "}"], $listjson1);
        $monfichier = fopen('fichier.json', 'a+');
        fputs($monfichier, $listjson);
        fclose($monfichier);
    }

    public function createResponse() {
        // je permets le telechargement du fichier en créeant la reponse
        $response = new Response();
        $response->setContent(file_get_contents($this->filepath . $this->filename));
        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-disposition', 'filename=' . $this->filename);
        return $response;
    }

}
