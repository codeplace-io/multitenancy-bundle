<?php

namespace Codeplace\MultitenancyBundle\Resolver;

use Codeplace\MultitenancyBundle\Model\TenantInterface;
use Symfony\Component\HttpFoundation\Request;

interface TenantResolverInterface
{
    public function resolve(Request $request): TenantInterface;
}