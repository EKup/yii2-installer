<?php
namespace ekup\yii2\installer\configurator\actions;

/**
 * Class CreateStructure
 */
class CreateStructure extends Base
{
    public $param;

    public function run()
    {
        $this->showError("runned!!!  " . $this->param);
        $this->showError("buy ");
    }
}