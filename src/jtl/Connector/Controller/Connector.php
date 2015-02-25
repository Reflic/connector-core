<?php
/**
 *
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\Application
 */

namespace jtl\Connector\Controller;

use \jtl\Connector\Core\Controller\Controller as CoreController;
use \jtl\Connector\Result\Action;
use \jtl\Connector\Core\Rpc\Error;
use \jtl\Connector\Application\Application;
use \jtl\Connector\Core\Model\QueryFilter;
use \jtl\Connector\Core\Model\DataModel;
use \jtl\Connector\Linker\IdentityLinker;
use \jtl\Connector\Serializer\JMS\SerializerBuilder;
use \jtl\Connector\Core\Model\AuthRequest;

/**
 * Base Config Controller
 *
 * @access public
 * @author David Spickers <david.spickers@jtl-software.de>
 */
class Connector extends CoreController
{
    /**
     * (non-PHPdoc)
     * @see \jtl\Connector\Core\Controller\IController::push()
     */
    public function push(DataModel $model)
    {
        // Not yet implemented
    }
    
    /**
     * (non-PHPdoc)
     * @see \jtl\Connector\Core\Controller\IController::pull()
     */
    public function pull(QueryFilter $queryFilter)
    {
        // Not yet implemented
    }

    /**
     * (non-PHPdoc)
     * @see \jtl\Connector\Core\Controller\IController::statistic()
     */
    public function statistic(QueryFilter $queryFilter)
    {
        // Not yet implemented
    }
    
    /**
     * Initialize the connector.
     *
     * @param mixed $params Can be empty or not defined and a string.
     */
    public function init($params = null)
    {
        $ret = new Action();
        try {
            $ret->setResult($this->getConfig()->read($params));
            $ret->setHandled(true);
        } catch (\Exception $e) {
            $err = new Error();
            $err->setCode($e->getCode());
            $err->setMessage($e->getMessage());
            $ret->setError($err);
        }

        return $ret;
    }
    
    /**
     * Returns the connector features.
     *
     * @param mixed $params Can be empty or not defined and a string.
     */
    public function features($params = null)
    {
        $ret = new Action();
        try {
            //@todo: irgend ne supertolle feature list methode
            $featureData = file_get_contents(CONNECTOR_DIR . '/config/features.json');
            $features = json_decode($featureData, true);

            $ret->setResult($features);
            $ret->setHandled(true);
        } catch (\Exception $e) {
            $err = new Error();
            $err->setCode($e->getCode());
            $err->setMessage($e->getMessage());
            $ret->setError($err);
        }

        return $ret;
    }

    /**
     * Ack Identity Mappings
     *
     * @param mixed $params empty or ack json string.
     */
    public function ack($params = null)
    {
        $ret = new Action();
        try {
            $serializer = SerializerBuilder::create();

            $ack = $serializer->deserialize($params, "jtl\Connector\Model\Ack", 'json');

            $identityLinker = IdentityLinker::getInstance();

            foreach ($ack->getIdentities() as $modelName => $identities) {
                foreach ($identities as $identity) {
                    //$identityLinker->save($identity->getEndpoint(), $identity->getHost(), $modelName);
                }
            }

            $ret->setResult(true);
            $ret->setHandled(true);
        } catch (\Exception $e) {
            $err = new Error();
            $err->setCode($e->getCode());
            $err->setMessage($e->getMessage());
            $ret->setError($err);
        }

        return $ret;
    }

    /**
     * Returns the connector auth action
     *
     * @param mixed $params
     * @return \jtl\Connector\Result\Action
     */
    public function auth($params)
    {
        $action = new Action();
        $action->setHandled(true);
        $authRequest = null;

        try {
            $serializer = SerializerBuilder::create();

            $authRequest = $serializer->deserialize($params, "jtl\Connector\Core\Model\AuthRequest", 'json');
        } catch (\Exception $e) {
            $err = new Error();
            $err->setCode($e->getCode());
            $err->setMessage($e->getMessage());
            $action->setError($err);

            return $action;
        }

        $configuredAuthToken = $this->getConfig()->read('auth_token');

        // If credentials are not valid, return appropriate response
        if (!($authRequest instanceof AuthRequest) || $configuredAuthToken !== $authRequest->getToken()) {
            sleep(2);

            // Set 'handled' flag because the call actually IS handled
            $action->setHandled(true);

            $error = new Error();
            $error->setCode(790);
            $error->setMessage("Could not authenticate access to the connector");
            $action->setError($error);

            return $action;
        }

        if (Application::$session !== null) {
            $session = new \stdClass();
            $session->sessionId = Application::$session->getSessionId();
            $session->lifetime = Application::$session->getLifetime();
            
            $action->setResult($session);
        } else {
            $error = new Error();
            $error->setCode(789)
                ->setMessage("Could not get any Session");
            $action->setError($error);
        }
        
        return $action;
    }
}
