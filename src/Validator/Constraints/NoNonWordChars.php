<?php
/**
 * Created by PhpStorm.
 * User: serkyron
 * Date: 26.08.18
 * Time: 19:56
 */

namespace App\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NoNonWordChars extends Constraint
{
    public $message = 'Only a-z A-Z 0-9 characters are allowed.';
}