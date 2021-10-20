<?php

namespace Codeplace\MultitenancyBundle\Model;

interface TenantAwareInterface
{
    public function getTenant(): ?TenantInterface;
    public function setTenant(?TenantInterface $tenant): void;
}
