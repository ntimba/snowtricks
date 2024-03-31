<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                "attr" => [
                    "placeholder" => "Nom d'utilisateur",
                    "class" => "form__widget"
                ],
                "label" => "Nom d'utilisateur"
            ])
            ->add('email', EmailType::class, [
                "attr" => [
                    "placeholder" => "Adresse E-mail",
                    "class" => "form__widget"
                ],
                "label" => "Adresse E-mail"
            ])
            ->add('password', PasswordType::class, [
                "attr" => [
                    "placeholder" => "Mot de passe",
                    "class" => "form__widget"
                ],
                "label" => "Mot de passe"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
