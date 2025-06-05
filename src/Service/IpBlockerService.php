<?php

namespace Acme\IpBlockerBundle\Service;

use Acme\IpBlockerBundle\Repository\IpAddressRepository;

class IpBlockerService
{
    private IpAddressRepository $ipRepo;

    private ?array $cachedWhitelist = null;

    public function __construct(IpAddressRepository $ipRepo)
    {
        $this->ipRepo = $ipRepo;
    }

    public function isBlocked(string $ip): bool
    {
        $whitelist = $this->getActiveWhitelist();

        if (count($whitelist) > 0) {
            foreach ($whitelist as $whiteIp) {
                if ($whiteIp->getIp() === $ip && !$whiteIp->isExpired()) {
                    return false;
                }
            }
            return true;
        }

        $blacklistItem = $this->ipRepo->findActiveBlacklistByIp($ip);

        return $blacklistItem !== null;
    }

    /**
     * @return \Acme\IpBlockerBundle\Entity\IpAddress[]
     */
    public function getActiveWhitelist(): array
    {
        if ($this->cachedWhitelist === null) {
            $this->cachedWhitelist = $this->ipRepo->findActiveWhitelist();
        }
        return $this->cachedWhitelist;
    }
}
