<?php

namespace Xali\Bundle\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('email', 'repeated', array(
                    'type' => 'email',
                    'first_options' => array('label' => 'form.email'),
                    'second_options' => array(
                                            'label' => 'form.email_confirmation'
                                        ),
                ))
                
                ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options' => array('label' => 'form.password'),
                'second_options' => array(
                                        'label' => 'form.password_confirmation'
                                    ),
            ))
       
            ->add('firstname', 'text', array('label' => "form.firstname"))
            
            ->add('lastname', 'text', array('label' => "form.lastname"))
        
            ->add('gender', 'choice', array(
                'label' => 'form.gender',
                'choices' => array(
                    'm' => 'form.gender_choices.male',
                    'f' => 'form.gender_choices.female'
                ),
                'multiple' => false,
                'expanded' => false,
                'empty_value' => 'form.choose_value',
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Xali\Bundle\UserBundle\Entity\User'
        ));
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'xali_userbundle_user';
    }
}