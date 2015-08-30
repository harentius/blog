<?php

namespace Harentius\WidgetsBundle\Twig;

class WidgetsExtension extends \Twig_Extension
{
    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('harentius_widget', [$this, 'harentiusWidget'], ['is_safe' => ['html'], 'needs_context' => true]),
        ];
    }

    /**
     *
     */
    public function harentiusWidget($context)
    {
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'harentius_widgets_extension';
    }
}
