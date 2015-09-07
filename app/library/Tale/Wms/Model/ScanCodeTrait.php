<?php

namespace Tale\Wms\Model;

trait ScanCodeTrait
{

    public $scanCode = 'string(128) required unique';

    public function initScanCodeEvents() {

        $ensureScanCode = function () {

            if (!empty($this->scanCode))
                return;

            $this->generateScanCode();
        };

        $this->bind('beforeSave', $ensureScanCode )
             ->bind('beforeCreate', $ensureScanCode);
    }

    public function getScanCodePrefix()
    {

        return strtoupper(str_replace(['a', 'e', 'i', 'o', 'u'], '', basename(__CLASS__)));
    }

    public function generateScanCode()
    {

        $scanCode = null;
        do {

            $scanCode = $this->getScanCodePrefix().'-'.rand(100,999).'-'.rand(100,999);
        } while($this->getTable()->where(['scanCode' => $scanCode])->count() > 0);

        $this->scanCode = $scanCode;

        return $scanCode;
    }
}