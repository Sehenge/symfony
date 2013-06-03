<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sehenge
 * Date: 6/4/13
 * Time: 12:10 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Acme\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="timeline")
 */
class Timeline
{
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="integer", length=8)
     */
    protected $product_id;
    /**
     * @ORM\Column(type="string", length=128)
     */
    protected $availability;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $event_date;

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
     * Set product_id
     *
     * @param integer $productId
     * @return Timeline
     */
    public function setProductId($productId)
    {
        $this->product_id = $productId;
    
        return $this;
    }

    /**
     * Get product_id
     *
     * @return integer 
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Set availability
     *
     * @param string $availability
     * @return Timeline
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
    
        return $this;
    }

    /**
     * Get availability
     *
     * @return string 
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Set event_date
     *
     * @param \DateTime $eventDate
     * @return Timeline
     */
    public function setEventDate($eventDate)
    {
        $this->event_date = $eventDate;
    
        return $this;
    }

    /**
     * Get event_date
     *
     * @return \DateTime 
     */
    public function getEventDate()
    {
        return $this->event_date;
    }
}