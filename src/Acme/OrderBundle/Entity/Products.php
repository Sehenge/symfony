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
     * @ORM\Column(type="float")
     */
    protected $price;

    /**
     * @ORM\Column(type="float")
     */
    protected $retail_price;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $brand;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $modification_date;

    /**
     * @ORM\Column(type="string", length=256)
     */
    protected $availability;

    /**
     * @ORM\Column(type="string", length=16)
     */
    protected $source;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $add_date;

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

    /**
     * Set source
     *
     * @param string $source
     * @return Products
     */
    public function setSource($source)
    {
        $this->source = $source;
    
        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Products
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set retail_price
     *
     * @param float $retailPrice
     * @return Products
     */
    public function setRetailPrice($retailPrice)
    {
        $this->retail_price = $retailPrice;
    
        return $this;
    }

    /**
     * Get retail_price
     *
     * @return float 
     */
    public function getRetailPrice()
    {
        return $this->retail_price;
    }

    /**
     * Set brand
     *
     * @param string $brand
     * @return Products
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    
        return $this;
    }

    /**
     * Get brand
     *
     * @return string 
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set modification_date
     *
     * @param string $modificationDate
     * @return Products
     */
    public function setModificationDate($modificationDate)
    {
        $this->modification_date = $modificationDate;
    
        return $this;
    }

    /**
     * Get modification_date
     *
     * @return string 
     */
    public function getModificationDate()
    {
        return $this->modification_date;
    }

    /**
     * Set add_date
     *
     * @param \timestamp $addDate
     * @return Products
     */
    public function setAddDate(\timestamp $addDate)
    {
        $this->add_date = $addDate;
    
        return $this;
    }

    /**
     * Get add_date
     *
     * @return \timestamp 
     */
    public function getAddDate()
    {
        return $this->add_date;
    }
}