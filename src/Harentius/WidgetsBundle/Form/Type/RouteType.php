<?php

namespace Harentius\WidgetsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteType extends AbstractType
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
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $routesChoice = [];

        foreach ($this->routes as $key => $route) {
            $routesChoice[$key] = $route['name'];
        }

        $builder->add('route', 'choice', [
            'label' => 'Route',
            'choices' => $routesChoice,
            'empty_data' => null,
            'placeholder' => 'All'
        ]);
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
        return 'harentius_widget_route';
    }
}
