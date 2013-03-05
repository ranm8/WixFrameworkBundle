<?php

namespace Wix\FrameworkBundle\Tests\EventListener;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Wix\FrameworkBundle\EventListener\ControllerListener;
use Wix\FrameworkBundle\Tests\EventListener\Fixture\FooControllerPermissionOwnerAtClass;
use Wix\FrameworkBundle\Tests\EventListener\Fixture\FooControllerPermissionOwnerAtMethod;
use Wix\FrameworkBundle\Tests\EventListener\Fixture\FooControllerPermissionsAtClassAndMethod;
use Wix\FrameworkBundle\Tests\EventListener\Fixture\FooControllerWithoutPermission;
use Wix\FrameworkBundle\Configuration\Permission;

class ControllerListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Permission
     */
    protected $permission;

    public function setUp()
    {
        $this->request = new Request();
        $this->permission = new Permission(array());
    }

    public function tearDown()
    {
        $this->request = null;
        $this->permission = null;
    }

    /**
     * @expectedException \Wix\FrameworkBundle\Exception\AccessDeniedException
     */
    public function testPermissionOwnerAtMethodWitInvalidPermissions()
    {
        $controller = new FooControllerPermissionOwnerAtMethod();

        $event = $this->getFilterControllerEvent(array($controller, 'barAction'), $this->request);
        $listener = $this->getControllerListener('NOT OWNER');

        $listener->onKernelController($event);
    }

    public function testPermissionOwnerAtMethodWitValidPermissions()
    {
        $controller = new FooControllerPermissionOwnerAtMethod();

        $event = $this->getFilterControllerEvent(array($controller, 'barAction'), $this->request);
        $listener = $this->getControllerListener('OWNER');

        $listener->onKernelController($event);
    }

    /**
     * @expectedException \Wix\FrameworkBundle\Exception\AccessDeniedException
     */
    public function testPermissionOwnerAtClassWitInvalidPermissions()
    {
        $controller = new FooControllerPermissionOwnerAtClass();

        $event = $this->getFilterControllerEvent(array($controller, 'barAction'), $this->request);
        $listener = $this->getControllerListener('NOT OWNER');

        $listener->onKernelController($event);
    }

    public function testPermissionOwnerAtClassWitValidPermissions()
    {
        $controller = new FooControllerPermissionOwnerAtClass();

        $event = $this->getFilterControllerEvent(array($controller, 'barAction'), $this->request);
        $listener = $this->getControllerListener('OWNER');

        $listener->onKernelController($event);
    }

    public function testWithoutPermission()
    {
        $controller = new FooControllerWithoutPermission();

        $event = $this->getFilterControllerEvent(array($controller, 'barAction'), $this->request);
        $listener = $this->getControllerListener('NOT OWNER');

        $listener->onKernelController($event);
    }

    public function testPermissionAtClassAndMethod()
    {
        $controller = new FooControllerPermissionsAtClassAndMethod();

        $event = $this->getFilterControllerEvent(array($controller, 'barAction'), $this->request);
        $listener = $this->getControllerListener('NOT OWNER');
        $listener->onKernelController($event);

        $listener = $this->getControllerListener('OWNER');
        $listener->onKernelController($event);
    }

    /**
     * @param $owner
     * @return ControllerListener
     */
    protected function getControllerListener($owner)
    {
        $instance = $this->getMock('Wix\FrameworkComponent\Instance\InstanceInterface');

        $instance->expects($this->any())->method('getPermissions')->will($this->returnValue($owner));

        $decoder = $this->getMock('Wix\FrameworkComponent\InstanceDecoderInterface');

        $decoder->expects($this->any())->method('parse')->will($this->returnValue($instance));

        $listener = new ControllerListener(new AnnotationReader(), $decoder);

        return $listener;
    }

    /**
     * @param $controller
     * @param Request $request
     * @return FilterControllerEvent
     */
    protected function getFilterControllerEvent($controller, Request $request)
    {
        $mockKernel = $this->getMockForAbstractClass('Symfony\Component\HttpKernel\Kernel', array('', ''));

        return new FilterControllerEvent($mockKernel, $controller, $request, HttpKernelInterface::MASTER_REQUEST);
    }
}
