<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Debug\Test\Listener;

use Es\Debug\Listener\ComponentsListener;
use Es\Debug\ToolbarEvent;
use Es\Mvc\ViewModelInterface;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\System\System;
use Es\View\ViewModel;

class ComponentsListenerTest //extends \PHPUnit_Framework_TestCase
{
    use ServicesTrait;

    public function setUp()
    {
        require_once 'SystemTestHelper.php';

        SystemTestHelper::resetSystem();
    }

    public function testSetModel()
    {
        $model    = new ViewModel();
        $listener = new ComponentsListener();
        $listener->setModel($model);
        $this->assertSame($model->getTemplate(), ComponentsListener::TEMPLATE);
        $this->assertSame($model, $listener->getModel());
    }

    public function testGetModel()
    {
        $listener = new ComponentsListener();
        $model    = $listener->getModel();
        $this->assertInstanceOf(ViewModelInterface::CLASS, $model);
        $this->assertSame($model->getTemplate(), ComponentsListener::TEMPLATE);
    }

    public function testInvoke()
    {
        $config = [
            'components' => [
                'Es\\Debug\\Component',
            ],
        ];
        $system   = System::init($config);
        $services = new Services();
        $services->set('System', $system);
        $this->setServices($services);

        $rootModel    = $this->getMock(ViewModel::CLASS, ['addChild']);
        $toolbarEvent = new ToolbarEvent($rootModel);

        $model    = new ViewModel();
        $listener = new ComponentsListener();
        $listener->setModel($model);

        $rootModel
            ->expects($this->once())
            ->method('addChild')
            ->with($this->identicalTo($model));

        $listener($toolbarEvent);

        $this->assertTrue(isset($model['components']));
        $items = $model['components'];
        $this->assertInternalType('array', $items);

        $this->assertSame(1, count($items));
        $this->assertArrayHasKey('Es\\Debug\\Component', $items);
    }
}
