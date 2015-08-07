<?php

namespace Harentius\BlogBundle\Admin;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Harentius\BlogBundle\Entity\Article;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ArticleAdmin extends Admin
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public function prePersist($object)
    {
        /** @var Article $object*/
        if ($object->getIsPublished() && !$object->getPublishedAt()) {
            $object->setPublishedAt(new \DateTime());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function preUpdate($object)
    {
        /** @var Article $object*/
        $article = $this->em->getRepository('HarentiusBlogBundle:Article')->find($object->getId());

        if (!$article->getIsPublished() && $object->getIsPublished() && !$object->getPublishedAt()) {
            $object->setPublishedAt(new \DateTime());
        }
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
            ->add('publishedAt', 'datetime', [
                'required' => false,
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

    /**
     * {@inheritDoc}
     */
    public function createQuery($context = 'list')
    {
        /** @var QueryBuilder $query */
        $query = parent::createQuery($context);
        $alias = $query->getRootAliases()[0];
        $query
            ->orderBy($alias . '.isPublished', 'ASC')
            ->addOrderBy($alias . '.updatedAt', 'DESC')
        ;

        return $query;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('upload', 'upload');
        $collection->add('browse', 'browse/{type}');
    }
}
