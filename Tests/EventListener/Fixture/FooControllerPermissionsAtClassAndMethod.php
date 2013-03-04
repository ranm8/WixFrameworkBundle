<?php

namespace Wix\FrameworkBundle\Tests\EventListener\Fixture;

use Wix\FrameworkBundle\Configuration\Permission;

/**
 * @Permission({"OWNER"})
 */
class FooControllerPermissionsAtClassAndMethod
{
    /**
     * @Permission({"NOT OWNER"})
     */
    public function barAction()
    {

    }
}
