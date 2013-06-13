<?php

namespace Acme\OrderBundle\EventListener;

use CG\Core\ClassUtils;

class OrderExtension extends \Twig_Extension
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
            'code' => new \Twig_Function_Method($this, 'getCode', array('is_safe' => array('html'))),
            'helloc' => new \Twig_Function_Method($this, 'getHello', array('is_safe' => array('html'))),
            'history' => new \Twig_Function_Method($this, 'getHistory', array('is_safe' => array('html'))),
            'getbrands' => new \Twig_Function_Method($this, 'getBrands', array('is_safe' => array('html'))),
            'getprices' => new \Twig_Function_Method($this, 'getPrices', array('is_safe' => array('html'))),
            'hellocnew' => new \Twig_Function_Method($this, 'getHelloNew', array('is_safe' => array('html'))),
            'availabilitation' => new \Twig_Function_Method($this, 'getAvailability', array('is_safe' => array('html'))),
        );
    }

    public function getCode($template)
    {
        // highlight_string highlights php code only if '<?php' tag is present.
        $controller = highlight_string("<?php" . $this->getControllerCode(), true);
        $controller = str_replace('<span style="color: #0000BB">&lt;?php&nbsp;&nbsp;&nbsp;&nbsp;</span>', '&nbsp;&nbsp;&nbsp;&nbsp;', $controller);

        $template = htmlspecialchars($this->getTemplateCode($template), ENT_QUOTES, 'UTF-8');

        // remove the code block
        $template = str_replace('{% set code = code(_self) %}', '', $template);

        return <<<EOF
<p><strong>Controller Code</strong></p>
<pre>$controller</pre>

<p><strong>Template Code</strong></p>
<pre>$template</pre>
EOF;
    }

    public function getAvailability($array)
    {
        $string = '';
        foreach ($array as $model) {
            $string .= $model->getModel() . " - "
                . $model->getColorCode() . " - "
                . $model->getSize() . " - "
                . $model->getAvailability() . "<br />";
        }

        return <<<EOF
<p><strong>Results:</strong></p>
<pre>$string</pre>
EOF;
    }

    public function getHello($array, $site)
    {
        $string = '';
        if ($site == 'luxottica') {
            $head = '<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Brand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp; Model &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp; Color Code &nbsp;-&nbsp; Size &nbsp;-&nbsp;&nbsp; Price &nbsp;&nbsp;&nbsp;-&nbsp; R.Price &nbsp;- Availability</strong></p>';
        } else if ($site == 'mysafilo') {
            $head = '<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UPC - &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Brand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp; Model &nbsp;- Color Code -&nbsp;&nbsp;&nbsp;&nbsp; Size &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp; Price &nbsp;&nbsp;&nbsp;- Availability</strong></p>';
        } else {
            $head = '<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Brand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp; Model &nbsp;- Color Code -&nbsp;&nbsp;&nbsp;&nbsp; Size &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp; Price &nbsp;&nbsp;&nbsp;- Availability</strong></p>';
        }
        foreach ($array as $elem) {
            if ($elem->getAvailability() == 'Available') {
                $string .= '<div class="green">';
            } else {
                $string .= '<div>';
            }
            if ($site == 'luxottica') {
                $string .=
                    '<div class="brand">' . $elem->getBrand() . '</div> - <div class="model"><strong>'
                    . $elem->getModel() . '</strong></div> - <div class="ccolor">'
                    . $elem->getColorCode() . '</div> - '
                    . $elem->getSize() . ' - <div class="price"><strong>$'
                    . $elem->getPrice() . '</div> - <div class="price">$'
                    . $elem->getRetailPrice() . '</div> - '
                    . $elem->getAvailability() . '</strong> ';
                if ($elem->getImage()) {
                    $string .= '<a class="model_img" rel="' . $elem->getImage() . '">Show image</a>';
                }
                $string .= '</div>';
            } else if ($site == 'mysafilo') {
                $string .=
                    $elem->getUpc() . ' - '
                    .'<div class="brand">' . $elem->getBrand() . '</div> - <div class="model"><strong>'
                    . $elem->getModel() . '</strong></div> - <div class="ccolor">'
                    . $elem->getColorCode() . '</div> - '
                    . $elem->getSize() . ' - <div class="price"><strong>$'
                    . $elem->getPrice() . '</div> - '
                    . $elem->getAvailability() . '</strong> ';
                if ($elem->getImage()) {
                    $string .= '<a class="model_img" rel="' . $elem->getImage() . '">Show image</a>';
                }
                $string .= '</div>';
            } else {
                $string .=
                    '<div class="brand">' . $elem->getBrand() . '</div> - <div class="model"><strong>'
                    . $elem->getModel() . '</strong></div> - <div class="ccolor">'
                    . $elem->getColorCode() . '</div> - '
                    . $elem->getSize() . ' - <div class="price"><strong>$'
                    . $elem->getPrice() . '</div> - '
                    . $elem->getAvailability() . '</strong> ';
                if ($elem->getImage()) {
                    $string .= '<a class="model_img" rel="' . $elem->getImage() . '">Show image</a>';
                }
                $string .= '</div>';
            }
        }

        return <<<EOF
        $head
        <pre>$string</pre>
EOF;
    }

    public function getHelloNew($new, $site)
    {
        $string = '';
        if ($site == 'luxottica') {
            $head = '<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Brand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp; Model &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp; Color Code &nbsp;-&nbsp; Size &nbsp;-&nbsp;&nbsp; Price &nbsp;&nbsp;&nbsp;-&nbsp; R.Price &nbsp;- Availability</strong></p>';
        } else if ($site == 'mysafilo') {
            $head = '<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UPC - &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Brand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp; Model &nbsp;- Color Code -&nbsp;&nbsp;&nbsp;&nbsp; Size &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp; Price &nbsp;&nbsp;&nbsp;- Availability</strong></p>';
        } else {
            $head = '<p><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Brand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp; Model &nbsp;- Color Code -&nbsp;&nbsp;&nbsp;&nbsp; Size &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp; Price &nbsp;&nbsp;&nbsp;- Availability</strong></p>';
        }
        foreach ($new as $elem) {
            if ($elem->getAvailability() == 'Available') {
                $string .= '<div class="green">';
            } else {
                $string .= '<div>';
            }
            $cur_time = date('Y-m-d', time());
            $add_time = date('Y-m-d', date_timestamp_get($elem->getAddDate()));

            $cur_day = substr($cur_time,8, 2);
            $add_day = substr($add_time,8, 2);

            if (($cur_day - $add_day) < 2) {
                if ($site == 'luxottica') {
                    $string .=
                        '<div class="brand">' . $elem->getBrand() . '</div> - <div class="model"><strong>'
                        . $elem->getModel() . '</strong></div> - <div class="ccolor">'
                        . $elem->getColorCode() . '</div> - '
                        . $elem->getSize() . ' - <div class="price">$'
                        . $elem->getPrice() . '</div> - <div class="price">$'
                        . $elem->getRetailPrice() . '</div> - <strong>'
                        . $elem->getAvailability() . '</strong> - <div class="add_date">'
                        . date('Y-m-d', date_timestamp_get($elem->getAddDate())) . '</div></div>';
                } else if ($site == 'mysafilo') {
                    $string .=
                        $elem->getUpc() . ' - '
                        .'<div class="brand">' . $elem->getBrand() . '</div> - <div class="model"><strong>'
                        . $elem->getModel() . '</strong></div> - <div class="ccolor">'
                        . $elem->getColorCode() . '</div> - '
                        . $elem->getSize() . ' - <div class="price">$'
                        . $elem->getPrice() . '</div> - <strong>'
                        . $elem->getAvailability() . '</strong> - <div class="add_date">'
                        . date('Y-m-d', date_timestamp_get($elem->getAddDate())) . '</div></div>';
                } else {
                    $string .=
                        '<div class="brand">' . $elem->getBrand() . '</div> - <div class="model"><strong>'
                        . $elem->getModel() . '</strong></div> - <div class="ccolor">'
                        . $elem->getColorCode() . '</div> - '
                        . $elem->getSize() . ' - <div class="price">$'
                        . $elem->getPrice() . '</div> - <strong>'
                        . $elem->getAvailability() . '</strong> - <div class="add_date">'
                        . date('Y-m-d', date_timestamp_get($elem->getAddDate())) . '</div></div>';
                }
            }
        }

        return <<<EOF
        $head
        <pre>$string</pre>
EOF;
    }

    protected function getControllerCode()
    {
        $class = get_class($this->controller[0]);
        if (class_exists('CG\Core\ClassUtils')) {
            $class = ClassUtils::getUserClass($class);
        }

        $r = new \ReflectionClass($class);
        $m = $r->getMethod($this->controller[1]);

        $code = file($r->getFilename());

        return '    '.$m->getDocComment()."\n".implode('', array_slice($code, $m->getStartline() - 1, $m->getEndLine() - $m->getStartline() + 1));
    }

    protected function getTemplateCode($template)
    {
        return $this->loader->getSource($template->getTemplateName());
    }

    /**
     * @param $array
     *
     * @return string
     */
    public function getHistory($array)
    {
        $string = '';
        foreach($array as $row) {
            $string .= $row->getId();
        }

        return <<<EOF
        <pre>$string</pre>
EOF;
    }

    public function getBrands($array)
    {
        $string = '<div class="sdiv"><span class="slabel">Brand</span><select id=brands onchange="getModelsByBrand($(this).val())">';
        //var_dump($array);die(1);
        foreach($array as $row) {
            $string .= '<option>' . $row['brand'] . '</option>';
        }
        $string .= '</select></div>';

        return <<<EOF
        <pre>$string</pre>
EOF;
    }

    public function getPrices($array)
    {
        $string = '<div class="amazon_price_div">';
        $string .= '<div class=a_head><span style="margin-left:0;width:20px;">Asin</span><span style="width:130px">Brand - Model</span><span style="margin:0 0 0 130px">Landed Price</span><span>Listing Price</span><span style="margin:0 0 0 0px">Regular Price</span></div>';
        foreach($array as $row) {
            $string .= '<div class=inner><span style="margin-left:0;width:20px;"><a href="http://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords=' . $row->getAsin() . '" target="_blank">' . $row->getAsin() . '</a></span>'
            . '<span style="width:280px">' . $row->getBrand() . ' - ' .$row->getModel() . '</span>'
            . '<span style="width:50px">' . $row->getLandedPrice() . '</span>'
            . '<span style="width:50px">' . $row->getListingPrice() . '</span>'
            . '<span>' . $this->checkPrice($row->getBrand(), $row->getRegularPrice()) . '</span></div>';
        }
        $string .= '</div>';

        return <<<EOF
        <pre>$string</pre>
EOF;
    }

    private function checkPrice($brand, $price)
    {
        switch($brand) {
            case 'Calvin Klein' : $minPrice = 130; break;
            case 'ADIDAS' : $minPrice = 50; break;
                default : $minPrice = 0;
        }


        if ($price > $minPrice) {
            return $price . '!';
        } else {
            return $price;
        }
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'order';
    }
}
