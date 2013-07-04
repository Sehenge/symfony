<?php

namespace Acme\BinderBundle\Controller;

use Acme\SyncBundle\Form\CheckAvailability;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Form\Tests\Extension\Core\DataTransformer\BooleanToStringTransformerTest;
use Symfony\Component\HttpFoundation\RedirectResponse;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BinderController extends Controller
{
    /**
     * @Route("/", name="_binder")
     * @Template()
     */
    public function indexAction()
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $tstart = $mtime[1] + $mtime[0];

        $cache = $this->get('memcache');

        $mprod = $cache->get('products');

        if ($mprod) {
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $tend = $mtime[1] + $mtime[0];
            $totaltime = round($tend - $tstart, 4);
            return array('products' => $mprod, 'time' => $totaltime);
        } else {
            $em = $this->getDoctrine()->getManager('amazon');
            $qb = $em->createQueryBuilder();
            $products = $qb->select('products.regular_price, products.sku, stu.upc, qbprod.desc1, qbprod.desc2, qbprod.size, qbprod.attribute, qbprod.cost, qbprod.quantityonhand, azids.quantity')
                ->from('Acme\BinderBundle\Entity\SkuToUpc', 'stu')
                ->innerJoin('Acme\OrderBundle\Entity\AmazonProductsPrice', 'products', 'WITH', 'products.sku = stu.sku')
                ->innerJoin('Acme\BinderBundle\Entity\QbProductsInfo', 'qbprod', 'WITH', 'stu.upc = SUBSTRING(qbprod.upc, 2, 12)')
                ->innerJoin('Acme\BinderBundle\Entity\AmazonIds', 'azids', 'WITH', 'stu.sku = azids.seller_sku')
                ->getQuery()
                ->getResult();
            $cache->set('products', $products);

            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $tend = $mtime[1] + $mtime[0];
            $totaltime = round($tend - $tstart, 4);
            return array('products' => $products, 'time' => $totaltime);
        }




    }


}
