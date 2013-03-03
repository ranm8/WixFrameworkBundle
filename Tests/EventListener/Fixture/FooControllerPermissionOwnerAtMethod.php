<?php

namespace Wix\BaseBundle\Tests\EventListener\Fixture;

use Wix\BaseBundle\Configuration\Permission;

class FooControllerPermissionOwnerAtMethod
{
    /**
     * @Permission({"OWNER"})
     */
    public function barAction()
    {

    }
}
