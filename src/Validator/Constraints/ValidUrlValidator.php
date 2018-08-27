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
    public function validate($value, Constraint $constraint)
    {
        $validStatusCodes = explode(',', getenv('VALID_RESPONSE_CODES'));

        $options = [
            CURLOPT_URL => $value,
            CURLOPT_RETURNTRANSFER => true
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        $statusCode = curl_getinfo ($ch, CURLINFO_RESPONSE_CODE);

        if (!in_array($statusCode, $validStatusCodes))
        {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ url }}', $value)
                ->addViolation();
        }
    }
}