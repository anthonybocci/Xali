<?php

namespace Xali\Bundle\SurvivorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SurvivorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text', array('label' => "form.firstname"))
            
            ->add('lastname', 'text', array('label' => "form.lastname"))
            
            ->add('birthday', 'date', array('label' => "form.birthday"))
            
            ->add('eyesColor', 'choice', array(
                'choice' => array(
                    'brown'  => 'form.eyescolor.brown',
                    'green'   => 'form.eyescolor.green',
                    'blue' => 'form.eyescolor.blue',
                    'grey'    => 'form.eyescolor.grey',
                    'black'   => 'form.eyescolor.black',
                    'other'  => 'form.eyescolor.other',
                ),
            'multiple' => false,
            'expanded' => false
            ))
            
            ->add('weight', 'number', array('label' => "form.weight"))
            
            ->add('height', 'number', array('label' => "form.height"))
            
            ->add('hairColor', 'choice', array(
                'choice' => array(
                    'blond'  => 'form.haircolor.blond',
                    'blue'   => 'form.haircolor.blue',
                    'brown'  => 'form.haircolor.brown',
                    'pink'   => 'form.haircolor.pink',
                    'purple' => 'form.haircolor.purple',
                    'red'    => 'form.haircolor.red',
                    'other'  => 'form.haircolor.other',
                ),
            'multiple' => false,
            'expanded' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Xali\Bundle\SurvivorBundle\Entity\Survivor'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'xali_bundle_survivorbundle_survivor';
    }
}
