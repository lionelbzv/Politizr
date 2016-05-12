<?php

namespace Politizr\FrontBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints\PasswordStrength;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Politizr\Constant\UserConstants;

/**
 * Citizen inscription form step 1
 *
 * @author Lionel Bouzonville
 */
class PUserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('qualified', 'hidden', array(
            'attr' => array( 'value' => false)
        ));

        $builder->add('p_u_status_id', 'hidden', array(
            'attr'     => array( 'value' => UserConstants::STATUS_ACTIVED )
        ));

        $builder->add('online', 'hidden', array(
            'attr'     => array( 'value' => false )
        ));

        $builder->add('username', 'hidden', array(
            'attr'     => array( 'value' => '' )
        ));

        $builder->add('email', 'email', array(
            'required' => true,
            'label' => 'Email',
            'constraints' => array(
                new NotBlank(array('message' => 'Email obligatoire.')),
                new Email(array('message' => 'Le format de l\'email n\'est pas valide.')),
            ),
            'attr' => array('placeholder' => 'Email')
        ));
        
        $builder->add('plainPassword', 'repeated', array(
            'required' => true,
            'first_options' =>   array(
                'label' => 'Mot de passe',
                'attr' => array('placeholder' => 'Mot de passe')
            ),
            'second_options' =>   array(
                'label' => 'Confirmation',
                'attr' => array('placeholder' => 'Mot de passe')
            ),
            'type' => 'password',
            'constraints' => array(
                new NotBlank(array('message' => 'Mot de passe obligatoire.')),
                new PasswordStrength(
                    array(
                        'message' => 'Le mot de passe doit contenir au moins 8 caractères',
                        'minLength' => 8,
                        'minStrength' => 1
                    )
                ),
            )
        ));

        $builder->add('cgu', 'checkbox', array(
            'required' => true,
            'label' => 'J\'accepte les conditions générales d\'utilisation',
            'mapped' => false
        ));

        // update username same as email field
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (isset($data['email'])) {
                $data['username'] = $data['email'];
            }
            $event->setData($data);
        });
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return 'register';
    }
    
    /**
     *
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Politizr\Model\PUser',
        ));
    }
}
