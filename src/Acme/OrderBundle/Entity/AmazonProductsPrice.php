<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 6/7/13
 * Time: 6:14 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Acme\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="amazon_products_price")
 */
class AmazonProductsPrice 
{
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="float")
     */
    protected $landed_price;
    /**
     * @ORM\Column(type="float")
     */
    protected $listing_price;

    /**
     * @ORM\Column(type="float")
     */
    protected $regular_price;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $sku;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $asin;

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
     * Set landed_price
     *
     * @param float $landedPrice
     * @return AmazonProductsPrice
     */
    public function setLandedPrice($landedPrice)
    {
        $this->landed_price = $landedPrice;
    
        return $this;
    }

    /**
     * Get landed_price
     *
     * @return float 
     */
    public function getLandedPrice()
    {
        return $this->landed_price;
    }

    /**
     * Set listing_price
     *
     * @param float $listingPrice
     * @return AmazonProductsPrice
     */
    public function setListingPrice($listingPrice)
    {
        $this->listing_price = $listingPrice;
    
        return $this;
    }

    /**
     * Get listing_price
     *
     * @return float 
     */
    public function getListingPrice()
    {
        return $this->listing_price;
    }

    /**
     * Set regular_price
     *
     * @param float $regularPrice
     * @return AmazonProductsPrice
     */
    public function setRegularPrice($regularPrice)
    {
        $this->regular_price = $regularPrice;
    
        return $this;
    }

    /**
     * Get regular_price
     *
     * @return float 
     */
    public function getRegularPrice()
    {
        return $this->regular_price;
    }

    /**
     * Set sku
     *
     * @param string $sku
     * @return AmazonProductsPrice
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    
        return $this;
    }

    /**
     * Get sku
     *
     * @return string 
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Set asin
     *
     * @param string $asin
     * @return AmazonProductsPrice
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
}