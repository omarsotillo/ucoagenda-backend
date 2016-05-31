<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hour
 *
 * @ORM\Table(name="hour")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HourRepository")
 */
class Hour
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
     *
     * @ORM\Column(name="startHour", type="string")
     */
    private $startHour;

    /**
     *
     * @ORM\Column(name="finishHour", type="string")
     */
    private $finishHour;

    /**
     * @ORM\Column(name="day_of_the_week",type="integer")
     */
    private $dayOfTheWeek;

    /**
     * @ORM\Column(name="class_location",type="string")
     */
    private $classLocation;
    /**
     * @ORM\Column(name="is_theory",type="boolean")
     */
    private $isTheory;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Lesson",inversedBy="hours")
     * @ORM\JoinColumn(name="lesson_id",referencedColumnName="id",onDelete="CASCADE")
     */
    private $lesson;


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
     * Set startHour
     *
     * @param \DateTime $startHour
     *
     * @return Hour
     */
    public function setStartHour($startHour)
    {
        $this->startHour = $startHour;

        return $this;
    }

    public function __toString()
    {
        return $this->startHour . ' ' . $this->dayOfTheWeek;
    }

    /**
     * Get startHour
     *
     * @return \DateTime
     */
    public function getStartHour()
    {
        return $this->startHour;
    }

    /**
     * Set finishHour
     *
     * @param \DateTime $finishHour
     *
     * @return Hour
     */
    public function setFinishHour($finishHour)
    {
        $this->finishHour = $finishHour;

        return $this;
    }

    /**
     * Get finishHour
     *
     * @return \DateTime
     */
    public function getFinishHour()
    {
        return $this->finishHour;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Hour
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     *
     * @return Hour
     */
    public function setLesson(\AppBundle\Entity\Lesson $lesson = null)
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Get lesson
     *
     * @return \AppBundle\Entity\Lesson
     */
    public function getLesson()
    {
        return $this->lesson;
    }

    /**
     * Set dayOfTheWeek
     *
     * @param string $dayOfTheWeek
     *
     * @return Hour
     */
    public function setDayOfTheWeek($dayOfTheWeek)
    {
        $this->dayOfTheWeek = $dayOfTheWeek;

        return $this;
    }

    /**
     * Get dayOfTheWeek
     *
     * @return string
     */
    public function getDayOfTheWeek()
    {
        return $this->dayOfTheWeek;
    }

    /**
     * Set classLocation
     *
     * @param string $classLocation
     *
     * @return Hour
     */
    public function setClassLocation($classLocation)
    {
        $this->classLocation = $classLocation;

        return $this;
    }

    /**
     * Get classLocation
     *
     * @return string
     */
    public function getClassLocation()
    {
        return $this->classLocation;
    }

    /**
     * Set isTheory
     *
     * @param boolean $isTheory
     *
     * @return Hour
     */
    public function setIsTheory($isTheory)
    {
        $this->isTheory = $isTheory;

        return $this;
    }

    /**
     * Get isTheory
     *
     * @return boolean
     */
    public function getIsTheory()
    {
        return $this->isTheory;
    }
}
