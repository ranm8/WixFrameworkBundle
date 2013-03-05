<?php

namespace Wix\FrameworkBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Wix\FrameworkComponent\InstanceDecoderInterface;
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
     * @param InstanceDecoderInterface $decoder
     * @param array $keys
     */
    public function __construct(InstanceDecoderInterface $decoder, array $keys)
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
        $this->data = array(
            'keys' => $this->keys
        );

        try {
            /** @var InstanceInterface $instance */
            $instance = $this->decoder->parse($request->get('instance'));

            $this->data['instance'] = array(
                'instance_id' => $instance->getInstanceId(),
                'sign_date' => $instance->getSignDate(),
                'uid' => $instance->getUid(),
                'permissions' => $instance->getPermissions(),
            );
        } catch (InvalidInstanceException $exception) {
            $this->data['instance'] = null;
        }
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