<?php

/*pour gerer les complaints envoyé par le user */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('subject', TextType::class)
            ->add('message', TextareaType::class)
            ->add('send', SubmitType::class, [
                'label' => 'Send Message',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ]);
    }
}


?>
