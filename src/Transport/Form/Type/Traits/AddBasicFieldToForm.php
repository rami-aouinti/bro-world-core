<?php

declare(strict_types=1);

namespace Bro\WorldCoreBundle\Transport\Form\Type\Traits;

use Symfony\Component\Form\FormBuilderInterface;

use function call_user_func_array;

/**
 * @package Bro\WorldCoreBundle
 */
trait AddBasicFieldToForm
{
    /**
     * @param array<int, array<int, mixed>> $fields
     */
    protected function addBasicFieldToForm(FormBuilderInterface $builder, array $fields): void
    {
        foreach ($fields as $params) {
            call_user_func_array($builder->add(...), $params);
        }
    }
}
