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
use Es\Http\Stream;
use Es\System\SystemEvent;
use Es\System\SystemTrait;
use Es\View\ViewTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * Injects the debug toolbar to the body of the response.
 */
class InjectToolbarListener
{
    use SystemTrait, ViewTrait;

    /**
     * Injects the debug toolbar to the body of the response.
     *
     * @param \Es\Debug\ToolbarEvent $toolbarEvent
     */
    public function __invoke(ToolbarEvent $toolbarEvent)
    {
        $system = $this->getSystem();
        $event  = $system->getEvent();

        $result = $event->getResult(SystemEvent::FINISH);

        if ($result instanceof ResponseInterface) {
            $contentType = $result->getHeaderLine('Content-Type');
            if (0 === strpos($contentType, 'text/html')) {
                $view  = $this->getView();
                $model = $toolbarEvent->getContext();

                $injection = $view->render($model);

                $body   = (string) $result->getBody();
                $html   = str_replace('</body>', $injection . '</body>', $body);
                $stream = Stream::make($html);
                $event->setResult(SystemEvent::FINISH, $result->withBody($stream));
            }
        }
    }
}
