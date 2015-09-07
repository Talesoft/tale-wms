<?php

namespace Tale\Wms\Model;

use Tale\App\ModelBase;
use Tale\Wms\Model\TimestampTrait;


class Storage extends ModelBase {
    use TimestampTrait;
    use CanonicalNameTrait;
    use ScanCodeTrait;

    public $allowIn = 'bool default(1)';
    public $allowOut = 'bool default(1)';
    public $startTime = 'datetime optional';
    public $endTime = 'datetime optional';
}