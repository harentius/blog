<?php

namespace Harentius\WidgetsBundle\Form\DataTransformer;

use Harentius\WidgetsBundle\Entity\Widget;
use Symfony\Component\Form\DataTransformerInterface;

class WidgetAdminDataTransformer implements DataTransformerInterface
{
    /**
     * @param Widget $value
     * @return Widget
     */
    public function transform($value)
    {
        if ($value === null) {
            return $value;
        }

        $value->setRouteData([
            'name' => $value->getRoute(),
            'parameters' => $value->getRouteParameters(),
        ]);

        return $value;
    }

    /**
     * @param Widget $value
     * @return Widget
     */
    public function reverseTransform($value)
    {
        if ($value === null) {
            return $value;
        }

        $routeData = $value->getRouteData();
        $value
            ->setRoute($routeData['name'])
            ->setRouteParameters($routeData['parameters'])
        ;

        return $value;
    }
}
