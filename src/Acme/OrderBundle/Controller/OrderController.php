<?php

namespace Acme\OrderBundle\Controller;

use Acme\OrderBundle\Form\CheckAvailability;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Acme\DemoBundle\Form\ContactType;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class OrderController extends Controller
{
    /**
     * @Route("/", name="_order")
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->get('form.factory')->create(new CheckAvailability());

        return array('form' => $form->createView());
    }

    /**
     * @Route("/hello", name="_order_hello")
     * @Template()
     */
    public function helloAction()
    {
        $form = $this->get('form.factory')->create(new CheckAvailability());

        $request = $this->get('request');
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                //$this->get('session')->getFlashBag()->set('notice', 'Message sent!');
                $product = $form->getData();
            }
        } else {
            return new RedirectResponse($this->generateUrl('_welcome'));
        }

        /*$_product = $this->getDoctrine()
            ->getRepository('AcmeOrderBundle:Products')
            ->findByModel($product['model']);*/

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();

        if (!$product['color_code'] && !$product['size'] && $product['model']) {
            $_product = $qb->select('products')->from('Acme\OrderBundle\Entity\Products', 'products')
                ->where($qb->expr()->like('products.model', $qb->expr()->literal('%'.$product['model'].'%')))
                ->getQuery()
                ->getResult();
        } else if ($product['color_code'] && $product['model'] && !$product['size']) {
            $_product = $qb->select('products')->from('Acme\OrderBundle\Entity\Products', 'products')
                ->where($qb->expr()->like('products.model', $qb->expr()->literal('%'.$product['model'].'%')))
                ->andWhere($qb->expr()->like('products.color_code', $qb->expr()->literal('%'.$product['color_code'].'%')))
                ->getQuery()
                ->getResult();
        } else if (!$product['color_code'] && $product['model'] && $product['size']) {
            $_product = $qb->select('products')->from('Acme\OrderBundle\Entity\Products', 'products')
                ->where($qb->expr()->like('products.model', $qb->expr()->literal('%'.$product['model'].'%')))
                ->andWhere($qb->expr()->like('products.size', $qb->expr()->literal('%'.$product['size'].'%')))
                ->getQuery()
                ->getResult();
        } else {
            $_product = $qb->select('products')->from('Acme\OrderBundle\Entity\Products', 'products')
                ->where($qb->expr()->like('products.model', $qb->expr()->literal('%'.$product['model'].'%')))
                ->andWhere($qb->expr()->like('products.color_code', $qb->expr()->literal('%'.$product['color_code'].'%')))
                ->andWhere($qb->expr()->like('products.size', $qb->expr()->literal('%'.$product['size'].'%')))
                ->getQuery()
                ->getResult();
        }

        if (!$_product) {
            throw $this->createNotFoundException(
                'No product found for model '. $product['model'] . ' color code ' . $product['color_code'] .
                ' and size '. $product['size']
            );
        }

        return array('result' => $_product,
            'form' => $form->createView());
    }

    /**
     * @Route("/lists/{site}", name="_order_list")
     * @Template()
     */
    public function listsAction($site)
    {
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $_product = $qb->select('products')->from('Acme\OrderBundle\Entity\Products', 'products')
            ->where($qb->expr()->like('products.source', $qb->expr()->literal($site)))
            ->getQuery()
            ->getResult();

        return array('result' => $_product,
            'site' => strtolower($site));
    }

    /**
     * @Route("/lists/{site}/new", name="_order_lists_new")
     * @Template()
     */
    public function listsNewAction($site)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            'SELECT products FROM AcmeOrderBundle:Products products WHERE products.source = :source ORDER BY products.add_date DESC'
        )->setParameter('source', strtoupper($site));

        $_new = $query->getResult();

        return array('site' => strtolower($site),
            'new' => $_new);
    }

    /**
     * @Route("/history/", name="_order_history")
     * @Template()
     */
    public function historyAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $_product = $qb->select('timeline.id, timeline.product_id, timeline.availability, products.brand, products.model')
            ->from('Acme\OrderBundle\Entity\Timeline', 'timeline')
            ->innerJoin('Acme\OrderBundle\Entity\Products', 'products')
            ->where($qb->expr()->like('timeline.availability', $qb->expr()->literal('Available')))
            ->andWhere($qb->expr()->eq('products.id', 'timeline.product_id'))
            ->getQuery()
            ->getResult();
        var_dump($_product);die(1);

        return array('result' => $_product);
    }
}
