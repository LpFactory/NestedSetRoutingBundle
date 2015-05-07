<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Satinizer;

/**
 * Interface UrlSatinizerInterface
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Satinizer
 * @author jobou
 */
interface UrlSatinizerInterface
{
    /**
     * Clean the url
     *
     * @param string $url
     *
     * @return string
     */
    public function clean($url);
}
