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

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSetInterface;
use Zend\Db\TableGateway\AbstractTableGateway;

/**
 * Class PostTable
 *
 * @package Blog\Model
 */
class PostTable extends AbstractTableGateway
{
    protected $table = 'posts';

    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * PostTable constructor.
     *
     * @param Adapter $adapter
     * @param ResultSetInterface $resultSet
     */
    public function __construct(Adapter $adapter, ResultSetInterface $resultSet)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = $resultSet;
    }
}
