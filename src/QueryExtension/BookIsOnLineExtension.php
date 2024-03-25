<?php

namespace App\QueryExtension;

use App\Entity\Book;
use Doctrine\ORM\QueryBuilder;
use ApiPlatform\Metadata\Operation;
use Symfony\Bundle\SecurityBundle\Security;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;

class BookIsOnLineExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{

    public function __construct(private Security $security)
    {
    }


    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
      $this->findBookisOnLineWhere($resourceClass, $queryBuilder);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        $this->findBookisOnLineWhere($resourceClass, $queryBuilder);
    }


    /**
     * get books record depend isOnLine parameter
     *
     * @param string $resourceClass
     * @param QueryBuilder $queryBuilder
     * @return void
     */
    public function findBookisOnLineWhere(string $resourceClass, QueryBuilder $queryBuilder): void
    {
           if(Book::class !== $resourceClass) {
            return;
           }

           if ($this->security->isGranted('ROLE_ADMIN')) {
            return; // Laisser passer les requêtes pour l'administrateur
        }
    
           //creer le querybuilder pour la logic
           //reuperer l'ensemble des enregistrement est filtrer avec le pararmetre isOnLine
           $rootAlias = $queryBuilder->getRootAliases()[0];
           $queryBuilder->andWhere(sprintf('%s.isOnLine = :isOnLine', $rootAlias))
               ->setParameter('isOnLine', true);
    }
}