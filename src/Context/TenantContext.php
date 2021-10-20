<?php

declare(strict_types=1);

namespace Codeplace\MultitenancyBundle\Context;

use Codeplace\MultitenancyBundle\Exception\InitializationException;
use Codeplace\MultitenancyBundle\Model\TenantInterface;

final class TenantContext
{
    private TenantInterface $tenant;

    public function getTenant(): TenantInterface
    {
        if (!isset($this->tenant)) {
            throw new InitializationException('Tenant is not set');
        }

        return $this->tenant;
    }

    public function setTenant(?TenantInterface $tenant): void
    {
        if (isset($this->tenant)) {
            throw new InitializationException('Tenant already set');
        }

        $this->tenant = $tenant;
    }

    public function hasTenant(): bool
    {
        return isset($this->tenant);
    }
}