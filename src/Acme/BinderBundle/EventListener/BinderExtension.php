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
            'parseEbay' => new \Twig_Function_Method($this, 'parseEbay', array('is_safe' => array('html'))),
            'undeflist' => new \Twig_Function_Method($this, 'parseAsins', array('is_safe' => array('html'))),
        );
    }

    /**
     * @param $products
     * @param $coproducts
     * @return string
     */
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
                    $template .= '<tr class="odd';
                } else {
                    $template .= '<tr class="even';
                }
                $minimum = number_format((($row['cost'] + 30) / 0.85), 2);
                if (!$this->checkPrice($minimum, $row['regular_price'], $row['approved'])) {
                    $template .= ' warn';
                }
                $template .= '"><td id=upc><a href="http://www.amazon.com/s/ref=nb_sb_noss/185-6549131-8962738?url=search-alias%3Daps&field-keywords=' . $row['upc'] . '" target="_blank">' . $row['upc']
                    . '</a></td><td>' . $row['desc1'] . '</td><td>' . $row['attribute']
                    . '</td><td>' . $row['size'] . '</td><td>' . $row['quantityonhand'] . '</td><td>'
                    . $row['quantity']. '</td><td>' . $row['cost'] . '<b style="float:right;">(' . $minimum . ')</b>' . '</td><td>' . $row['regular_price'];
                $template .= '</td><td class=buttons><div class="approve decline"></div><div class="approve accept"></div></td></tr>'. "\n";

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

    public function parseEbay($products)
    {
        $template = '<table id=ebay_temp>';
        foreach ($products as $row) {
            $template .= '<tr><td class=itemid upc="' . $row['upc'] . '">' . $row['itemid'] . '</td><td>' . $row['ebay_name'] . '</td><td>'
                . $row['desc1'] . '</td><td>'
                . $row['attribute'] . '</td><td>'
                . $row['size'] . '</td></tr>';
        }
        $template .= '</table>';

        return $template;
    }

    /**
     * @param $minimal
     * @param $price
     * @param $approve  0 - decline, 1 - accept
     * @return bool
     */
    private function checkPrice($minimal, $price, $approve)
    {
        if (!$approve && ($price < $minimal)) {
            return false;
        }

        return true;
    }

    public function parseAsins($asins)
    {
        $template = "";

        foreach ($asins as $asin) {
            $template .= $asin['asin'] . ' - ' . $asin['model'] . ' - ' . $asin['desc1'] . ' - ' . $asin['attribute'] . ' - ' . $asin['size'] . '<br />';
        }

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