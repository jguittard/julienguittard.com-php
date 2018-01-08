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

use PDO;
use Psr\Container\ContainerInterface;
use Zend\Db\Adapter\AdapterInterface as DbAdapter;
use Zend\Db\ResultSet\ResultSetInterface;

/**
 * Class PostTableFactory
 *
 * @package Blog\Model
 */
class PostTableFactory
{
    /**
     * @param ContainerInterface $container
     * @return PostTable
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PostTable
    {
        $dbConfig = $container->get('config')['db'];
        $path = $dbConfig['database'];
        $sql = $dbConfig['sql'];

        if (file_exists($path)) {
            $path = realpath($path);
            unlink($path);
        }

        $this->createDatabaseFile($path, $sql);

        $dbAdapter = $container->get(DbAdapter::class);
        $resultSet = $container->get(ResultSetInterface::class);

        return new PostTable($dbAdapter, $resultSet);
    }

    private function createDatabaseFile(string $path, string $sql)
    {
        if ($path[0] !== '/') {
            $path = realpath(getcwd()) . '/' . $path;
        }
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();
        $pdo->exec(file_get_contents(realpath($sql)));
        $pdo->commit();
    }
}
