<?php

namespace App\EventListener;

use App\Entity\Products;
use App\Entity\StockHistoric;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EventListener
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Evento que se ejecuta después de persistir una entidad
     */
    public function postPersist(LifecycleEventArgs $args){
        // Obtenemos la entidad a persistir del args
        $entity = $args->getObject();
        
        // Comprobamos que la entidad sea una instancia de tipo Products
        if(!$entity instanceof Products) {
            // Si no lo es, salimos de la función con return
            return;
        }
        
        // Obtenemos el usuario
        $token = $this->tokenStorage->getToken();
        // Comprobamos el usuario, ya que puede que el persist sea en otro sitio
        if ($token) {
            $user = $token->getUser();

            // Creamos el StockHistory
            $stockHistory = new StockHistoric();
            $stockHistory->setUser($user);
            $stockHistory->setProduct($entity);
            $stockHistory->setCreatedAt(new \DateTime());
            $stockHistory->setStock($entity->getStock());

            // Hacemos el persist y el flush
            $em = $args->getObjectManager();
            $em->persist($stockHistory);
            $em->flush();
        }
    }

    /**
     * Evento que se ejecuta tras hacer un update
     */
    public function postUpdate(LifecycleEventArgs $args){
        // Obtenemos la entidad a persistir del args
        $entity = $args->getObject();

        // Comprobamos que la entidad sea una instancia de tipo Products
        if(!$entity instanceof Products) {
            // Si no lo es, salimos de la función con return
            return;
        }
        
        // Obtenemos el usuario
        $token = $this->tokenStorage->getToken();
        // Comprobamos el usuario, ya que puede que el persist sea en otro sitio
        if ($token) {

            $user = $token->getUser();

            // Creamos el StockHistory
            $stockHistory = new StockHistoric();
            $stockHistory->setUser($user);
            $stockHistory->setProduct($entity);
            $stockHistory->setCreatedAt(new \DateTime());
            $stockHistory->setStock($entity->getStock());

            // Hacemos el persist y el flush
            $em = $args->getObjectManager();
            $em->persist($stockHistory);
            $em->flush();
        }
    }

}