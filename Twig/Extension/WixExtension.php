<?php

namespace Wix\FrameworkBundle\Twig\Extension;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class WixExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            'wix_url' => new \Twig_Function_Method($this, 'wixUrl', array('is_safe' => array('html'))),
        );
    }

    /**
     * Returns a URL for the requested route with the section url as it's base URL.
     *
     * It should be used with <a target="_top" href="{{ wix_url('route') }}">click!</a> as described in the SDK:
     * @link { http://dev.wix.com/docs/display/DRAF/Developing+a+Page+App#DevelopingaPageApp-DeepLinkingforServer-SideRendering }
     *
     * @param $route
     * @return string
     */
    public function wixUrl($route)
    {
        return $this->getSectionUrl() . ltrim($this->getRoute($route), '/');
    }

    /**
     * Returns a Router instance
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->container->get('router');
    }

    /**
     * Returns a Request instance
     *
     * @return Request
     */
    protected function getRequest()
    {
        return $this->container->get('request');
    }

    /**
     * Returns the section URL of the current request
     *
     * @return string The section URL for the current request
     */
    protected function getSectionUrl()
    {
        return $this->getRequest()->get('section-url');
    }

    /**
     * Returns the URL for the requested route
     *
     * @param string $route A route to look for
     * @return string A URL
     */
    protected function getRoute($route)
    {
        return $this->getRouter()->generate($route);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'wix';
    }
}