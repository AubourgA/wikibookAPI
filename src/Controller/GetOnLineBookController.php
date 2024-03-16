<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[AsController]
class GetOnLineBookController extends AbstractController
{
  
    public function __construct(
        private EntityManagerInterface $em,
    ) {}

    public function __invoke(): Array
    {
        $filteredBook = $this->em->getRepository(Book::class)->findBy(['isOnLine' => 1]);

        return $filteredBook;
    }
}
