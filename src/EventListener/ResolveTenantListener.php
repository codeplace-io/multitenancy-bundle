<?php

declare(strict_types=1);

namespace Codeplace\MultitenancyBundle\EventListener;

use Codeplace\MultitenancyBundle\Context\TenantContext;
use Codeplace\MultitenancyBundle\Doctrine\Filter\TenantAwareFilter;
use Codeplace\MultitenancyBundle\Exception\ResolverException;
use Codeplace\MultitenancyBundle\Exception\RuntimeException;
use Codeplace\MultitenancyBundle\Resolver\TenantResolverInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class ResolveTenantListener
{
    private TenantResolverInterface $tenantResolver;
    private ?string $tenantReferenceColumnName;
    private EntityManagerInterface $entityManager;
    private TenantContext $tenantContext;
    private LoggerInterface $logger;

    public function __construct(
        TenantResolverInterface $tenantResolver,
        ?string $tenantReferenceColumnName,
        EntityManagerInterface $entityManager,
        TenantContext $tenantContext,
        LoggerInterface $logger
    ) {
        $this->tenantResolver = $tenantResolver;
        $this->tenantReferenceColumnName = $tenantReferenceColumnName;
        $this->entityManager = $entityManager;
        $this->tenantContext = $tenantContext;
        $this->logger = $logger;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $this->setupContext($event);

        $this->enableDoctrineFilter();
    }

    private function setupContext(RequestEvent $event)
    {
        $request = $event->getRequest();

        $currentRoute = $request->attributes->get('_route');
        $disableOnRoutes = ['_wdt'];
        if (null === $currentRoute || preg_match('/^_profiler/', $currentRoute) || in_array($currentRoute, $disableOnRoutes)) {
            return;
        }

        try {
            $this->tenantContext->setTenant($this->tenantResolver->resolve($request));
        } catch (ResolverException $resolverException) {
            $this->logger->notice(
                sprintf(
                    'Tenant can not be resolved for "%s": %s',
                    $request->getUri(),
                    $resolverException->getMessage()
                )
            );
        }
    }

    private function enableDoctrineFilter(): void
    {
        if ($this->tenantContext->hasTenant()) {
            $filter = $this->entityManager->getFilters()->enable('codeplace_tenant_filter');

            if (!$filter instanceof TenantAwareFilter) {
                throw new RuntimeException(sprintf(
                    '"%s" filter class must be an instance of "%s"',
                    get_class($filter),
                    TenantAwareFilter::class
                ));
            }

            $filter->setTenantReferenceColumnName($this->tenantReferenceColumnName);
            $filter->setTenant($this->tenantContext->getTenant());
        }
    }
}
