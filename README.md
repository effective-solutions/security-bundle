# Effective Solutions - Security Bundle

## Installation

Use following command in command line to install Effective Solutions Security Bundle

`php composer.phar require effective-solutions/security-bundle`

After the installation add following in your `AppKernel.php` file

`new EffectiveSolutions\SecurityBundle\EffectiveSolutionsSecurityBundle(),`

Add following in `app/config/routing.yml` file

```
effective_solutions_security:
    resource: "@EffectiveSolutionsSecurityBundle/Resources/config/routing.yml"
    prefix:   /
```

## Usage

- Create `User.php` file in your Entity folder and add following code.

```
<?php

namespace Your\AppBundle\Entity;
use EffectiveSolutions\SecurityBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @ORM\ManyToOne(targetEntity="Role",cascade={"persist"})
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     **/
    private $role;

    /**
     * Set role
     *
     * @param \Base\DataAccessBundle\Entity\Role $role
     * @return User
     */
    public function setRole(\Base\DataAccessBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Base\DataAccessBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        $roles =  array();
        if($this->getRole() != null)
            $roles[] = $this->getRole()->getMetacode();
        return $roles;
    }
}
```

- Create `Role.php` file in your Entity folder and add following code.

```
<?php

namespace Your\AppBundle\Entity;
use EffectiveSolutions\SecurityBundle\Model\Role as BaseRole;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class Role extends BaseRole
{

}

```

- Run following code to create your user and role tables in your database

`php app/console doctrine:schema:update --force`

- Add following code in your `security.yml` file

```
security:
    encoders:
        Base\DataAccessBundle\Entity\User:
            algorithm: sha512
            cost: 10

    providers:
        in_db:
            entity:
                class: Base\DataAccessBundle\Entity\User
                property: username
        in_memory:
            memory:
                users:
                    user:  { password: userpass, roles: [ 'ROLE_USER' ] }
                    admin: { password: adminpass, roles: [ 'ROLE_ADMIN' ] }


    firewalls:
        dev:
            pattern:  ^/(_(profiler|wdt)|css|images|js)/
            security: false


        secured_area:
            pattern:    ^/
            form_login:
                login_path: login_route
                check_path: login_check
                default_target_path: /
                failure_path: login_route
                csrf_provider: form.csrf_provider
            logout:
                path:   logout
                target: login_route
            anonymous: ~


    access_control:
        - { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY}

```

- Run following code in your command line to configure admin user. Admin username is admin and password is admin

`php app/console register`

- Then go to `http://localhost/YourAppName/web/app_dev.php/login`

## With Sonata Admin Bundle

If you are using `Sonata Admin Bundle`, create BaseAdmin class and extend your admin classes from BaseAdmin class.
Overide `isGranted` method in the BaseAdmin class as follows.

```
public function isGranted($name,$object=null){

        // overridden by effective security bundle
        if(!$this->getService('effective_security.role_handler')->isRouteGranted($this->getBaseRouteName()))
            return false;
        if(is_array($name))
        {
            foreach($name as $element)
            {
                if(!$this->getService('effective_security.role_handler')->isRouteGranted($this->getBaseRouteName().'_'.strtolower($element)))
                    return false;
            }
        }
        else
        {
            if(!$this->getService('effective_security.role_handler')->isRouteGranted($this->getBaseRouteName().'_'.strtolower($name)))
                return false;
        }

        return parent::isGranted($name,$object);
    }
```

Create `roles_access_config.yml` in your app/config folder and you can write access control logic in that. For example,

```
admin_base_dataaccess_customer_create:
  - ROLE_SUPER_ADMIN
```

If you want to secure a controller, you can write route alias as above and call `secure()` in the Controller or `isSecure()`
to check if the user has permission to access that controller.

Thank you for using Effective Solutions Security Bundle. Powered By [EffectiveSolutions.lk](http://effectivesolutions.lk)