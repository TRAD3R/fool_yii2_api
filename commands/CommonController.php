<?php
namespace app\commands;

use yii\console\Controller;

class CommonController extends Controller
{
  protected $status = false;  // return status
  protected $data = '';       // return data
  protected $error = '';    // DB error
}