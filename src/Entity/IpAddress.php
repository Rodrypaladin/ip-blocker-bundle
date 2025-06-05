<?php

namespace Acme\IpBlockerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: 'Acme\IpBlockerBundle\Repository\IpAddressRepository')]
#[ORM\Table(name: 'ip_addresses')]
class IpAddress
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(type: 'string', length: 45)]
    private string $ip;

    #[ORM\Column(type: 'boolean')]
    private bool $whitelist = false;

    #[ORM\Column(type: 'boolean')]
    private bool $blacklist = false;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $expiresAt = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;
        return $this;
    }

    public function isWhitelist(): bool
    {
        return $this->whitelist;
    }

    public function setWhitelist(bool $whitelist): self
    {
        $this->whitelist = $whitelist;
        return $this;
    }

    public function isBlacklist(): bool
    {
        return $this->blacklist;
    }

    public function setBlacklist(bool $blacklist): self
    {
        $this->blacklist = $blacklist;
        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt !== null && $this->expiresAt <= new \DateTimeImmutable();
    }
}
