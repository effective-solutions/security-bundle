<?php
/**
 * Created by PhpStorm.
 * User: charith
 * Date: 7/22/15
 * Time: 10:38 AM
 */

namespace EffectiveSolutions\SecurityBundle\Model;
use Doctrine\ORM\Mapping as ORM;

class Role
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=30, nullable=false)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(name="metacode", type="string", length=20, nullable=false)
     */
    protected $metacode;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Role
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set metacode
     *
     * @param string $metacode
     * @return Role
     */
    public function setMetacode($metacode)
    {
        $this->metacode = $metacode;

        return $this;
    }

    /**
     * Get metacode
     *
     * @return string
     */
    public function getMetacode()
    {
        return $this->metacode;
    }

    function __toString()
    {
        return $this->getId() ? $this->getDescription() : "New Role";
    }
}