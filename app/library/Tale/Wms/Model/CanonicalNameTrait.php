<?php

namespace Tale\Wms\Model;

use Tale\Util\StringUtil;

trait CanonicalNameTrait
{
    use NameTrait;

    public $canonicalName = 'string(64) required unique';

    public function initCanonicalNameEvents() {

        $ensureCanonicalName = function ($e) {

            if (!empty($this->canonicalName))
                return;

            if (empty($this->name)) {
                $e->preventDefault();
                throw new \Exception(
                    "Failed to save model: No name found to generate a "
                    ."canonical name."
                );
            }

            $this->setCanonicalNameFromName();
        };

        $this->bind('beforeSave', $ensureCanonicalName )
             ->bind('beforeCreate', $ensureCanonicalName);
    }

    public function setCanonicalNameFromName()
    {

        $name = null;
        $i = 0;
        do {

            $addOn = ++$i >= 2 ? "-{$i}" : '';
            $name = StringUtil::canonicalize($this->name).$addOn;
        } while($this->getTable()->where(['canonicalName' => $name])->count() > 0);

        $this->canonicalName = $name;

        return $name;
    }
}