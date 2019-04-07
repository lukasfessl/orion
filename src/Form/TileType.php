<?php

namespace App\Form;


use App\Entity\Tile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TileType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $bookmarks = $options['data']['bookmarks'];

        $builder
            ->add("id", HiddenType::class, [
                    "required" => false,
            ])
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
            ->add("bookmark", ChoiceType::class, [
                "choices" => $this->getBookmarkOptions($bookmarks),
            ])
            ->add("save", SubmitType::class);
    }

    private function getBookmarkOptions($bookmarks) {
        $bookmarkOptions = [];
        foreach ($bookmarks as $bookmark) {
            $bookmarkOptions[$bookmark->getName()] = $bookmark->getId();
        }

        return $bookmarkOptions;
    }

    public function configureOption(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Tile::class,
        ]);
    }
}