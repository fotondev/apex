<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TrackNameConstraintValidator extends ConstraintValidator
{
    private array $trackNames = ['barcelona', 'brands_hatch', 'cota', 'donington', 'hungaroring', 'imola', 'indianapolis', 'kyalami',
        'laguna_seca', 'misano', 'monza', 'mount_panorama', 'nurburgring', 'oulton_park', 'paul_ricard', 'silverstone', 'snetterton',
        'spa', 'suzuka', 'valencia', 'watkins_glen', 'zandvoort', 'zolder', 'red_bull_ring'];

    public function validate($value, Constraint $constraint):  void
    {
        /* @var TrackNameConstraint $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (!in_array($value, $this->trackNames)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
