<?php

namespace Wix\FrameworkBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @MongoDB\Document(collection="users")
 * @MongoDB\UniqueIndex(keys={"instanceId"="asc", "componentId"="asc"})
 *
 * A general purpose user document to use for Wix applications. It has two unique indexes: an instance id an a
 * component id.
 */
class User implements UserInterface
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $instanceId;

    /**
     * @MongoDB\String
     */
    protected $componentId;

    /**
     * @MongoDB\Date
     */
    protected $createdAt;

    /**
     * @MongoDB\Date
     */
    protected $updatedAt;

    /**
     * @inheritDoc
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        if (null === $this->updatedAt) {
            return null;
        }

        return $this->updatedAt->getTimestamp();
    }

    /**
     * @param $instanceId
     * @param $componentId
     */
    public function __construct($instanceId, $componentId)
    {
        $this->instanceId = $instanceId;
        $this->componentId = $componentId;

        $this->createdAt = new \DateTime();
    }

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
      return $this->createdAt->getTimestamp();
    }

    /**
     * Set instanceId
     *
     * @param string $instanceId
     * @return User
     */
    public function setInstanceId($instanceId)
    {
        $this->instanceId = $instanceId;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getInstanceId()
    {
        return $this->instanceId;
    }

    /**
     * Set componentId
     *
     * @param string $componentId
     * @return User
     */
    public function setComponentId($componentId)
    {
        $this->componentId = $componentId;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getComponentId()
    {
        return $this->componentId;
    }
}
