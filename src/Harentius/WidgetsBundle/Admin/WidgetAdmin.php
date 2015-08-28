<?php

namespace Harentius\WidgetsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class WidgetAdmin extends Admin
{
    /**
     * {@inheritDoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name')
            ->add('route')
            ->add('position')
            ->add('isPhpUse')
            ->add('backLink')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text')
            ->add('route')
            ->add('position', 'harentius_widget_position')
            ->add('isPhpUse', null, [
                'label' => 'Contains php code',
                'required' => false,
            ])
            ->add('content')
            ->add('backLink', 'text', [
                'label' => 'Backlink',
                'required' => false,
            ])
        ;
    }
}
