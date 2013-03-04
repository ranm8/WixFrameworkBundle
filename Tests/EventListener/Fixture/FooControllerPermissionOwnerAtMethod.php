<?php

namespace Wix\FrameworkBundle\Tests\EventListener\Fixture;

use Wix\FrameworkBundle\Configuration\Permission;

class FooControllerPermissionOwnerAtMethod
{
    /**
     * @Permission({"OWNER"})
     */
    public function barAction()
    {

    }
}
