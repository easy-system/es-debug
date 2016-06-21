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

use Es\Debug\Listener\InjectToolbarListener;
use Es\Debug\ToolbarEvent;
use Es\Http\Response;
use Es\Http\Stream;
use Es\Services\Services;
use Es\Services\ServicesTrait;
use Es\System\System;
use Es\System\SystemEvent;
use Es\View\View;
use Es\View\ViewModel;

class InjectToolbarListenerTest extends \PHPUnit_Framework_TestCase
{
    use ServicesTrait;

    public function setUp()
    {
        require_once 'SystemTestHelper.php';

        SystemTestHelper::resetSystem();
    }

    public function testInvoke()
    {
        $html     = '<body></body>';
        $stream   = Stream::make($html);
        $response = new Response(
            200,
            $stream,
            ['Content-Type' => ['text/html']]
        );
        $system      = System::init();
        $systemEvent = $system->getEvent();
        $systemEvent->setResult(SystemEvent::FINISH, $response);

        $view = $this->getMock(View::CLASS, ['render']);

        $services = new Services();
        $services->set('System', $system);
        $services->set('View', $view);
        $this->setServices($services);

        $model = new ViewModel();
        $event = new ToolbarEvent($model);

        $view
            ->expects($this->once())
            ->method('render')
            ->with($this->identicalTo($model))
            ->will($this->returnValue('foo'));

        $listener = new InjectToolbarListener();
        $listener($event);

        $injected = $systemEvent->getResult(SystemEvent::FINISH);
        $this->assertInstanceOf(Response::CLASS, $injected);

        $body = $injected->getBody();
        $this->assertSame('<body>foo</body>', (string) $body);
    }
}
