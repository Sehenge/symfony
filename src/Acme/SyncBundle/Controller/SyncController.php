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
        //curl_exec($ch);
        curl_close($ch);

        //sleep(5);
        $cf = curl_init('http://eyewearconnection.com/admin/generator/out.txt');
        curl_setopt($cf, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cf, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($cf, CURLOPT_USERPWD, 'sites:restrictedzone');
        $data = curl_exec($cf);
        curl_close($cf);

        $handle = fopen('syncfeeds/ewcout.csv', 'w');
        fwrite($handle, $data);
        fclose($handle);

        return new \Symfony\Component\HttpFoundation\Response('Ewc feed successfully exported!');
    }

    /**
     * @Route("/importexb/")
     */
    public function importExb()
    {
        $last_id = file_get_contents('http://exboutique.com/import/syncid.php');

        $fp = fopen('syncfeeds/exbimport.csv', 'w');
        if (($handle = fopen("syncfeeds/ewcout.txt", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {
                if ($data[2] > $last_id) {
                    $data[18] = '"' . preg_replace("/eyewearconnection/", "affordableluxurygroup", $data[18]) . '"';
                    fputcsv($fp, $data, ',', "\0");
                }
            }
        }
        fclose($fp);
        fclose($handle);

        $feed = file_get_contents('syncfeeds/exbimport.csv');
        $feed = str_replace("\0", '', $feed);

        $ce = curl_init('http://exboutique.com/admin/import/ewcsync.php');
        curl_setopt($ce, CURLOPT_POST, 1);
        curl_setopt($ce, CURLOPT_POSTFIELDS, array('ewcfeed' => json_encode($feed)));
        curl_setopt($ce, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ce, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ce, CURLOPT_USERPWD, 'sites:restrictedzone');
        curl_exec($ce);
        curl_close($ce);

        return new \Symfony\Component\HttpFoundation\Response('Successfully imported to Exboutique');
    }

    /**
     * @Route("/importshdx/")
     */
    public function importShdx()
    {
        $last_id = file_get_contents('http://www.shadesexpo.com/converter/syncid.php');

        $fp = fopen('syncfeeds/shdximport.csv', 'w');
        if (($handle = fopen("syncfeeds/ewcout.txt", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {
                if ($data[2] > $last_id) {
                    $data[18] = '"' . preg_replace("/eyewearconnection/", "affordableluxurygroup", $data[18]) . '"';
                    fputcsv($fp, $data, "\t", "\0");
                }
            }
        }
        fclose($fp);
        fclose($handle);

        $feed = file_get_contents('syncfeeds/shdximport.csv');
        $feed = str_replace("\0", '', $feed);

        $cs = curl_init('http://www.shadesexpo.com/converter/Syncer.php');
        curl_setopt($cs, CURLOPT_POST, 1);
        curl_setopt($cs, CURLOPT_POSTFIELDS, array('shdxfeed' => json_encode($feed)));
        curl_setopt($cs, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($cs);
        curl_close($cs);

        return new \Symfony\Component\HttpFoundation\Response('Shadesexpo feed was generated');
    }

    /**
     * @Route("/importexbw/")
     */
    public function importExbw()
    {
        $data = file_get_contents('http://www.shadesexpo.com/converter/exbfeedmover.php');
        $shdxfeedhandle = fopen('syncfeeds/shdxexport.csv', 'w');
        fwrite($shdxfeedhandle, json_decode($data));
        fclose($shdxfeedhandle);
        //die(1);



        return new \Symfony\Component\HttpFoundation\Response('Successfully imported to Exboutique (watches)');
    }
}
