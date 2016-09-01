<?php
namespace NewRelicTest\Factory;

use Interop\Container\ContainerInterface;
use NewRelic\ClientInterface;
use NewRelic\Factory\ErrorListenerFactory;
use NewRelic\Listener\ErrorListener;
use NewRelic\ModuleOptionsInterface;
use Psr\Log\LoggerInterface;

class ErrorListenerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('NewRelic\Client')->willReturn(
            $this->prophesize(ClientInterface::class)
        );
        $container->get('NewRelic\ModuleOptions')->willReturn(
            $this->prophesize(ModuleOptionsInterface::class)
        );
        $container->get('NewRelic\Logger')->willReturn(
            $this->prophesize(LoggerInterface::class)
        );
        $errorListenerFactory = new ErrorListenerFactory();

        $listener = $errorListenerFactory($container->reveal(), ErrorListener::class);

        $this->assertInstanceOf(ErrorListener::class, $listener);
    }
}
