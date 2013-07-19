<?php

namespace Acme\BinderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Form\Tests\Extension\Core\DataTransformer\BooleanToStringTransformerTest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BinderController extends Controller
{
    public static $cache;

    /**
     * @Route("/", name="_binder")
     * @Template()
     */
    public function indexAction()
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $tstart = $mtime[1] + $mtime[0];

        self::$cache = $this->get('memcache');
        $mprod = self::$cache->get('products');
        $mcoprod = self::$cache->get('coproducts');

        if ($mprod && $mcoprod) {
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $tend = $mtime[1] + $mtime[0];
            $totaltime = round($tend - $tstart, 4);
            return array('products' => $mprod, 'time' => $totaltime, 'coproducts' => $mcoprod);
        } else {
            return $this->render('AcmeBinderBundle:Binder:load.html.twig');
        }
    }

    /**
     * @Route("/clear/", name="_binder_clear")
     * @Template()
     */
    public function clearCacheAction()
    {
        self::$cache = $this->get('memcache');
        self::$cache->delete('template');
        self::$cache->delete('coproducts');
        self::$cache->delete('products');

        $handle = fopen('file', 'w');
        fwrite($handle, '0/1');
        fclose($handle);

        return new Response((string) 'Cache was cleared!');
    }

    /**
     * @Route("/load/", name="_binder_load")
     * @Template()
     */
    public function loadAction()
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $tstart = $mtime[1] + $mtime[0];
        self::$cache = $this->get('memcache');

        $em = $this->getDoctrine()->getManager('amazon');
        $qb = $em->createQueryBuilder();
        $products = $qb->select('products.regular_price, products.sku, stu.upc, qbprod.desc1, qbprod.desc2, qbprod.size, qbprod.attribute, qbprod.cost, qbprod.quantityonhand, azids.quantity, products.approved')
            ->from('Acme\BinderBundle\Entity\SkuToUpc', 'stu')
            ->innerJoin('Acme\OrderBundle\Entity\AmazonProductsPrice', 'products', 'WITH', 'products.sku = stu.sku')
            ->innerJoin('Acme\BinderBundle\Entity\QbProductsInfo', 'qbprod', 'WITH', 'stu.upc = SUBSTRING(qbprod.upc, 2, 12)')
            ->innerJoin('Acme\BinderBundle\Entity\AmazonIds', 'azids', 'WITH', 'stu.sku = azids.seller_sku')
            ->getQuery()
            ->getResult();
        self::$cache->set('products', $products);

        $co_products = array();
        $conn = $this->container->get('amazon_connection');
        $sql = 'SELECT qbprod.desc1, qbprod.desc2, qbprod.size, qbprod.attribute, qbprod.cost, qbprod.quantityonhand
                FROM quickbooks_products_info qbprod
                WHERE qbprod.desc1 REGEXP \'VW|CO$\'';
        $stm = $conn->query($sql);
        while($row = $stm->fetch()) {
            $co_products[] = $row;
        }
        self::$cache->set('coproducts', $co_products);

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $tend = $mtime[1] + $mtime[0];
        $totaltime = round($tend - $tstart, 4);
        return array('products' => $products, 'coproducts' => $co_products, 'time' => $totaltime);
    }

    /**
     * @Route("/check/{process}", name="_binder_check")
     * @Template()
     */
    public function checkAction($process)
    {
        if ($process == 'dbLoading') {
            $handle = fopen ('file', 'r');
            $line = fread($handle, filesize('file'));
            $arr = explode('/', $line);
        }

        return new Response((string) json_encode($arr));
    }

    /**
     * @Route("/changeapprove/{upc}")
     * @Template()
     */
    public function changeApprovalAction($upc)
    {
        $request = $this->get('request');
        if ($request->isMethod('POST') && $request->get('approve')) {
            if ($request->get('approve') === 'accept') {
                $approve = 1;
            } else {
                $approve = 0;
            }

            $qb = $this->getDoctrine()->getManager('amazon')->createQueryBuilder();
            $qb->update('Acme\OrderBundle\Entity\AmazonProductsPrice', 'amz')
                ->set('amz.approved', $approve)
                ->where('amz.asin IN (SELECT stu.asin FROM Acme\BinderBundle\Entity\SkuToUpc stu WHERE stu.upc = ' . $upc . ')')
                //->where($qb->expr()->eq('stu.upc', $upc))
                ->getQuery()
                ->execute();
            return new \Symfony\Component\HttpFoundation\Response((string) $upc);
        }
        return new \Symfony\Component\HttpFoundation\Response((string) 'false!!!');
    }

    /**
     * @Route("/list/")
     * @Template()
     */
    public function undefListAction()
    {
        $conn = $this->container->get('amazon_connection');
        $asins = array();
        $sql = 'SELECT p.asin, p.model, qb.desc1
                FROM amazon_products_price p, quickbooks_products_info qb
                WHERE qb.desc1 REGEXP p.model
                AND p.asin NOT IN (
                    SELECT asin
                    FROM sku_to_upc
                )';
        $stm = $conn->query($sql);
        while($row = $stm->fetch()) {
            $asins[] = $row;
        }

        return array('asins' => $asins);
    }

    /**
     * @Route("/sync/{asin}/{action}")
     * @Template()
     */
    public function syncApproveAction($asin, $action)
    {

    }

    /**
     * @Route("/ebay")
     * @Template()
     */
    public function ebayAction()
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $tstart = $mtime[1] + $mtime[0];

        $conn = $this->container->get('amazon_connection');
        $_products = array();
        $sql = 'SELECT eb.itemid, eb.ebay_name , qb.desc1, qb.attribute, qb.size, qb.cost, eb.upc
                FROM ebay_products_temp eb
                INNER JOIN quickbooks_products_info qb ON qb.ListId = eb.qb_id
                WHERE eb.itemid IN (
                SELECT tb.itemid
                FROM ebay_products_temp tb
                GROUP BY tb.itemid
                HAVING COUNT(*) = 1
                )
                ORDER BY eb.itemid DESC';
        $stm = $conn->query($sql);
        while($row = $stm->fetch()) {
            $_products[] = $row;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $tend = $mtime[1] + $mtime[0];
        $totaltime = round($tend - $tstart, 4);

        return array('products' => $_products, 'time' => $totaltime);
    }
}
