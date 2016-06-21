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

use Es\Debug\Debug;
use Es\Debug\Listener\InjectDumpListener;
use Es\Http\Response;
use Es\Http\Stream;
use Es\Mvc\ViewModelInterface;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\System\System;
use Es\System\SystemEvent;
use Es\View\View;
use Es\View\ViewModel;

class InjectDumpListenerTest extends \PHPUnit_Framework_TestCase
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
        $listener = new InjectDumpListener();
        $listener->setModel($model);
        $this->assertSame($model->getTemplate(), InjectDumpListener::TEMPLATE);
    }

    public function testGetModel()
    {
        $listener = new InjectDumpListener();
        $model    = $listener->getModel();
        $this->assertInstanceOf(ViewModelInterface::CLASS, $model);
        $this->assertSame($model->getTemplate(), InjectDumpListener::TEMPLATE);
    }

    public function testInvoke()
    {
        $var = 'bar';
        Debug::dump($var);

        $system   = System::init([], true);
        $view     = $this->getMock(View::CLASS, ['render']);
        $services = new Services();
        $services->set('System', $system);
        $services->set('View', $view);
        $this->setServices($services);

        $html     = '<body></body>';
        $stream   = Stream::make($html);
        $response = new Response(
            200,
            $stream,
            ['Content-Type' => ['text/html']]
        );

        $event = new SystemEvent();
        $event->setResult(SystemEvent::FINISH, $response);

        $model    = new ViewModel();
        $listener = new InjectDumpListener();
        $listener->setModel($model);

        $view
            ->expects($this->once())
            ->method('render')
            ->with($this->identicalTo($model))
            ->will($this->returnValue('foo'));

        $listener($event);

        $this->assertTrue(isset($model['dumps']));
        $this->assertSame($model['dumps'], Debug::getDumpInstances());

        $injected = $event->getResult(SystemEvent::FINISH);
        $this->assertInstanceOf(Response::CLASS, $injected);

        $body = $injected->getBody();
        $this->assertSame('<body>foo</body>', (string) $body);
    }
}
