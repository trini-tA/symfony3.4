<?php
namespace AppBundle\Service;

use Doctrine\ORM\Event\OnFlushEventArgs;
use AppBundle\Entity\Todo;

class TodoService{

    public function onFlush(OnFlushEventArgs $args){
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $entities = array_merge(
            $uow->getScheduledEntityInsertions(),
            $uow->getScheduledEntityUpdates()
        );

        foreach ($entities as $entity) {
            if (!($entity instanceof Todo )) {
                continue;
            }
            $entity->setUpdatedAt( new \DateTime("now") );

            $em->persist( $entity );
            $md = $em->getClassMetadata('AppBundle\Entity\Todo');
            $uow->recomputeSingleEntityChangeSet($md, $entity);

        }
      


        //die( 'stop' );
    }
}