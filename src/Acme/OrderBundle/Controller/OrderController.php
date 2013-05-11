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

        $_product = $this->getDoctrine()
            ->getRepository('AcmeOrderBundle:Products')
            ->findByModel($product['model']);

        if (!$_product) {
            throw $this->createNotFoundException(
                'No product found for model '. $product['model']
            );
        }

        return array('name' => $_product[0]->getAvailability(),
        'model' => $product['model'],
        'size' => $product['size'],
        'color_code' => $product['color_code'],
        'form' => $form->createView());
    }

    /**
     * @Route("/contact", name="_demo_contact")
     * @Template()
     */
    public function contactAction()
    {
        $form = $this->get('form.factory')->create(new ContactType());

        $request = $this->get('request');
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
                $mailer = $this->get('mailer');
                // .. setup a message and send it
                // http://symfony.com/doc/current/cookbook/email.html

                $this->get('session')->getFlashBag()->set('notice', 'Message sent!');

                return new RedirectResponse($this->generateUrl('_demo'));
            }
        }

        return array('form' => $form->createView());
    }
}
