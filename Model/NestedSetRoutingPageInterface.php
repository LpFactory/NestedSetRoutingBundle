<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/OpSiteBundle/blob/master/LICENSE
 * @link https://github.com/jbouzekri/OpSiteBundle
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Model;

/**
 * Class NestedSetRoutingPageInterface
 *
 * @author jobou
 */
interface NestedSetRoutingPageInterface
{
    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug();

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug);
}
