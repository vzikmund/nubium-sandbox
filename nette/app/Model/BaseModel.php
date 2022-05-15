<?php
declare(strict_types=1);

namespace App\Model;


use Nette\Database\Explorer;


abstract class BaseModel
{

    /**
     * @param Explorer $explorer
     */
    public function __construct(protected Explorer $explorer){}

}