<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 文章扩展字段
 * @author my 2017-10-25
 * Class ArticleExattr
 * @package App
 */
class ArticleExattr extends Model
{
    protected $table = 'article_exattr';
    protected $guarded = [];
    protected $dateFormat = 'U';


}
