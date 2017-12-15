<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TwitterScheduleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Schedule', ['class' => 'col-md-6'])
            ->add('title')
            ->add('cron')
            ->end()

            ->with('Target', ['class' => 'col-md-6'])
            ->add('city')
            ->end()

            ->with('Source', ['class' => 'col-md-6'])
            ->add('station')
            ->add('latitude')
            ->add('longitude')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('cron')
            ->add('city')
            ->add('station')
            ->add('latitude')
            ->add('longitude')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('cron')
            ->add('city')
            ->add('station')
            ->add('latitude')
            ->add('longitude')
        ;
    }
}
