<?php

namespace Acme\BinderBundle\EventListener;

use CG\Core\ClassUtils;

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

    public function parseProducts($array)
    {
        $template = '<table class=products><tr><th>UPC</th><th>Model</th><th>Color Code</th><th>Size</th><th>QQB</th><th>QAZ</th><th>PriceQB</th><th>PriceAZ</th></tr>';
        $i=0;
        foreach ($array as $row) {
            if (++$i%2) {
                $template .= '<tr class=odd><td>';
            } else {
                $template .= '<tr class=even><td>';
            }
            $template .= $row['upc'] . '</td><td>' . $row['desc1'] . '</td><td>' . $row['attribute']
                . '</td><td>' . $row['size'] . '</td><td>' . $row['quantityonhand'] . '</td><td>'
                . $row['quantity']. '</td><td>' . $row['cost'] . '</td><td>' . $row['regular_price'];
            $template .= '</td></tr>';
        }
        $template .= '</table>';

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
