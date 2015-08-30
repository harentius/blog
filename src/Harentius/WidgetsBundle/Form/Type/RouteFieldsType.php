<?php

namespace Harentius\WidgetsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteFieldsType extends AbstractType
{
    /**
     * @var array
     */
    private $routes;

    /**
     *
     */
    public function __construct()
    {
        $this->routes = [];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['route'] === null) {
            return;
        }

        if (!isset($this->routes[$options['route']])) {
            throw new \InvalidArgumentException(sprintf('Unknown route "%s"', $options['route']));
        }

        $routeParameters = $this->routes[$options['route']]['parameters'];

        foreach ($routeParameters as $key => $parameter) {
            $builder->add($key, 'entity', [
                'class' => $parameter['source']['class'],
                'property' => $parameter['source']['identityField'],
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('route', null);
    }

    /**
     * @param string $key
     * @param string $route
     */
    public function registerRoute($key, $route)
    {
        $this->routes[$key] = $route;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'harentius_widget_route_fields';
    }
}
