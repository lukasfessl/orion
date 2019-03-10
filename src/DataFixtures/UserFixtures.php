<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        die;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager) {
        die;
        $user = new User();
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'test'));
        $manager->flush();
    }
}
