<?php

namespace Bifrost\Rules;

use Bifrost\Repositories\EntityRepository;

abstract class AttributeDontExistsRule extends Rule
{
    /**
     * @inheritDoc
     */
    public function passes($attribute, $value)
    {
        return blank($this->repository->findOneBy([$attribute => $value]));
    }

    /**
     * @inheritDoc
     */
    public function message()
    {
        return ucfirst(':attribute') . ' \':value\' already exists.';
    }
}