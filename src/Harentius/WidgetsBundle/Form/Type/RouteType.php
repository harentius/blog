<?php

namespace Harentius\WidgetsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class RouteType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'harentius_widget_route';
    }
}
