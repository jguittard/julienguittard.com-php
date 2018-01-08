<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

namespace Blog\Model;

use Psr\Container\ContainerInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Hydrator\ArraySerializable as ArraySerializableHydrator;

/**
 * Class PostResultSetFactory
 *
 * @package Blog\Model
 */
class PostResultSetFactory
{
    /**
     * @param ContainerInterface $container
     * @return HydratingResultSet
     */
    public function __invoke(ContainerInterface $container): HydratingResultSet
    {
        $rowObject = new Post();

        return new HydratingResultSet(new ArraySerializableHydrator(), $rowObject);
    }
}
