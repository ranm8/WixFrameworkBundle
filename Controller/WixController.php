<?php

namespace Wix\FrameworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Wix\FrameworkBundle\Configuration\Permission;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Wix\FrameworkComponent\Instance\Instance;
use Wix\FrameworkBundle\Document\User;
use Wix\FrameworkBundle\Exception\MissingParametersException;

abstract class WixController extends Controller
{

    /**
     * @var Instance
     */
    private $instance;

    /**
     * @var DocumentManager
     */
    private $manager;

    public function __construct() {

    }

    /**
     * Implement return your doc type (e.g. WixFrameworkBundle:User), please make sure your doc inherits WixFrameworkBundle:User
     * @return string
     */
    abstract protected function getDocumentType();

    /**
     * @return Instance
     */
    protected function getInstance()
    {
        if ($this->instance === null) {
            $instance = $this->getRequest()->query->get('instance');

            $this->instance = $this->get('wix_framework.instance_decoder')->parse($instance);
        }

        return $this->instance;
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
     * @param bool $full
     * @throws MissingParametersException
     * @return mixed
     */
    protected function getComponentId($full = false)
    {
        $query = $this->getRequest()->query;

        $componentId = $query->has('origCompId') ? $query->get('origCompId') : $query->get('compId');
        $this->validateComponentId($componentId);

        if ($full === false) {
            $componentId = preg_replace("/^(TPWdgt|TPSttngs)/", "", $componentId);
        }

        return $componentId;
    }

    /**
     * Validates the Wix component ID
     * @param $compId
     * @throws \Wix\FrameworkBundle\Exception\MissingParametersException
     */
    protected function validateComponentId($compId) {
        if (null === $compId) {
            throw new MissingParametersException('Could not find a component id (originCompId or compId query string parameter).');
        }

        if (preg_match("/^(TPWdgt|TPSttngs|TPSctn)/", $compId) == false) {
            throw new MissingParametersException('Invalid component id. should be in the format of "TPWdgt" or "TPSttngs" with a digit appended to it.');
        }
    }

    /**
     * Returns user document from DB
     *
     * @return User
     */
    protected function getUserDocument()
    {
        $componentId = $this->getComponentId();
        $instanceId = $this->getInstance()->getInstanceId();

        $user = $this->getRepository($this->getDocumentType())
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

    /**
     * @param $user
     * @return User
     */
    protected function updateUserDoc($user) {
        $this->getDocumentManager()->persist($user);
        $this->getDocumentManager()->flush();

        return $user;
    }
}
