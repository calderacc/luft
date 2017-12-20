<?php

namespace AppBundle\Admin;

use AppBundle\Entity\User;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CityAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('City', ['class' => 'col-xs-6'])
            ->add('name')
            ->add('slug')
            ->add('description', TextareaType::class, ['required' => false])
            ->end()

            ->with('twitter', ['class' => 'col-xs-6'])
            ->add('twitterUsername', TextType::class, ['required' => false])
            ->add('twitterToken', TextType::class, ['required' => false])
            ->add('twitterSecret', TextType::class, ['required' => false])
            ->end()

            ->with('User', ['class' => 'col-xs-6'])
            ->add('user', EntityType::class,
            [
                'class' => User::class,
            ])
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('createdAt')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $actions = [];

        if ($this->isRoleGranted('ROLE_TWITTER_MANAGEMENT')) {
            $actions['twitter'] = [];
        }

        if ($this->isRoleGranted('ROLE_CITY_MANAGEMENT')) {
            $actions['edit'] = [];
        }

        $listMapper
            ->addIdentifier('name')
            ->add('createdAt')
            ->add('user')
            ->add('twitterUsername', 'string', [
                'template' => 'SonataAdminBundle:CRUD:list__twitter_username.html.twig'
            ])
        ;

        if (count($actions)) {
            $listMapper->add('_action', null, [
                'actions' => $actions
            ]);
        }
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('twitter', $this->getRouterIdParameter().'/twitter')
            ->add('twitter_token', $this->getRouterIdParameter().'/twitter_token')
        ;
    }
}
