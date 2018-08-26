<?php
/**
 * Created by PhpStorm.
 * User: serkyron
 * Date: 26.08.18
 * Time: 20:01
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidUrlValidator extends ConstraintValidator
{
    private $validStatusCodes = [200, 301, 500];

    public function validate($value, Constraint $constraint)
    {
        $options = [
            CURLOPT_URL => $value,
            CURLOPT_RETURNTRANSFER => 1
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        $statusCode = curl_getinfo ($ch, CURLINFO_RESPONSE_CODE);

        if (!in_array($statusCode, $this->validStatusCodes))
        {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ url }}', $value)
                ->addViolation();
        }
    }
}