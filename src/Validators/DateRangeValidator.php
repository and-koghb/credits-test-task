<?php

namespace App\Validators;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class DateRangeValidator
{
    public static function validateStartDate($startDate, ExecutionContextInterface $context)
    {
        $start = \DateTime::createFromFormat('Y-m-d', $startDate);
        $today = new \DateTime();

        if (!$start || $start->format('Y-m-d') !== $startDate) {
            $context->buildViolation('Invalid date format. Use YYYY-MM-DD.')->addViolation();
            return;
        }

        if ($start < $today) {
            $context->buildViolation('Start date cannot be less than current date.')->addViolation();
            return;
        }

        $oneWeekLater = (clone $today)->modify('+1 week');

        if ($start > $oneWeekLater) {
            $context->buildViolation('Start date cannot be more than 1 week after the current date.')->addViolation();
        }
    }

    public static function validateEndDate($endDate, ExecutionContextInterface $context)
    {
        $form = $context->getObject();
        $startDate = \DateTime::createFromFormat('Y-m-d', $form->startDate ?? '');
        $end = \DateTime::createFromFormat('Y-m-d', $endDate);

        if (!$startDate || !$end || $end->format('Y-m-d') !== $endDate) {
            $context->buildViolation('Invalid date format. Use YYYY-MM-DD.')->addViolation();
            return;
        }

        $minEndDate = (clone $startDate)->modify('+1 month');
        $maxEndDate = (clone $startDate)->modify('+30 years');

        if ($end < $minEndDate) {
            $context->buildViolation('End date must be at least 1 month after the start date.')->addViolation();
        }

        if ($end > $maxEndDate) {
            $context->buildViolation('End date cannot be more than 30 years after the start date.')->addViolation();
        }
    }
}
