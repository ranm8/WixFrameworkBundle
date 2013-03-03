<?php

namespace Wix\BaseBundle\Tests\EventListener\Fixture;

use Wix\BaseBundle\Configuration\Permission;

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
