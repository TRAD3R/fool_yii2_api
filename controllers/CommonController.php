<?php
/**
 * Class for common functions and fields
 *
 * Created by PhpStorm.
 * User: TRAD3R
 * Date: 01.06.2018
 * Time: 8:43
 */

namespace app\controllers;

use yii\rest\Controller;
class CommonApiController extends Controller
{
    public $status = false;     // status for Rest
    public $data = [];          // return data
    public $errorDB = '';       // DB error
}