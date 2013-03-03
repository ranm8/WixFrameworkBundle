<?php
/**
 * Ronen Amiel <ronena@codeoasis.com>
 * 2/20/13, 3:18 PM
 * InstanceTest.php
 */

namespace Wix\BaseBundle\Tests\Instance;

use Wix\BaseBundle\Instance\Instance;

class InstanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \stdClass
     */
    protected function getInstance()
    {
        $instance = new \stdClass();
        $instance->instanceId = 'instanceId';
        $instance->signDate = 'signDate';
        $instance->uid = 'uid';
        $instance->permissions = 'OWNER';

        return $instance;
    }

    public function testNewInstance()
    {
        $instance = new Instance($this->getInstance());

        $this->assertEquals($instance->getInstanceId(), 'instanceId');
        $this->assertEquals($instance->getSignDate(), 'signDate');
        $this->assertEquals($instance->getUid(), 'uid');
        $this->assertEquals($instance->getPermissions(), 'OWNER');
    }
}