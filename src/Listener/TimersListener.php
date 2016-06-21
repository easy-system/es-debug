<?php
/**
 * This file is part of the "Easy System" package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Damon Smith <damon.easy.system@gmail.com>
 */
namespace Es\Debug\Listener;

use Es\Debug\ToolbarEvent;
use Es\Events\ListenersTrait;
use Es\Mvc\ViewModelInterface;
use Es\Server\ServerTrait;
use Es\View\ViewModel;

/**
 * Adds timers to the debug toolbar.
 */
class TimersListener
{
    use ListenersTrait, ServerTrait;

    /**
     * The template of View Model.
     *
     * @const string
     */
    const TEMPLATE = 'debug/timers';

    /**
     * The View Model.
     *
     * @var \Es\Mvc\ViewModelInterface
     */
    protected $model;

    /**
     * Sets the profiler listener.
     *
     * @param ProfilerListener $profiler The profiler listener
     */
    public function setProfiler(ProfilerListener $profiler)
    {
        $this->getListeners()->set('SystemProfiler', $profiler);
    }

    /**
     * Gets the profiler listener.
     *
     * @param ProfilerListener $profiler The profiler listener
     */
    public function getProfiler()
    {
        return $this->getListeners()->get('SystemProfiler');
    }

    /**
     * Sets the View Model.
     *
     * @param \Es\Mvc\ViewModelInterface $model The View Model
     */
    public function setModel(ViewModelInterface $model)
    {
        $this->model = $model->setTemplate(static::TEMPLATE);
    }

    /**
     * Gets the View Model.
     *
     * @return \Es\Mvc\ViewModelInterface The View Model
     */
    public function getModel()
    {
        if (! $this->model) {
            $this->setModel(new ViewModel());
        }

        return $this->model;
    }

    /**
     * Adds timers to the debug toolbar.
     *
     * @param \Es\Debug\ToolbarEvent $event The event of the debug toolbar
     */
    public function __invoke(ToolbarEvent $event)
    {
        $server  = $this->getServer();
        $request = $server->getRequest();

        $start  = $request->getServerParam('REQUEST_TIME_FLOAT');
        $finish = microtime(true) + 0.003;

        $model    = $this->getModel();
        $profiler = $this->getProfiler();

        $timers = $profiler->getTimers();

        if (! empty($timers)) {
            reset($timers);
            $timers[key($timers)]['start'] = $start;
            end($timers);
            $timers[key($timers)]['stop'] = $finish;
        }

        foreach ($timers as $key => $item) {
            $timers[$key] = round($item['stop'] - $item['start'], 4);
        }
        $timers['Total'] = round($finish - $start, 4);
        $model['timers'] = $timers;

        $rootModel = $event->getContext();
        $rootModel->addChild($model);
    }
}
