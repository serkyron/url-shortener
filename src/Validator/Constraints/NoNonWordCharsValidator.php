<?php
/**
 * Created by PhpStorm.
 * User: serkyron
 * Date: 26.08.18
 * Time: 20:01
 */

namespace App\Validator\Constraints;

use App\Entity\UrlPair;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NoNonWordCharsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (preg_match('/\W/', $value) === 1)
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}