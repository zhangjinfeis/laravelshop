<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * 角色与用户关系模型
 * @author my 2017-10-25
 * Class ManagerRoleUser
 * @package App
 */
class ManagerRoleUser extends Model
{
    protected $table = 'manager_role_user';
    protected $dateFormat = 'U';

}
