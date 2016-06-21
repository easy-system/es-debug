<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Debug;

use Es\Component\ComponentInterface;
use Es\System\SystemEvent;

/**
 * The system component.
 */
class Component implements ComponentInterface
{
    /**
     * The configuration of listeners.
     *
     * @var array
     */
    protected $listenersConfig = [
        'SystemProfiler' => 'Es\Debug\Listener\ProfilerListener',
        //
        'Es.Debug.Listener.InjectDumpListener'        => 'Es\Debug\Listener\InjectDumpListener',
        'Es.Debug.Listener.CreateToolbarListener'     => 'Es\Debug\Listener\CreateToolbarListener',
        'Es.Debug.Listener.InjectToolbarListener'     => 'Es\Debug\Listener\InjectToolbarListener',
        'Es.Debug.Listener.TimersListener'            => 'Es\Debug\Listener\TimersListener',
        'Es.Debug.Listener.ComponentsListener'        => 'Es\Debug\Listener\ComponentsListener',
        'Es.Debug.Listener.ModulesListener'           => 'Es\Debug\Listener\ModulesListener',
        'Es.Debug.Listener.ServicesListener'          => 'Es\Debug\Listener\ServicesListener',
        'Es.Debug.Listener.ListenersListener'         => 'Es\Debug\Listener\ListenersListener',
        'Es.Debug.Listener.ControllersListener'       => 'Es\Debug\Listener\ControllersListener',
        'Es.Debug.Listener.RequestAttributesListener' => 'Es\Debug\Listener\RequestAttributesListener',
    ];

    /**
     * The configuration of events.
     *
     * @var array
     */
    protected $eventsConfig = [
        'InjectDumpListener::__invoke' => [
            SystemEvent::FINISH,
            'Es.Debug.Listener.InjectDumpListener',
            '__invoke',
            12000,
        ],
        'CreateToolbarListener::__invoke' => [
            SystemEvent::FINISH,
            'Es.Debug.Listener.CreateToolbarListener',
            '__invoke',
            11000,
        ],
        'ComponentsListener::__invoke' => [
            ToolbarEvent::CLASS,
            'Es.Debug.Listener.ComponentsListener',
            '__invoke',
            800,
        ],
        'ModulesListener::__invoke' => [
            ToolbarEvent::CLASS,
            'Es.Debug.Listener.ModulesListener',
            '__invoke',
            700,
        ],
        'ServicesListener::__invoke' => [
            ToolbarEvent::CLASS,
            'Es.Debug.Listener.ServicesListener',
            '__invoke',
            600,
        ],
        'ListenersListener::__invoke' => [
            ToolbarEvent::CLASS,
            'Es.Debug.Listener.ListenersListener',
            '__invoke',
            500,
        ],
        'ControllersListener::__invoke' => [
            ToolbarEvent::CLASS,
            'Es.Debug.Listener.ControllersListener',
            '__invoke',
            400,
        ],
        'RequestAttributesListener::__invoke' => [
            ToolbarEvent::CLASS,
            'Es.Debug.Listener.RequestAttributesListener',
            '__invoke',
            300,
        ],
        'TimersListener::__invoke' => [
            ToolbarEvent::CLASS,
            'Es.Debug.Listener.TimersListener',
            '__invoke',
            1,
        ],
        'InjectToolbarListener::__invoke' => [
            ToolbarEvent::CLASS,
            'Es.Debug.Listener.InjectToolbarListener',
            '__invoke',
            PHP_INT_MAX * -1,
        ],
        'SystemProfiler::captureStop' => [
            SystemEvent::CLASS,
            'SystemProfiler',
            'captureStop',
            PHP_INT_MAX * -1,
        ],
        'SystemProfiler::captureStart' => [
            SystemEvent::CLASS,
            'SystemProfiler',
            'captureStart',
            PHP_INT_MAX,
        ],
    ];

    /**
     * The configuration of system.
     *
     * @var array
     */
    protected $systemConfig = [
        'view' => [
            'resolver' => [
                'debug/dump'               => __DIR__ . '/../view/dump.phtml',
                'debug/toolbar'            => __DIR__ . '/../view/toolbar.phtml',
                'debug/timers'             => __DIR__ . '/../view/timers.phtml',
                'debug/components'         => __DIR__ . '/../view/components.phtml',
                'debug/modules'            => __DIR__ . '/../view/modules.phtml',
                'debug/services'           => __DIR__ . '/../view/services.phtml',
                'debug/listeners'          => __DIR__ . '/../view/listeners.phtml',
                'debug/controllers'        => __DIR__ . '/../view/controllers.phtml',
                'debug/request-attributes' => __DIR__ . '/../view/request-attributes.phtml',
            ],
        ],
    ];

    /**
     * The current version of component.
     *
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * Gets the current version of component.
     *
     * @return string The version of component
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Gets the configuration of listeners.
     *
     * @return array The configuration of listeners
     */
    public function getListenersConfig()
    {
        return $this->listenersConfig;
    }

    /**
     * Gets the configuration of events.
     *
     * @return array
     */
    public function getEventsConfig()
    {
        return $this->eventsConfig;
    }

    /**
     * Gets the configuration of system.
     *
     * @return array The configuration of system
     */
    public function getSystemConfig()
    {
        return $this->systemConfig;
    }
}
