<?php
namespace NewRelic\Listener;

use Exception;
use Psr\Log\LoggerInterface;
use Zend\EventManager\EventManagerInterface as Events;
use Zend\Mvc\MvcEvent;

class ErrorListener extends AbstractListener
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param  Events $events
     * @return void
     */
    public function attach(Events $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH_ERROR,
            [$this, 'onError']
        );
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_RENDER_ERROR,
            [$this, 'onError']
        );
    }

    /**
     * @param  MvcEvent $event
     * @return void
     */
    public function onError(MvcEvent $event)
    {
        if (!$this->options->getExceptionsLoggingEnabled()) {
            return;
        }

        $exception = $event->getParam('exception');
        if ($exception) {
            $message = $this->createLogMessageFromException($exception);
            $this->logger->error($message, ['exception' => $exception]);
        }
    }

    private function createLogMessageFromException(Exception $exception)
    {
        return $exception->getFile()
            . ":" . $exception->getLine()
            . ": " . $exception->getMessage();
    }
}
