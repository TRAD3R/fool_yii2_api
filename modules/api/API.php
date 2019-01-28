<?php

namespace app\modules\api;

use yii\base\Module;

class API extends Module
{
    public function init()
    {
        parent::init();

        $this->modules = [
            'v1' => [
                'class' => 'app\modules\api\v1\ApiV1',
            ],
        ];
    }
}