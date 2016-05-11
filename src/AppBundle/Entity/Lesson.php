<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lesson
 *
 * @ORM\Table(name="lesson")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LessonRepository")
 */
class Lesson
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Degree",inversedBy="lessons")
     * @ORM\JoinColumn(name="degree_id",referencedColumnName="id",onDelete="CASCADE")
     */
    private $degree;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Hour",mappedBy="lesson")
     */
    private $hours;
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
     * @return Lesson
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
     * Set degree
     *
     * @param \AppBundle\Entity\Degree $degree
     *
     * @return Lesson
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->hours = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add hour
     *
     * @param \AppBundle\Entity\Hour $hour
     *
     * @return Lesson
     */
    public function addHour(\AppBundle\Entity\Hour $hour)
    {
        $this->hours[] = $hour;

        return $this;
    }

    /**
     * Remove hour
     *
     * @param \AppBundle\Entity\Hour $hour
     */
    public function removeHour(\AppBundle\Entity\Hour $hour)
    {
        $this->hours->removeElement($hour);
    }

    /**
     * Get hours
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHours()
    {
        return $this->hours;
    }
}
