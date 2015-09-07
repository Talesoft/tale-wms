<?php

namespace Tale\Wms\Model;

use Tale\App\ModelBase;
use Tale\Wms\Model\TimestampTrait;


class Product extends ModelBase {
    use TimestampTrait;
    use CanonicalNameTrait;
    use ScanCodeTrait;

    public $description = 'string optional';
    public $price = 'double optional';
}