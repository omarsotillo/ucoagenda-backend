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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Faculty",inversedBy="users")
     * @ORM\JoinColumn(name="faculty_id",referencedColumnName="id")
     */
    private $faculty;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Degree",inversedBy="users")
     * @ORM\JoinColumn(name="degree_id",referencedColumnName="id")
     */
    private $degree;
    
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

    /**
     * Set faculty
     *
     * @param \AppBundle\Entity\Faculty $faculty
     *
     * @return User
     */
    public function setFaculty(\AppBundle\Entity\Faculty $faculty = null)
    {
        $this->faculty = $faculty;

        return $this;
    }

    /**
     * Get faculty
     *
     * @return \AppBundle\Entity\Faculty
     */
    public function getFaculty()
    {
        return $this->faculty;
    }

    /**
     * Set degree
     *
     * @param \AppBundle\Entity\Degree $degree
     *
     * @return User
     */
    public function setDegree(\AppBundle\Entity\Degree $degree = null)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree
     *
     * @return \AppBundle\Entity\Degree
     */
    public function getDegree()
    {
        return $this->degree;
    }
}
