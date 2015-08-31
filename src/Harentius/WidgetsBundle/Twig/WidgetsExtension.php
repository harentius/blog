<?php

namespace Harentius\WidgetsBundle\Twig;

use Harentius\WidgetsBundle\Entity\WidgetRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class WidgetsExtension extends \Twig_Extension
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var WidgetRepository
     */
    private $widgetRepository;

    /**
     * @param RequestStack $requestStack
     * @param WidgetRepository $widgetRepository
     */
    public function __construct(RequestStack $requestStack, WidgetRepository $widgetRepository)
    {
        $this->request = $requestStack->getMasterRequest();
        $this->widgetRepository = $widgetRepository;
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('harentius_widget', [$this, 'harentiusWidget'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param $position
     * @return string
     */
    public function harentiusWidget($position)
    {
        $parameters = $this->request->attributes->get('_route_params');
        ksort($parameters);
        $route = [
            'name' => $this->request->attributes->get('_route'),
            'parameters' => $parameters
        ];
        $widgets = $this->widgetRepository->findByRouteOrNullRouteAndPositionOrderedByPriority($route, $position);
        $result = '';

        foreach ($widgets as $widget) {
            $result .= $widget->getContent();
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'harentius_widgets_extension';
    }
}
