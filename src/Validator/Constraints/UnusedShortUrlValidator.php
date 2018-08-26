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

class UnusedShortUrlValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $entry = $this->entityManager
            ->getRepository(UrlPair::class)
            ->findByShortUrl($value);

        if ($entry)
        {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ url }}', $value)
                ->addViolation();
        }
    }
}