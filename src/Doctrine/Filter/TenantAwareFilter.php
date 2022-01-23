<?php

declare(strict_types=1);

namespace Codeplace\MultitenancyBundle\Doctrine\Filter;

use Codeplace\MultitenancyBundle\Model\TenantAwareInterface;
use Codeplace\MultitenancyBundle\Model\TenantInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

final class TenantAwareFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        $tenantId = $this->getRawParameter('tenant_id');
        if (null === $tenantId) {
            return '';
        }

        // Check if the entity implements the TenantAware interface
        if (!$targetEntity->reflClass->implementsInterface(TenantAwareInterface::class)) {
            return '';
        }

        $tenantReferenceColumnName = $this->getRawParameter('tenant_reference_column_name');

        return $targetTableAlias.'.'.$tenantReferenceColumnName.' = '.$tenantId;
    }

    private function getRawParameter(string $name): string
    {
        return trim($this->getParameter($name), "'");
    }
}