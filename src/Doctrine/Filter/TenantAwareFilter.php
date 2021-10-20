<?php

declare(strict_types=1);

namespace Codeplace\MultitenancyBundle\Doctrine\Filter;

use Codeplace\MultitenancyBundle\Model\TenantAwareInterface;
use Codeplace\MultitenancyBundle\Model\TenantInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

final class TenantAwareFilter extends SQLFilter
{
    private ?TenantInterface $tenant;
    private ?string $tenantReferenceColumnName;

    public function setTenant(TenantInterface $tenant)
    {
        $this->tenant = $tenant;
    }

    public function setTenantReferenceColumnName(?string $tenantReferenceColumnName): void
    {
        $this->tenantReferenceColumnName = $tenantReferenceColumnName;
    }

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (null === $this->tenant) {
            return '';
        }

        // Check if the entity implements the TenantAware interface
        if (!$targetEntity->reflClass->implementsInterface(TenantAwareInterface::class)) {
            return '';
        }

        return $targetTableAlias.'.'.$this->tenantReferenceColumnName.' = '.$this->tenant->getId();
    }
}