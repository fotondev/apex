<?php

namespace App\Validator;

use App\Entity\Track;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MaxCarSlotValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint): void
    {
        /* @var MaxCarSlot $constraint */

        if (null === $value || '' === $value) {
            return;
        }
        $form = $this->context->getRoot();
        $settings = $form->getConfig()->getOptions()['settings'];


        if ($value > $settings->getMaxCarSlots()) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ limit }}', $settings->getMaxCarSlots())
                ->addViolation();
        }

    }
}
