# Effective Solutions - Security Bundle

## Installation

Use following command in command line to install Effective Solutions Security Bundle

`php composer.phar require effective-solutions/security-bundle`

After the installation add following in your AppKernel.php file

'new EffectiveSolutions\SecurityBundle\EffectiveSolutionsSecurityBundle(),'

## Usage

1. Create `User.php` file in your Entity folder and add following code.

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
     * @param \Base\PageBundle\Entity\Role $role
     * @return User
     */
    public function setRole(\Base\PageBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Base\PageBundle\Entity\Role
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

2. Create `Role.php` file in your Entity folder and add following code.

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

3. Add following code in your 'security.yml' file

```
security:
    encoders:
        Base\PageBundle\Entity\User:
            algorithm: sha512
            cost: 10

    providers:
        in_db:
            entity:
                class: Base\PageBundle\Entity\User
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

4. Run following code in your command line to configure admin user. Admin username is admin and password is admin

`php app/console register`

5. Then go to http://localhost/YourAppName/web/app_dev.php/login