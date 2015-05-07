<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/OpSiteBundle/blob/master/LICENSE
 * @link https://github.com/jbouzekri/OpSiteBundle
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Tests;

use LpFactory\Bundle\NestedSetRoutingBundle\Model\NestedSetRoutingPageInterface;

/**
 * Class TestUnitPage
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Tests
 * @author jobou
 */
class TestUnitPage implements NestedSetRoutingPageInterface
{
    /**
     * @var string
     */
    protected $slug;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return rand(1, 1000);
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }
}
