<?php

namespace Wix\BaseBundle\Instance;

/**
 * A class that represents a decoded instance and provides information on that instance.
 */
class Instance
{
    /**
     * An instance to provide information on
     *
     * @var \stdClass object
     */
    private $instance;

    /**
     * @param \stdClass $instance an instance to provide information on
     */
    public function __construct(\stdClass $instance)
    {
        $this->instance = $instance;
    }

    /**
     * Returns the instance id. This property identifies an instance of an app inside a Wix website.
     *
     * @return string
     */
    public function getInstanceId()
    {
        if (!isset($this->instance->instanceId)) {
            return null;
        }

        return $this->instance->instanceId;
    }

    /**
     * Returns the instance sign date. This is the date this Wix user signed into Wix.
     *
     * @return string
     */
    public function getSignDate()
    {
        if (!isset($this->instance->signDate)) {
            return null;
        }

        return $this->instance->signDate;
    }

    /**
     * Returns the instance uid. It identifies a Wix user.
     *
     * @return mixed
     */
    public function getUid()
    {
        if (!isset($this->instance->uid)) {
            return null;
        }

        return $this->instance->uid;
    }

    /**
     * @return mixed
     */
    public function getPermissions()
    {
        if (!isset($this->instance->permissions)) {
            return null;
        }

        return $this->instance->permissions;
    }
}