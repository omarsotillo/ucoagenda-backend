<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var boolean
     * 
     * @ORM\Column(name="isFirstTime",type="boolean",)
     * 
     */
    private $isFirstTime=true;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    /**
     * Set isFirstTime
     *
     * @param boolean $isFirstTime
     *
     * @return User
     */
    public function setIsFirstTime($isFirstTime)
    {
        $this->isFirstTime = $isFirstTime;

        return $this;
    }

    /**
     * Get isFirstTime
     *
     * @return boolean
     */
    public function getIsFirstTime()
    {
        return $this->isFirstTime;
    }
}
