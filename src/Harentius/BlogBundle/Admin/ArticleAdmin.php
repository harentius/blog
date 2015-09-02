<?php

namespace Harentius\BlogBundle\Admin;

use Doctrine\Common\Cache\CacheProvider;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleAdmin extends AbstractPostAdmin
{
    /**
     * @var CacheProvider
     */
    private $cache;

    /**
     * {@inheritDoc}
     */
    public function prePersist($object)
    {
        $this->cache->deleteAll();
    }

    /**
     * {@inheritDoc}
     */
    public function preUpdate($object)
    {
        $this->cache->deleteAll();
    }

    /**
     * @param CacheProvider $cache
     */
    public function setControllerCache(CacheProvider $cache)
    {
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('slug')
            ->add('category')
            ->add('tags')
            ->add('author')
            ->add('isPublished')
            ->add('publishedAt')
            ->add('metaDescription')
            ->add('metaKeywords')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ]
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('slug', null, [
                'required' => false,
            ])
            ->add('category')
//            ->add('tags', 'sonata_type_model_autocomplete', array(
//                'property' => 'name',
//                'multiple' => 'true',
//            ))
            ->add('text', 'textarea', [
                'attr' => ['class' => 'blog-page-edit'],
            ])
            ->add('isPublished', null, [
                'required' => false,
            ])
            ->add('publishedAt', 'sonata_type_date_picker', [
                'required' => false,
                'format' => 'dd MM y',
            ])
            ->add('author')
            ->add('metaDescription', 'textarea', [
                'required' => false,
            ])
            ->add('metaKeywords', 'text', [
                'required' => false,
            ])
        ;
    }
}
