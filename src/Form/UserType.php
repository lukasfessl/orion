<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add("email", EmailType::class, [
                "required" => true,
            ])
            ->add("password", PasswordType::class, [
                "required" => true,
                'mapped' => false,
                'constraints' => [
                        new NotBlank(),
                        new Callback([$this, 'validate'])
                ]
            ])
            ->add("passwordAgain", PasswordType::class, [
                "required" => true,
                'mapped' => false,
                'constraints' => [
                        new NotBlank(),
                ]
            ])
            ->add("create", SubmitType::class);
    }

    public function validate($value, ExecutionContextInterface $context) {
        if (empty($value)) {
            return;
        }
        $form = $context->getRoot();
        if ($form["passwordAgain"]->getData() != $value) {
            $context->buildViolation('Password does not match')->addViolation();
        }
    }

    public function configureOption(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}