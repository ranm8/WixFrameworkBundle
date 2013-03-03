<?php
/**
 * Ronen Amiel <ronena@codeoasis.com>
 * 3/3/13, 2:57 PM
 * DebugToolbar.php
 */

namespace Wix\BaseBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wix\BaseBundle\Exception\InvalidInstanceException;
use Wix\BaseBundle\Instance\Decoder;
use Wix\BaseBundle\Instance\Instance;

class DebugToolbar extends DataCollector
{
    /**
     * @var Decoder
     */
    protected $decoder;

    /**
     * @var array
     */
    protected $keys;

    /**
     * Constructor.
     *
     * @param Decoder $decoder
     * @param array $keys
     */
    public function __construct(Decoder $decoder, array $keys)
    {
        $this->decoder = $decoder;
        $this->keys = $keys;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param \Exception $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        try {
            $instance = $this->decoder->parse($request->get('instance'));
        } catch(InvalidInstanceException $exception) {
            return;
        }

        $this->data = array(
            'config'   => array(
                'keys'          => $this->keys,
            ),
            'instance' => array(
                'instance_id'   => $instance->getInstanceId(),
                'sign_date'     => $instance->getSignDate(),
                'uid'           => $instance->getUid(),
                'permissions'   => $instance->getPermissions(),
            ),
        );
    }

    /**
     * @return mixed
     */
    public function getApplicationKey()
    {
        return $this->data['config']['keys']['application_key'];
    }

    /**
     * @return Instance
     */
    public function getInstance()
    {
        return $this->data['instance'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wix_base_debug_toolbar';
    }
}