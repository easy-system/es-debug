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
            PHP_INT_MIN,
        ],
        'SystemProfiler::captureStop' => [
            SystemEvent::CLASS,
            'SystemProfiler',
            'captureStop',
            PHP_INT_MIN,
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
                'debug/dump'               => '{view_dir}/dump.phtml',
                'debug/toolbar'            => '{view_dir}/toolbar.phtml',
                'debug/timers'             => '{view_dir}/timers.phtml',
                'debug/components'         => '{view_dir}/components.phtml',
                'debug/modules'            => '{view_dir}/modules.phtml',
                'debug/services'           => '{view_dir}/services.phtml',
                'debug/listeners'          => '{view_dir}/listeners.phtml',
                'debug/controllers'        => '{view_dir}/controllers.phtml',
                'debug/request-attributes' => '{view_dir}/request-attributes.phtml',
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
     * Constructor.
     *
     * Normalizes the system configuration.
     *
     * Unable to use the following syntax for PHP 5.5:
     * <code>
     *    protected $systemConfig = [
     *        'view' => [
     *            'resolver' => [
     *                'debug/dump'    => __DIR__ . '/../view/dump.phtml',
     *                'debug/toolbar' => __DIR__ . '/../view/toolbar.phtml',
     *                // ...
     *            ],
     *        ],
     *    ];
     * </code>
     */
    public function __construct()
    {
        $viewDir = dirname(__DIR__) . PHP_DS . 'view';
        $convertor = function (&$item) use ($viewDir) {
            $item = str_replace('{view_dir}', $viewDir, $item);
        };
        array_walk_recursive($this->systemConfig, $convertor);
    }

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
