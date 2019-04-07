<?php

namespace App\Utils;

use Symfony\Component\Validator\ConstraintViolationListInterface;


class JsonFormatter {

    public static function getErrorMessages(ConstraintViolationListInterface $violations) {
        $errors = array();
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()][] = $violation->getMessage();
        }

        return $errors;
    }
}
