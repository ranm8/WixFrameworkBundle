<?php

namespace Wix\FrameworkBundle\Tests\EventListener\Fixture;

use Wix\FrameworkBundle\Configuration\Permission;

/**
 * @Permission({"OWNER"})
 */
class FooControllerPermissionOwnerAtClass
{
    public function barAction()
    {

    }
}
