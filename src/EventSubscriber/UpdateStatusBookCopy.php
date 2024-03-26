<?php

namespace App\EventSubscriber;

use App\Entity\Loan;
use App\Entity\Status;
use Doctrine\ORM\Events;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;

#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::postUpdate, priority: 500, connection: 'default')]
class UpdateStatusBookCopy
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    
    public function postPersist(PostPersistEventArgs $args): void
    {
        
        $this->changeStatusOfBookCopy(15, $args);
      
    }

    
    public function postUpdate(PostUpdateEventArgs $args)
    {
        $this->changeStatusOfBookCopy(13, $args);
    }



    public function changeStatusOfBookCopy($id, $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof Loan) {
            return;
        }

        $bookCopy = $entity->getBookCopy();
       
        $newStatus = $this->em->getRepository(Status::class)->find($id);
     
        if (!$newStatus) {
            throw new \Exception('Statut non trouvé');
        }

        $bookCopy->setStatus($newStatus);

        $this->em->persist($bookCopy);
        $this->em->flush();

    }
}