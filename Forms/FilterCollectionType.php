<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mv
 * Date: 20/02/13
 * Time: 22:06
 * To change this template use File | Settings | File Templates.
 */

namespace Cannibal\Bundle\FilterBundle\Forms;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Cannibal\Bundle\FilterBundle\Forms\FilterType;

class FilterCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('filters', 'collection', array(
            'empty_data'=>new ArrayCollection(),
            'by_reference'=>false,
            'allow_add'=>true,
            'allow_delete'=>true,
            'type'=>new FilterType()
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>'Cannibal\\Bundle\\FilterBundle\\Filter\\FilterCollection'
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'filter_collection';
    }
}