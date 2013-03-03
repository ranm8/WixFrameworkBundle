<?php

namespace Wix\BaseBundle\Instance;

use Wix\BaseBundle\Exception\InvalidInstanceException;
use Wix\BaseBundle\Exception\MissingInstanceException;

/**
 * A service that decodes Wix instances. It has one public method: parse. It accepts a Wix instance and returns an
 * Instance object that provides information about the decoded instance.
 */
class Decoder
{
    /**
     * Service configuration.
     *
     * @var array
     */
    protected $config;

    /**
     * Decode every instance only once.
     *
     * @var array
     */
    protected $cache = array();

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Parses an instance and returns an Instance object that represents it's data.
     *
     * @param string $instance An instance to parse
     * @return Instance The data of the instance as an Instance object
     */
    public function parse($instance)
    {
        if (!isset($this->cache[$instance])) {
            $this->cache[$instance] = new Instance($this->parseInstance($instance));
        }

        return $this->cache[$instance];
    }

    /**
     * Decodes a string with base64 decryption.
     *
     * @param string $input Encoded data
     * @return string Decoded data
     */
    protected function base64UrlDecode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Parses an instance and returns an object with it's data.
     *
     * @param string $instance An instance to parse
     * @throws InvalidInstanceException
     * @return \stdClass The instance's data
     */
    protected function parseInstance($instance)
    {
        if ($instance === null) {
            throw new InvalidInstanceException('Could not find instance');
        }

        try {
            list($hash, $payload) = explode('.', $instance, 2);
        }
        catch(\Exception $e) {
            throw new InvalidInstanceException(sprintf('Provided instance is not formatted properly (%s)', $instance));
        }

        $hash = $this->base64UrlDecode($hash);
        $expected = hash_hmac('sha256', $payload, $this->config['application_secret'], true);

        if ($hash !== $expected) {
            throw new InvalidInstanceException(sprintf('Provided instance is invalid (%s)', $instance));
        }

        $data = json_decode($this->base64UrlDecode($payload));

        return $data;
    }
}