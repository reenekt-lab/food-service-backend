<?php


namespace Modules\Payments\Support\Account;


use Illuminate\Support\Str;

class UUIDAccountNumberGenerator implements AccountNumberGenerator
{
    /**
     * @inheritDoc
     */
    public function generate()
    {
        return Str::uuid();
    }
}
