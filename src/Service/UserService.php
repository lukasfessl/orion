<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService {

    /** @var UserRepository */
    private $userRepository;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder) {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    public function createUser(User $user, $password) {
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $this->userRepository->save($user);
    }
}