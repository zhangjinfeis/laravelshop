<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 链接模型
 * @author my 2017-10-25
 * Class ManagerMenu
 * @package App
 * https://packagist.org/packages/baum/baum
 */
class Guestbook extends Model
{
    protected $table = 'guestbook';
    protected $guarded = [];
    protected $dateFormat = 'U';

}
