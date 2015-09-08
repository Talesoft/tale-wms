<?php

namespace Tale\Wms\Model;

use Tale\App\ModelBase;
use Tale\Wms\Model\TimestampTrait;

class Transfer extends ModelBase
{
    use TimestampTrait;

    public $id = 'id';
    public $sourceStorageId = 'fk';
    public $sourcePersonId = 'fk optional';
    public $targetStorageId = 'fk';
    public $targetPersonId = 'fk optional';
    public $productId = 'fk';
}