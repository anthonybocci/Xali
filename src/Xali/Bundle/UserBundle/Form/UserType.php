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
                    'invalid_message' => 'fos_user.email.mismatch',
                ))
                
                ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array(
                                        'label' => 'form.password_confirmation'
                                    ),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
       
            ->add('firstname', 'text', array('label' => "form.firstname"))
            
            ->add('lastname', 'text', array('label' => "form.lastname"));
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