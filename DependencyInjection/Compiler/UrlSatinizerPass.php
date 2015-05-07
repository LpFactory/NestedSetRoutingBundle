<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class UrlSatinizerPass
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\DependencyInjection\Compiler
 * @author jobou
 */
class UrlSatinizerPass implements CompilerPassInterface
{
    /**
     * Load all services with tag : lp_factory.routing.satinizer
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('lp_factory.routing.satinizer.chain')) {
            return;
        }

        $definition = $container->getDefinition(
            'lp_factory.routing.satinizer.chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'lp_factory.routing.satinizer'
        );
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addSatinizer',
                array(new Reference($id))
            );
        }
    }
}
