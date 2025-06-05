<?php

namespace Acme\IpBlockerBundle\Repository;

use Acme\IpBlockerBundle\Entity\IpAddress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IpAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IpAddress::class);
    }

    public function findActiveBlacklistByIp(string $ip): ?IpAddress
    {
        $qb = $this->createQueryBuilder('i')
            ->where('i.ip = :ip')
            ->andWhere('i.blacklist = true')
            ->andWhere('i.expiresAt IS NULL OR i.expiresAt > :now')
            ->setParameter('ip', $ip)
            ->setParameter('now', new \DateTimeImmutable())
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findActiveWhitelist(): array
    {
        $qb = $this->createQueryBuilder('i')
            ->where('i.whitelist = true')
            ->andWhere('i.expiresAt IS NULL OR i.expiresAt > :now')
            ->setParameter('now', new \DateTimeImmutable());

        return $qb->getQuery()->getResult();
    }
}
