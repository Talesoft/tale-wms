<?php

namespace Tale\Wms\Model;

use Tale\App\ModelBase;
use Tale\Wms\Model\TimestampTrait;

class StorageProduct extends ModelBase
{
    use TimestampTrait;

    public $id = 'id';
    public $storageId = 'fk';
    public $productId = 'fk';
    public $amount = 'int(11) default(0)';
    public $requiredAmount = 'int(11) default(0)';
}