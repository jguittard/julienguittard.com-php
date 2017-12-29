<?php
/**
 * Julien Guittard Website
 *
 * @version   1.0
 * @author    Julien Guittard <julien.guittard@me.com>
 * @see       https://github.com/jguittard/julienguittard.com for the canonical source repository
 * @copyright Copyright (c) 2017 Julien Guittard. (https://julienguittard.com)
 */

namespace ApiTest;

use Api\ConfigProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfigProviderTest
 *
 * @package ApiTest
 */
class ConfigProviderTest extends TestCase
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->configProvider = new ConfigProvider();
    }

    public function testInvokeReturnsArray()
    {
        $cp = $this->configProvider;
        $this->assertArrayHasKey('dependencies', $cp());
    }

    public function testGetDependenciesReturnsFactories()
    {
        $this->assertArrayHasKey('factories', $this->configProvider->getDependencies());
    }
}
