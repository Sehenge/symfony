<?php

namespace Acme\BinderBundle\EventListener;

use Acme\BinderBundle\AcmeBinderBundle;
use CG\Core\ClassUtils;
use Acme\BinderBundle\Controller;

class BinderExtension extends \Twig_Extension
{
    protected $loader;
    protected $controller;

    public function __construct(\Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'parse' => new \Twig_Function_Method($this, 'parseProducts', array('is_safe' => array('html'))),
        );
    }

    public function parseProducts($products, $coproducts)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $tstart = $mtime[1] + $mtime[0];

        $cache = \Acme\BinderBundle\Controller\BinderController::$cache;
        $template = $cache->get('template');

        if (!$template) {
            $template = '<table class=products><tr><th>UPC</th><th>Model</th><th>Color Code</th><th>Size</th><th>QQB</th><th>QAZ</th><th>PriceQB</th><th>PriceAZ</th></tr>';
            $i=0;
            $size = sizeof($products);
            $handle = fopen('file', 'w');
            fwrite($handle, $i . '/' . $size);
            fclose($handle);
            foreach ($products as $row) {
                $break = false;
                foreach ($coproducts as $key=>$corow) {
                    $pattern = '/' . str_replace('/', '', $row['desc1']) . ' ?- ?CO$/';
                    if (($row['attribute'] == $corow['attribute']) && ($row['size'] == $corow['size']) && preg_match($pattern, $corow['desc1'])) {
                        $row['desc1'] = $corow['desc1'];
                        $row['cost'] = $corow['cost'];
                        $row['quantityonhand'] = $corow['quantityonhand'];
                        $break = true;
                        unset($coproducts[$key]);
                        break;
                    }
                }
                if (!$break) {
                    foreach ($coproducts as $key=>$corow) {
                        $pattern = '"/' . $row['desc1'] . '\s?-\s?VW/"';
                        if (($row['attribute'] == $corow['attribute']) && ($row['size'] == $corow['size']) && (preg_match($pattern, $corow['desc1']))) {
                            $row['quantityonhand'] += $corow['quantityonhand'];
                            unset($coproducts[$key]);
                            break;
                        }
                    }
                }
                if (++$i % 2) {
                    $template .= '<tr class=odd><td>';
                } else {
                    $template .= '<tr class=even><td>';
                }
                $template .= $row['upc'] . '</td><td>' . $row['desc1'] . '</td><td>' . $row['attribute']
                    . '</td><td>' . $row['size'] . '</td><td>' . $row['quantityonhand'] . '</td><td>'
                    . $row['quantity']. '</td><td>' . $row['cost'] . '<b style="float:right;">(' . number_format((($row['cost'] + 30) / 0.85), 2) . ')</b>' . '</td><td>' . $row['regular_price'];
                $template .= '</td></tr>'. "\n";

                if (!($i % 10)) {
                    $handle = fopen('file', 'w');
                    fwrite($handle, $i . '/' . $size);
                    fclose($handle);
                }
            }
            $template .= '</table>';
            $cache->set('template', $template);

            $handle = fopen('file', 'w');
            fwrite($handle, $size . '/' . $size);
            fclose($handle);
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $tend = $mtime[1] + $mtime[0];
        $totaltime = round($tend - $tstart, 4);

        return <<<EOF
$template
EOF;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'binder';
    }
}