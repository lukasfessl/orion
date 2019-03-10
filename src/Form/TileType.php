<?php

namespace App\Form;


use App\Entity\Tile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TileType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add("link", TextType::class, [
                "required" => true,
            ])
            ->add("name", TextType::class, [
                "required" => true,
            ])
            ->add("icon", TextType::class, [
                "required" => false,
            ])
            ->add("newWindow", CheckboxType::class, [
                "required" => false,
            ])
            ->add("save", SubmitType::class);
    }

    public function configureOption(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Tile::class,
        ]);
    }
}