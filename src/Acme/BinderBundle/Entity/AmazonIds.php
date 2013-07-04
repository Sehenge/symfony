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
 * @ORM\Table(name="amazon_products_ids")
 */
class AmazonIds
{
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $seller_sku;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $asin;

    /**
     * @ORM\Column(type="integer")
     */
    protected $quantity;

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
     * Set seller_sku
     *
     * @param string $sellerSku
     * @return AmazonIds
     */
    public function setSellerSku($sellerSku)
    {
        $this->seller_sku = $sellerSku;
    
        return $this;
    }

    /**
     * Get seller_sku
     *
     * @return string 
     */
    public function getSellerSku()
    {
        return $this->seller_sku;
    }

    /**
     * Set asin
     *
     * @param string $asin
     * @return AmazonIds
     */
    public function setAsin($asin)
    {
        $this->asin = $asin;
    
        return $this;
    }

    /**
     * Get asin
     *
     * @return string 
     */
    public function getAsin()
    {
        return $this->asin;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return AmazonIds
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    
        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}