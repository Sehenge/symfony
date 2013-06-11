<?php

namespace Acme\SyncBundle\Controller;

use Acme\SyncBundle\Form\CheckAvailability;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Form\Tests\Extension\Core\DataTransformer\BooleanToStringTransformerTest;
use Symfony\Component\HttpFoundation\RedirectResponse;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SyncController extends Controller
{
    /**
     * @Route("/", name="_sync")
     * @Template()
     */
    public function indexAction()
    {
        return array('ewcfeed' => 'No feeds uploaded!',
        'exbfeed' => 'Not imported to Exb',
        'shdxfeed' => 'Not imported to Shdx',
        'exbwfeed' => 'Not imported to Exb (watches)');
    }

    /**
     * @Route("/getewc/")
     */
    public function getEwcAction()
    {
        //echo '123';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://eyewearconnection.com/admin/generator/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, 'sites:restrictedzone');
        curl_close($ch);
        //curl_exec($ch);

        //sleep(5);
        $cf = curl_init('http://eyewearconnection.com/admin/generator/out.txt');
        curl_setopt($cf, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cf, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($cf, CURLOPT_USERPWD, 'sites:restrictedzone');
        $data = curl_exec($cf);
        $handle = fopen('syncfeeds/ewcout.txt', 'w');
        fwrite($handle, $data);
        fclose($handle);
        return new \Symfony\Component\HttpFoundation\Response('ewcout.txt');

    }
}
