<?php

namespace Wix\FrameworkBundle\Document;

interface UserInterface
{
    /**
     * Get the current Wix instance ID
     * @return string
     */
    public function getInstanceId();

    /**
     * Get the current Wix component ID
     * @return string
     */
    public function getComponentId();

    /**
     * Get the instance created timestamp
     * @return int
     */
    public function getCreatedAt();

    /**
     * Get the last updated doc timestamp
     * @return int
     */
    public function getUpdatedAt();

    /**
     * Sets the updated data timestamp
     * @param \DateTime $updatedAt
     * @return UserInterface
     */
    public function setUpdatedAt($updatedAt);
}