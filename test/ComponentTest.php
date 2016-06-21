<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Debug\Test;

use Es\Debug\Component;

class ComponentTest //extends \PHPUnit_Framework_TestCase
{
    protected $requiredTemplates = [
        'debug/dump',
        'debug/toolbar',
        'debug/timers',
        'debug/components',
        'debug/modules',
        'debug/services',
        'debug/listeners',
        'debug/controllers',
        'debug/request-attributes',
    ];

    public function testGetVersion()
    {
        $component = new Component();
        $version   = $component->getVersion();
        $this->assertInternalType('string', $version);
        $this->assertRegExp('#\d+.\d+.\d+#', $version);
    }

    public function testGetListenersConfig()
    {
        $component = new Component();
        $config    = $component->getListenersConfig();
        $this->assertInternalType('array', $config);
    }

    public function testGetEventsConfig()
    {
        $component = new Component();
        $config    = $component->getEventsConfig();
        $this->assertInternalType('array', $config);
    }

    public function testGetSystemConfig()
    {
        $component    = new Component();
        $systemConfig = $component->getSystemConfig();
        $this->assertInternalType('array', $systemConfig);

        $this->assertArrayHasKey('view', $systemConfig);
        $viewConfig = $systemConfig['view'];
        $this->assertInternalType('array', $viewConfig);

        $this->assertArrayHasKey('resolver', $viewConfig);
        $resolverConfig = $viewConfig['resolver'];
        $this->assertInternalType('array', $resolverConfig);

        foreach ($this->requiredTemplates as $item) {
            $this->assertArrayHasKey($item, $resolverConfig);
        }
    }
}
