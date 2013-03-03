<?php

namespace Wix\BaseBundle\Tests\EventListener\Fixture;

use Wix\BaseBundle\Configuration\Permission;

/**
 * @Permission({"OWNER"})
 */
class FooControllerPermissionOwnerAtClass
{
    public function barAction()
    {

    }
}
