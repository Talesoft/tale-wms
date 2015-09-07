<?php

namespace Tale\Wms\Model;

use Tale\App\ModelBase;
use Tale\Wms\Model\TimestampTrait;


class Person extends ModelBase {
    use TimestampTrait;
    use ScanCodeTrait;

    public $firstName = 'string(128)';
    public $lastName = 'string(128) optional';

    public $role = 'enum(admin,reader,user,guest) default(guest)';
    public $loginName = 'string(64) optional';
    public $passwordHash = 'string optional';

    public function setPassword( $password ) {

        $this->passwordHash = password_hash( $password, \PASSWORD_DEFAULT );

        return $this;
    }

    public function isPassword( $password ) {

        return password_verify( $password, $this->passwordHash );
    }
}