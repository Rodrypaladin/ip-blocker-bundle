<?php

namespace Acme\IpBlockerBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IpBlockerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $mappings = [
            realpath(__DIR__.'/Entity') => 'Acme\IpBlockerBundle\Entity',
        ];

        $container->addCompilerPass(
            DoctrineOrmMappingsPass::createAttributeMappingDriver(
                $mappings,
                ['doctrine.orm.default_entity_manager'],
                false
            )
        );
    }
}
