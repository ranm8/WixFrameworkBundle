<?php
/**
 * Ronen Amiel <ronena@codeoasis.com>
 * 2/21/13, 2:08 PM
 * WixPermissions.php
 */

namespace Wix\BaseBundle\Configuration;

/**
 * Handles the @permission annotations applicable to controllers and actions to limit access to them only to specific
 * Wix roles.
 *
 * @Annotation
 */
class Permission
{
    /**
     * @var array
     */
    protected $permissions;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->permissions = isset($values['value']) ? $values['value'] : array();
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}