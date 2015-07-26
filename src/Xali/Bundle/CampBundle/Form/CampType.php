<?php

namespace Xali\Bundle\CampBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CampType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => "form.name"))
            ->add('city', 'hidden', array('label' => " "))
            ->add('country', 'hidden', array('label' => " "))
            ->add('dateOfCreation', 'date', array(
                                            'label' => 'form.date_of_creation',
                                            'widget' => "single_text",
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Xali\Bundle\CampBundle\Entity\Camp'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'xali_bundle_campbundle_camp';
    }
}
