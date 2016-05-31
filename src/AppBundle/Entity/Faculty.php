<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Faculty
 *
 * @ORM\Table(name="faculty")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FacultyRepository")
 */
class Faculty
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"list","detail"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80)
     * @Serializer\Groups({"list","detail"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255)
     * @Serializer\Groups({"list","detail"})
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Degree",mappedBy="faculty")
     * @Serializer\Groups({"detail"})
     */
    private $degrees;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User",mappedBy="faculty")
     * @Serializer\Exclude()
     */
    private $users;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Faculty
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Faculty
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->degrees = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add degree
     *
     * @param \AppBundle\Entity\Degree $degree
     *
     * @return Faculty
     */
    public function addDegree(\AppBundle\Entity\Degree $degree)
    {
        $this->degrees[] = $degree;

        return $this;
    }

    /**
     * Remove degree
     *
     * @param \AppBundle\Entity\Degree $degree
     */
    public function removeDegree(\AppBundle\Entity\Degree $degree)
    {
        $this->degrees->removeElement($degree);
    }

    /**
     * Get degrees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDegrees()
    {
        return $this->degrees;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Faculty
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Faculty
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function __toString()
    {
        return $this->name;
    }
}
