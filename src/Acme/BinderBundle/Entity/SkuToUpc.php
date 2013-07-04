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
 * @ORM\Table(name="sku_to_upc")
 */
class SkuToUpc
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
    protected $sku;
    /**
     * @ORM\Column(type="integer")
     */
    protected $upc;

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $asin;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $approve;

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
     * Set sku
     *
     * @param string $sku
     * @return SkuToUpc
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
     * Set upc
     *
     * @param integer $upc
     * @return SkuToUpc
     */
    public function setUpc($upc)
    {
        $this->upc = $upc;
    
        return $this;
    }

    /**
     * Get upc
     *
     * @return integer 
     */
    public function getUpc()
    {
        return $this->upc;
    }

    /**
     * Set asin
     *
     * @param string $asin
     * @return SkuToUpc
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
     * Set approve
     *
     * @param string $approve
     * @return SkuToUpc
     */
    public function setApprove($approve)
    {
        $this->approve = $approve;
    
        return $this;
    }

    /**
     * Get approve
     *
     * @return string 
     */
    public function getApprove()
    {
        return $this->approve;
    }
}