<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\EventListener;

use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;

/**
 * Description of LikeNotificationSubscriber
 *
 * @author partnerfusion
 */
class LikeNotificationSubscriber implements EventSubscriber {
    public function getSubscribedEvents() {
        return [
            Events::onFlush
        ];
    }
    
    public function onFlush(OnFlushEventArgs $args) {
        $entityManager = $args->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();
        
        /* @var $collectionUpdate PersistentCollection */
        foreach ($unitOfWork->getScheduledCollectionUpdates() as $collectionUpdate) {
            if (!$collectionUpdate->getOwner() instanceof MicroPost) {
                continue;;
            }
            
            if ($collectionUpdate->getMapping()['fieldName'] !== 'likedBy') {
                continue;
            }
            
            $insertDiff = $collectionUpdate->getInsertDiff();
         
            
            if (!count($insertDiff)) {
                return;
            }
            
            /* @var $microPost MicroPost */
            $microPost = $collectionUpdate->getOwner();
            
            $notification = new LikeNotification();
            $notification->setUser($microPost->getUser());
            $notification->setMicroPost($microPost);
            $notification->setLikedBy(reset($insertDiff));
            
            $entityManager->persist($notification);
            
            $unitOfWork->computeChangeSet($entityManager->getClassMetadata(LikeNotification::class), $notification);
        }
    }
}
