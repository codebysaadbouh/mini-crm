<?php

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    protected Security $security;
    protected $auth;
    public function __construct(Security $security, AuthorizationCheckerInterface $checker)
    {
        $this->security = $security;
        $this->auth = $checker;
    }

    public function addWhere(QueryBuilder $queryBuilder, string $resourceClass) {
        // 1. Obtenir l'utilisateur connecté
        $user_connected = $this->security->getUser();
        // 2. Si on demande des invoices ou des customers des clients connectés, on filtre par l'utilisateur connecté [Agir sur la requête]
        if(
            ($resourceClass === Customer::class || $resourceClass === Invoice::class)
            &&
            !$this->auth->isGranted('ROLE_ADMIN')
            &&
            $user_connected instanceof User) {
            // dd($queryBuilder);
            $rootAlias = $queryBuilder->getRootAliases()[0];
            // dd($rootAlias);
            if($resourceClass === Customer::class) {
                $queryBuilder->andWhere("$rootAlias.user = :user_connected");
            } else if ($resourceClass === Invoice::class) {
                $queryBuilder->join("$rootAlias.customer", "c")
                    ->andWhere("c.user = :user_connected");
            }

            $queryBuilder->setParameter("user_connected", $user_connected);
            // dd($queryBuilder);
        }
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }
}