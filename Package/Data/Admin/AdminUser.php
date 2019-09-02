<?php

namespace Package\Data\Admin;

use Package\Database\Mysql\MysqlModel;

/**
 * Class AdminUser
 * @package Package\Data\Admin
 *
 * @property integer $id
 * @property string  $email
 * @property string  $mobile
 * @property string  $pass_word
 * @property string  $name
 * @property string  $real_name
 * @property string  $remark
 * @property integer $status
 * @property integer $principal_sex
 * @property integer $login_count
 */
class AdminUser extends MysqlModel
{

}