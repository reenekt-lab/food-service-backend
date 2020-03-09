<?php


namespace Modules\Payments\Support\Account;


interface AccountNumberGenerator
{
    /**
     * Генерирует номер счета
     *
     * @return mixed
     */
    public function generate();
}
