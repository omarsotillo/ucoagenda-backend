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
     * @var \DateTime
     *
     * @ORM\Column(name="startHour", type="datetime")
     */
    private $startHour;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finishHour", type="datetime")
     */
    private $finishHour;

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
}
