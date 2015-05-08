<?php
/**
 * Copyright 2015 Jonathan Bouzekri. All rights reserved.
 *
 * @copyright Copyright 2015 Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 * @license https://github.com/jbouzekri/LpFactory/blob/master/LICENSE
 * @link https://github.com/jbouzekri/LpFactory
 */

namespace LpFactory\Bundle\NestedSetRoutingBundle\Model\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Gedmo\Tree\RepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use LpFactory\Bundle\NestedSetRoutingBundle\Model\NestedSetRoutingPageInterface;

/**
 * Interface NestedSetRoutingPageRepositoryInterface
 *
 * @package LpFactory\Bundle\NestedSetRoutingBundle\Entity\Repository
 * @author jobou
 */
interface NestedSetRoutingPageRepositoryInterface extends RepositoryInterface, ObjectRepository
{
    /**
     * Find a page per slug in a specific tree
     *
     * @param string                        $slug
     * @param NestedSetRoutingPageInterface $root
     *
     * @return array
     */
    public function getPageInTree($slug, NestedSetRoutingPageInterface $root = null);

    /**
     * Get the path of page in the nested set
     * You do not have to implement this method as your repository will extend NestedTreeRepository
     *
     * @param NestedSetRoutingPageInterface $node
     *
     * @return array
     */
    public function getPath($node);

    /**
     * Get the root node of tree in single tree strategy
     *
     * @throws NonUniqueResultException
     *
     * @return NestedSetRoutingPageInterface
     */
    public function getSingleRootNode();
}
