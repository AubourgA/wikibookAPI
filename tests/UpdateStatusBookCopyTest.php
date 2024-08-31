<?php
namespace App\Tests\EventSubscriber;

use App\Entity\BookCopy;
use App\Entity\Loan;
use App\Entity\Status;
use App\Repository\BookCopyRepository;
use App\Repository\LoanRepository;
use App\Repository\StatusRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;

class UpdateStatusBookCopyTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private BookCopyRepository $bookCopyRepository;
    private LoanRepository $loanRepository;
    private StatusRepository $statusRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->em = $container->get(EntityManagerInterface::class);
        $this->bookCopyRepository = $container->get(BookCopyRepository::class);
        $this->loanRepository = $container->get(LoanRepository::class);
        $this->statusRepository = $container->get(StatusRepository::class);
    }

    public function testUpdateStatusOnLoanCreation(): void
    {
        // Create and persist Status entities
        $statusAvailable = new Status();
        $statusAvailable->setType('Available');
        $this->em->persist($statusAvailable);
        $this->em->flush();

        $statusLoaned = new Status();
        $statusLoaned->setType('Loaned');
        $this->em->persist($statusLoaned);
        $this->em->flush();

        // Create and persist BookCopy entity
        $bookCopy = new BookCopy();
        $bookCopy->setStatus($statusAvailable);
        $this->em->persist($bookCopy);
        $this->em->flush();

        // Create and persist Loan entity
        $loan = new Loan();
        $loan->setBookCopy($bookCopy);
        $loan->setBorrowDate(new \DateTime());
        $this->em->persist($loan);
        $this->em->flush();

        // Assert that the status of BookCopy has been updated
        $bookCopy = $this->bookCopyRepository->find($bookCopy->getId());
        $this->assertEquals($statusLoaned, $bookCopy->getStatus());
    }
}
