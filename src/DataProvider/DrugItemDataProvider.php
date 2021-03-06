<?php
// api/src/DataProvider/DrugItemDataProvider.php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Drug;
use App\Entity\RPPS;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class DrugItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;


    /**
     * @var Request|null
     */
    protected $request;

    /**
     * ModuleItemDataProvider constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(RequestStack $requestStack,EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->request = $requestStack->getMasterRequest();
    }


    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Drug::class === $resourceClass && "get" === $operationName;
    }


    /**
     * @param string $resourceClass
     * @param array|int|string $id
     * @param string|null $operationName
     * @param array $context
     * @return RPPS|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?RPPS
    {

        return $this->em->getRepository(Drug::class)->find($id);

    }
}
