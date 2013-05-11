<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sehenge
 * Date: 5/12/13
 * Time: 1:20 AM
 * To change this template use File | Settings | File Templates.
 */
// src/Acme/OrderBundle/Entity/Products.php
namespace Acme\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Products
{
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=256)
     */
    protected $model;

    /**
     * @ORM\Column(type="string", length=256)
     */
    protected $color_code;

    /**
     * @ORM\Column(type="string", length=256)
     */
    protected $size;

    /**
     * @ORM\Column(type="string", length=256)
     */
    protected $availability;

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
     * Set model
     *
     * @param string $model
     * @return Products
     */
    public function setModel($model)
    {
        $this->model = $model;
    
        return $this;
    }

    /**
     * Get model
     *
     * @return string 
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set color_code
     *
     * @param string $colorCode
     * @return Products
     */
    public function setColorCode($colorCode)
    {
        $this->color_code = $colorCode;
    
        return $this;
    }

    /**
     * Get color_code
     *
     * @return string 
     */
    public function getColorCode()
    {
        return $this->color_code;
    }

    /**
     * Set size
     *
     * @param string $size
     * @return Products
     */
    public function setSize($size)
    {
        $this->size = $size;
    
        return $this;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set availability
     *
     * @param string $availability
     * @return Products
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
}