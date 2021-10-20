<?php

namespace Codeplace\MultitenancyBundle\Model;

trait TenantAwareTrait
{
    protected ?TenantInterface $tenant = null;

    public function setTenant(?TenantInterface $tenant)
    {
        $this->tenant = $tenant;
    }

    public function getTenant(): ?TenantInterface
    {
        return $this->tenant;
    }
}