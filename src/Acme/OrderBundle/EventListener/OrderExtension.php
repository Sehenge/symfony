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
                    . $elem->getAvailability() . '</strong></div>';
            } else if ($site == 'mysafilo') {
                $string .=
                    $elem->getUpc() . ' - '
                    .'<div class="brand">' . $elem->getBrand() . '</div> - <div class="model"><strong>'
                    . $elem->getModel() . '</strong></div> - <div class="ccolor">'
                    . $elem->getColorCode() . '</div> - '
                    . $elem->getSize() . ' - <div class="price"><strong>$'
                    . $elem->getPrice() . '</div> - '
                    . $elem->getAvailability() . '</strong></div>';
                    //. date('Y-m-d H:m:s', date_timestamp_get($elem->getAddDate())) . '</div>';
            } else {
                $string .=
                    '<div class="brand">' . $elem->getBrand() . '</div> - <div class="model"><strong>'
                    . $elem->getModel() . '</strong></div> - <div class="ccolor">'
                    . $elem->getColorCode() . '</div> - '
                    . $elem->getSize() . ' - <div class="price"><strong>$'
                    . $elem->getPrice() . '</div> - '
                    . $elem->getAvailability() . '</strong></div>';
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
                        . $elem->getSize() . ' - <div class="price"><strong>$'
                        . $elem->getPrice() . '</div> - <div class="price">$'
                        . $elem->getRetailPrice() . '</div> - '
                        . $elem->getAvailability() . '</strong> - '
                        . date('Y-m-d H:m:s', date_timestamp_get($elem->getAddDate())) . '</div>';
                } else if ($site == 'mysafilo') {
                    $string .=
                        $elem->getUpc() . ' - '
                        .'<div class="brand">' . $elem->getBrand() . '</div> - <div class="model"><strong>'
                        . $elem->getModel() . '</strong></div> - <div class="ccolor">'
                        . $elem->getColorCode() . '</div> - '
                        . $elem->getSize() . ' - <div class="price"><strong>$'
                        . $elem->getPrice() . '</div> - '
                        . $elem->getAvailability() . '</strong> - '
                        . date('Y-m-d H:m:s', date_timestamp_get($elem->getAddDate())) . '</div>';
                } else {
                    $string .=
                        '<div class="brand">' . $elem->getBrand() . '</div> - <div class="model"><strong>'
                        . $elem->getModel() . '</strong></div> - <div class="ccolor">'
                        . $elem->getColorCode() . '</div> - '
                        . $elem->getSize() . ' - <div class="price"><strong>$'
                        . $elem->getPrice() . '</div> - '
                        . $elem->getAvailability() . '</strong> - '
                        . date('Y-m-d H:m:s', date_timestamp_get($elem->getAddDate())) . '</div>';
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
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'order';
    }
}
