<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle;

use LpFactory\Bundle\NestedSetRoutingBundle\DependencyInjection\Compiler\UrlSatinizerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class LpFactoryNestedSetRoutingBundle
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle
 * @author jobou
 */
class LpFactoryNestedSetRoutingBundle extends Bundle
{
    /**
     * @{inheritdoc}
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new UrlSatinizerPass());
    }
}
