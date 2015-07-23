<?php
/**
 * Created by PhpStorm.
 * User: charith
 * Date: 7/22/15
 * Time: 10:13 AM
 */

namespace EffectiveSolutions\SecurityBundle\AccessControl;
use Symfony\Component\Yaml\Parser;

class RolesHandler extends Base
{
    /**
     * load the content of the roles config file
     * @return mixed
     */
    protected function loadRolesAccessConfig()
    {
        $yaml = new Parser();
        return $yaml->parse(file_get_contents($this->getContainer()->get('Kernel')->getRootdir().'/config/roles_access_config.yml'));
    }

    /**
     * throw an exception if the current request does not match the role description in the config file
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function secure(){
        $rolesAccessConfig = $this->loadRolesAccessConfig();
        $user = $this->getUser();
        if(isset($user))
        {
            $route = $this->getRequest()->attributes->get('_route');
            if(isset($rolesAccessConfig[$route]))
            {
                $allowedRoles = $rolesAccessConfig[$route];
                $flag_granted = 0;
                foreach($allowedRoles as $allowedRole){

                    if($this->getAuthorizationChecker()->isGranted($allowedRole))
                    {
                        $flag_granted = 1;
                        break;
                    }
                }


                if($flag_granted == 0)
                    throw $this->createAccessDeniedException();
            }
        }
    }

    /**
     * check if the current request does not match the role description in the config file
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isSecure(){
        $rolesAccessConfig = $this->loadRolesAccessConfig();
        $user = $this->getUser();
        if(isset($user))
        {
            $route = $this->getRequest()->attributes->get('_route');
            if(isset($rolesAccessConfig[$route]))
            {
                $allowedRoles = $rolesAccessConfig[$route];
                $flag_granted = 0;
                foreach($allowedRoles as $allowedRole){

                    if($this->getAuthorizationChecker()->isGranted($allowedRole))
                    {
                        $flag_granted = 1;
                        break;
                    }
                }


                if($flag_granted == 0)
                    return false;
            }
        }
        return true;
    }

    /**
     * check if the route is granted the access
     * @param $route
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function isRouteGranted($route){
        $rolesAccessConfig = $this->loadRolesAccessConfig();
        $user = $this->getUser();
        if(isset($user) && isset($rolesAccessConfig[$route]))
        {
            $allowedRoles = $rolesAccessConfig[$route];
            foreach($allowedRoles as $allowedRole){

                if($this->getAuthorizationChecker()->isGranted($allowedRole))
                {
                    return true;
                }
            }
            return false;
        }
        return true;
    }
}