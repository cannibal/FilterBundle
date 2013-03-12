<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mv
 * Date: 20/02/13
 * Time: 22:09
 * To change this template use File | Settings | File Templates.
 */

namespace Cannibal\Bundle\FilterBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Cannibal\Bundle\FilterBundle\Filter\FilterInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text')
            ->add('comparison', 'choice', array(
                'choices'=>$this->getOperationChoices()
            ))
            ->add('criteria', 'text')
            ->add('isNot', 'checkbox', array('value'=>'true'))
        ;
    }

    public function getOperationChoices()
    {
        return array(
            FilterInterface::EQ=>'=',
            FilterInterface::LIKE=>'Like',
            FilterInterface::ILIKE=>'iLike',
            FilterInterface::LT=>'<',
            FilterInterface::LTE=>'<=',
            FilterInterface::GT=>'>',
            FilterInterface::GTE=>'>='
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>'Cannibal\\Bundle\\FilterBundle\\Filter\\Filter'
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'filter';
    }
}