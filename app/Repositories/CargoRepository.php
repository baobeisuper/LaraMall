<?php
/**
 * Created by PhpStorm.
 * User: zhulinjie
 * Date: 2017/4/21
 * Time: 16:05
 */

namespace App\Repositories;

use App\Model\Cargo;
use App\Model\IndexGoods;
use App\Tools\Analysis;

/**
 * Class CargoRepository
 * @package App\Repositories
 */
class CargoRepository
{

    use BaseRepository;

    /**
     * @var Cargo
     * @author zhulinjie
     */
    protected $model;

    /**
     * CargoRepository constructor.
     * @param Cargo $cargo
     */
    public function __construct(Cargo $cargo, Analysis $analysis)
    {
        $this->model = $cargo;
    }

    /**
     * 通过whereIn获取多条数据
     * 
     * @param $fields
     * @param array $ids
     * @param array $where
     * @return mixed
     * @author zhulinjie
     */
    public function selectWhereIn($fields, array $ids, array $where = []){
        return $this->model->where($where)->whereIn($fields, $ids)->get();
    }

    /**
     * 通过where获取多条数据
     *
     * @param $fields
     * @param array $ids
     * @param array $where
     * @return mixed
     * @author jiaohuafeng
     */
    public function selectWhere(array $where = []){
        return $this->model->where($where)->get();
    }

    /**
     * 通过货品ID获取跟货品相关联的货品关联表的数据
     *
     * @param $fields
     * @param array $ids
     * @param array $where
     * @return mixed
     * @author jiaohuafeng
     */
    public function getCargoCollection(array $where = []){
        return $this->model->find($where)->goodscollection();
    }

}