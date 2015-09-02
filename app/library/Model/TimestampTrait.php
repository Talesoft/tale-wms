<?php

namespace Tale\Wms\Model;

trait TimestampTrait {

    public $creationTime = 'timestamp default(currentTimestamp)';
    public $modificationTime = 'timestamp default(onUpdateCurrentTimestamp)';
}