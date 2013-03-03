<?php
/**
 * Ronen Amiel <ronena@codeoasis.com>
 * 2/21/13, 2:43 PM
 * WixDebugToolbarListener.php
 */

namespace Wix\BaseBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Wix\BaseBundle\Exception\InvalidInstanceException;
use Wix\BaseBundle\Instance\Decoder;

/**
 * Inserts a wix debug toolbar into the bottom of every page. The toolbar provides helpful information when developing
 * new wix applications.
 */
class WixDebugToolbarListener
{
    /**
     * @var TwigEngine
     */
    protected $templating;

    /**
     * @var Decoder
     */
    protected $decoder;

    /**
     * @var array
     */
    protected $config;

    /**
     * Constructor
     *
     * @param TwigEngine $templating
     * @param Decoder $decoder
     * @param array $config
     */
    public function __construct(TwigEngine $templating, Decoder $decoder, array $config)
    {
        $this->templating = $templating;
        $this->decoder = $decoder;
        $this->config = $config;
    }

    /**
     * Listens to KernelResponse event and adds the wix debug toolbar if it should be added.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $response = $event->getResponse();
        $request = $event->getRequest();

        // do not capture redirects or modify XML HTTP Requests
        if ($request->isXmlHttpRequest()) {
            return;
        }

        if (!$this->config['toolbar']['enabled']
            || $response->isRedirection()
            || ($response->headers->has('Content-Type') && !strpos($response->headers->get('Content-Type'), 'html'))
            || $request->getRequestFormat() !== 'html'
        ) {
            return;
        }

        $this->injectToolbar($response, $request);
    }

    /**
     * Injects the toolbar into the response.
     *
     * @param Response $response A Response instance
     * @param Request $request
     */
    protected function injectToolbar(Response $response, Request $request)
    {
        $content = $response->getContent();
        $pos = mb_strripos($content, '</body>');

        try {
            $instance = $this->decoder->parse($request->get('instance'));
        } catch(InvalidInstanceException $exception) {
            return;
        }

        if ($pos !== false) {
            $toolbar = "\n".str_replace("\n", '', $this->templating->render(
                'WixBaseBundle:Debug:toolbar.html.twig',
                array(
                    'instance' => $instance,
                    'application_key' => $this->config['keys']['application_key'],
                )
            ))."\n";
            $content = mb_substr($content, 0, $pos) . $toolbar . mb_substr($content, $pos);
            $response->setContent($content);
        }
    }
}
