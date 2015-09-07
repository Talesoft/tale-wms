<?php

namespace Tale\Wms\Model;

trait TimestampTrait {

    public $creationTime = 'timestamp default(currentTimestamp)';
    public $lastEditTime = 'timestamp default(onUpdateCurrentTimestamp)';
    public $creatorPersonId = 'fk optional';
    public $editorPersonId = 'fk optional';
}