<?php

namespace App\Form;

use App\Entity\TilePack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TilePackType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add("name", TextType::class, [
                "required" => true,
            ])
            ->add("slug", TextType::class, [
                    "required" => true,
            ])
            ->add("icon", TextType::class, [
                    "required" => false,
            ])
            ->add("active", CheckboxType::class, [
                    "required" => true,
            ])
            ->add("save", SubmitType::class);
    }

    public function configureOption(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => TilePack::class,
        ]);
    }
}