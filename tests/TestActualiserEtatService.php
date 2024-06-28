<?php

namespace App\Tests;

use App\Repository\SortieRepository;
use App\Service\ActualiserEtatService;
use App\Service\Historiser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestActualiserEtatService extends KernelTestCase
{
    public function testAfficherSortieAHistoriser(){

        self::bootKernel();
        $em=static::getContainer()->get(EntityManagerInterface::class);
        //ActualiserEtatService $actualiserEtatService
        $actualiserEtatService= new ActualiserEtatService($em);
        $sorties=$actualiserEtatService->afficherSortiesAHistoriser();
        var_dump(count([$sorties]));
        $bool=function($sorties){
            foreach($sorties as $sortie){
                var_dump($sortie->getNom());
                var_dump($sortie->getEtat()->getLibelle());
                if(($sortie->getEtat()->getLibelle()!="Passée") && ($sortie->getEtat()->getLibelle()!="Annulée")){

                    return false;
                }
            }
            return true;
        };
        $this->assertTrue($bool($sorties));

    }

    public function testHistoriser(){
        var_dump('test Historiser');
        self::bootKernel();
        $em=static::getContainer()->get(EntityManagerInterface::class);
        //ActualiserEtatService $actualiserEtatService
        $actualiserEtatService=new ActualiserEtatService($em);
        $sorties=$actualiserEtatService->afficherSortiesAHistoriser();
        foreach($sorties as $sortie){
            var_dump($sortie->getNom());
            var_dump($sortie->getEtat()->getLibelle());
        }
        //$actualiserEtatService->historiser();
        $historiser= new Historiser();
        $historiser($em);
        $em->clear();
        $sorties=$actualiserEtatService->afficherSortiesAHistoriser();
        foreach($sorties as $sortie){
            var_dump($sortie->getNom());
            var_dump($sortie->getEtat()->getLibelle());
        }
        $this->assertEquals(0,count($sorties));

    }


}
