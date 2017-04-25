<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Recommend
 * @package App\Model
 */
class Recommend extends Model
{

    use SoftDeletes;

    /**
     * @var string
     * @author Luoyan
     */
    protected $table = 'data_recommend';

    /**
     * @var array
     */
    protected $fillable = ['recommend_name', 'recommend_location', 'recommend_type', 'recommend_introduction'];

    /**
     * @var array
     * @author Luoyan
     */
    protected $dates = ['deleted_at'];

    /**
     * 多对多关联关系 / 一个推荐位对应多个货品
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author zhulinjie
     */
    public function cargos()
    {
        return $this->belongsToMany(Cargo::class, 'rel_recommend_goods', 'recommend_id', 'cargo_id')->withTimestamps();
    }
}
