<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 6/7/13
 * Time: 6:14 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Acme\BinderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="quickbooks_products_info")
 */
class QbProductsInfo
{
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=30)
     */
    protected $attribute;
    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $cost;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $desc1;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $desc2;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $price1;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $size;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $upc;

    /**
     * @ORM\Column(type="integer")
     */
    protected $quantityonhand;

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
     * Set attribute
     *
     * @param string $attribute
     * @return QbProductsInfo
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    
        return $this;
    }

    /**
     * Get attribute
     *
     * @return string 
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * Set cost
     *
     * @param string $cost
     * @return QbProductsInfo
     */
    public function setCost($cost)
    {
        $this->cost = $cost;
    
        return $this;
    }

    /**
     * Get cost
     *
     * @return string 
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set desc1
     *
     * @param string $desc1
     * @return QbProductsInfo
     */
    public function setDesc1($desc1)
    {
        $this->desc1 = $desc1;
    
        return $this;
    }

    /**
     * Get desc1
     *
     * @return string 
     */
    public function getDesc1()
    {
        return $this->desc1;
    }

    /**
     * Set desc2
     *
     * @param string $desc2
     * @return QbProductsInfo
     */
    public function setDesc2($desc2)
    {
        $this->desc2 = $desc2;
    
        return $this;
    }

    /**
     * Get desc2
     *
     * @return string 
     */
    public function getDesc2()
    {
        return $this->desc2;
    }

    /**
     * Set price1
     *
     * @param string $price1
     * @return QbProductsInfo
     */
    public function setPrice1($price1)
    {
        $this->price1 = $price1;
    
        return $this;
    }

    /**
     * Get price1
     *
     * @return string 
     */
    public function getPrice1()
    {
        return $this->price1;
    }

    /**
     * Set size
     *
     * @param string $size
     * @return QbProductsInfo
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
     * Set upc
     *
     * @param string $upc
     * @return QbProductsInfo
     */
    public function setUpc($upc)
    {
        $this->upc = $upc;
    
        return $this;
    }

    /**
     * Get upc
     *
     * @return string 
     */
    public function getUpc()
    {
        return $this->upc;
    }

    /**
     * Set quantityonhand
     *
     * @param integer $quantityonhand
     * @return QbProductsInfo
     */
    public function setQuantityonhand($quantityonhand)
    {
        $this->quantityonhand = $quantityonhand;
    
        return $this;
    }

    /**
     * Get quantityonhand
     *
     * @return integer 
     */
    public function getQuantityonhand()
    {
        return $this->quantityonhand;
    }
}