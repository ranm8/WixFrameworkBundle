<?php
  /**
   * Ronen Amiel <ronen.amiel@gmail.com>
   * 01/12/12, 08:23
   * AppController.php
   */

  namespace Wix\GoogleAdSenseAppBundle\Controller;

  use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\HttpFoundation\Response;
  use Wix\APIBundle\Base\Instance;
  use Wix\GoogleAdSenseAppBundle\Document\User;
  use Wix\GoogleAdSenseAppBundle\Document\Token;
  use Doctrine\ODM\MongoDB\DocumentManager;
  use Doctrine\ODM\MongoDB\DocumentRepository;
  use Wix\GoogleAdSenseAppBundle\Exceptions\MissingParametersException;

  class AppController extends Controller
  {
      /**
       * @var \Google_Client
       */
      private $client;

      /**
       * @var \Google_AdsensehostService
       */
      private $service;

      /**
       * @var Instance
       */
      private $instance;

      /**
       * @var DocumentManager
       */
      private $manager;

      /**
       * @return Instance
       * @throws \Exception
       */
      protected function getInstance()
      {
          if ($this->instance === null) {
              $instance = $this->getRequest()->query->get('instance');
              if ($instance === null) {
                  throw new \Exception('Missing instance query string parameter.');
              }

              $this->instance = $this->get('wix_bridge')->parse($instance);
          }

          return $this->instance;
      }

      /**
       * @return \Google_Client
       */
      protected function getClient()
      {
          if ($this->client === null) {
              $this->client = $this->get('google_oauth2')->getClient();

              $token = $this->getUserToken($this->getUserDocument());

              // set an access token if we have one
              if ($token->getAccessToken() !== null) {
                  $this->client->setAccessToken($token->getAccessToken());
              }

              // renew the access token if it expired and save the new one
              if ($this->client->isAccessTokenExpired()) {
                  $this->client->refreshToken($token->getRefreshToken());

                  $token->setAccessToken($this->client->getAccessToken());
                  $this->getDocumentManager()->persist($token);
                  $this->getDocumentManager()->flush($token);
              }
          }

          return $this->client;
      }

      /**
       * @return mixed
       */
      protected function getConfig()
      {
          $config = $this->container->getParameter('wix_google_ad_sense_app.config');

          return $config;
      }

      /**
       * @return bool
       */
      protected function isDefault()
      {
          $config = $this->getConfig();
          $token = $this->getUserToken($this->getUserDocument());

          return $token->getRefreshToken() === $config['user']['refresh_token'];
      }

      /**
       * @param User $user
       * @return Token
       */
      private function getUserToken(User $user)
      {
          if ($user->connected()) {
              $token = $user->getToken();
          } else {
              $config = $this->getConfig();
              $refreshToken = $config['user']['refresh_token'];

              $token = $this->getDocumentManager()->getRepository('WixGoogleAdSenseAppBundle:Token')
                ->find($refreshToken);

              if ($token === null) {
                  $token = new Token($refreshToken);
              }
          }

          return $token;
      }

      /**
       * @return \Google_AdsensehostService
       */
      protected function getService()
      {
          if ($this->service === null) {
              $this->service = $this->get('google_oauth2')->getService($this->getClient());
          }

          return $this->service;
      }

      /**
       * @return DocumentManager
       */
      protected function getDocumentManager()
      {
          if ($this->manager === null) {
              $this->manager = $this->get('doctrine.odm.mongodb.document_manager');
          }

          return $this->manager;
      }

      /**
       * @param array $params
       * @return array
       */
      protected function removeInstanceParams(array $params)
      {
          unset($params['instance']);
          unset($params['compId']);
          unset($params['origCompId']);

          return $params;
      }

      /**
       * @param bool $full
       * @throws MissingParametersException
       * @return mixed
       */
      protected function getComponentId($full = false)
      {
          $query = $this->getRequest()->query;

          $componentId = $query->has('origCompId') ? $query->get('origCompId') : $query->get('compId');

          if ($componentId === null) {
              throw new MissingParametersException('Could not find a component id (originCompId or compId query string parameter).');
          }

          if (preg_match("/^(TPWdgt|TPSttngs)/", $componentId) == false) {
              throw new MissingParametersException('Invalid component id. should be in the format of "TPWdgt" or "TPSttngs" with a digit appended to it.');
          }

          if ($full === false) {
              $componentId = preg_replace("/^(TPWdgt|TPSttngs)/", "", $componentId);
          }

          return $componentId;
      }

      /**
       *
       * @throws MissingParametersException
       * @return User
       */
      protected function getUserDocument()
      {
          $componentId = $this->getComponentId();
          $instanceId = $this->getInstance()->getInstanceId();

          $user = $this->getRepository('WixGoogleAdSenseAppBundle:User')
            ->findOneBy(array(
                    'instanceId' => $instanceId,
                    'componentId' => $componentId,
                ));

          if ($user === null) {
              $user = new User($instanceId, $componentId);
          }

          return $user;
      }

      /**
       * @param $class
       * @return DocumentRepository
       */
      protected function getRepository($class)
      {
          return $this->getDocumentManager()->getRepository($class);
      }
  }