<?php

namespace Wix\FrameworkBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wix\FrameworkComponent\InstanceDecoder;
use Wix\FrameworkComponent\Instance\InstanceInterface;
use Wix\FrameworkComponent\Exception\InvalidInstanceException;

class DebugToolbar extends DataCollector
{
    /**
     * @var InstanceDecoder
     */
    protected $decoder;

    /**
     * @var array
     */
    protected $keys;

    /**
     * Constructor.
     *
     * @param InstanceDecoder $decoder
     * @param array $keys
     */
    public function __construct(InstanceDecoder $decoder, array $keys)
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
            /** @var InstanceInterface $instance */
            $instance = $this->decoder->parse($request->get('instance'));
        } catch (InvalidInstanceException $exception) {
            return;
        }

        $this->data = array(
            'instance' => array(
                'instance_id' => $instance->getInstanceId(),
                'sign_date' => $instance->getSignDate(),
                'uid' => $instance->getUid(),
                'permissions' => $instance->getPermissions(),
            ),
            'keys' => $this->keys,
        );
    }

    /**
     * @return string
     */
    public function getKeys()
    {
        return $this->data['keys'];
    }

    /**
     * @return InstanceInterface
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
        return 'wix_framework_debug_toolbar';
    }
}