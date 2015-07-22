<?php
/**
 * Created by PhpStorm.
 * User: charith
 * Date: 7/22/15
 * Time: 10:12 AM
 */

namespace EffectiveSolutions\SecurityBundle\AccessControl;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class Base
{
    private $tokenStorage;
    private $authorizationChecker;
    private $session;
    private $request;

    public function __construct($tokenStorage,$authorizationChecker,$session,$request){
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->session = $session;
        $this->request = $request;
    }

    /**
     * Throw access denied exception
     * @param string $message
     * @param \Exception $previous
     * @return AccessDeniedException
     */
    protected function createAccessDeniedException($message = 'Access Denied', \Exception $previous = null)
    {
        return new AccessDeniedException($message, $previous);
    }

    /**
     * returns the security token storage
     * @return mixed
     */
    protected function getTokenStorage(){
        return $this->tokenStorage;
    }

    /**
     * get authentication checker service of symfony
     * @return mixed
     */
    protected function getAuthorizationChecker(){
        return $this->authorizationChecker;
    }

    /**
     * return the session service
     * @return mixed
     */
    protected function getSession(){
        return $this->session;
    }

    /**
     * returns the request service
     * @return mixed
     */
    protected function getRequest(){
        return $this->request;
    }

    /**
     * get the current user
     * @return mixed
     */
    protected function getUser(){
        return $this->getTokenStorage()->getToken()->getUser();
    }
}