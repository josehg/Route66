<?php
// src/AppBundle/Twig/AppExtension.php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
            new \Twig_SimpleFilter('enabled', array($this, 'enabledFilter', )),
        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    public function enabledFilter( $enabled )
    {
        if(true == $enabled)
        {
            return <<<EOF
<span class="label label-success">Activo</span>
EOF;
        }
        return <<<EOF
<span class="label label-danger">Inactivo</span>
EOF;
    }

    public function getName()
    {
        return 'app_extension';
    }
}