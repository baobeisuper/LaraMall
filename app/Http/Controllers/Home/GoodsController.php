<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Repositories\ActivityRepository;
use App\Repositories\CargoRepository;
use App\Repositories\CategoryAttributeRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CommentsRepository;
use App\Repositories\GoodsRepository;
use App\Repositories\RelGoodsActivityRepository;
use App\Repositories\RelGoodsLabelRepository;
use App\Repositories\RelLabelCargoRepository;
use App\Repositories\UserInfoRepository;
use App\Repositories\GoodsCollectionRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GoodsController extends Controller
{
    /**
     * 货品
     *
     * @var CargoRepository
     * @author zhulinjie
     */
    protected $cargo;

    /**
     * 商品
     *
     * @var
     * @author zhulinjie
     */
    protected $goods;

    /**
     * 分类
     *
     * @var
     * @author zhulinjie
     */
    protected $category;

    /**
     * 商品规格
     *
     * @var
     * @author zhulinjie
     */
    protected $relGoodsLabel;

    /**
     * 分类属性
     *
     * @var CategoryAttributeRepository
     * @author zhulinjie
     */
    protected $categoryAttr;

    /**
     * 分类标签值与货品关联表
     *
     * @var
     * @author zhulinjie
     */
    protected $relLabelCargo;

    /**
     * 活动操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $activity;

    /**
     * 商品活动关联操作类
     *
     * @var
     * @author zhulinjie
     */
    protected $relGoodsActivity;
    /**
     * @var CommentsRepository
     */
    protected $comment;
    /**
     * @var UserInfoRepository
     */
    protected $userInfo;

    /**
     * 货品收藏操作类
     *
     * @var
     * @author jiaohuafeng
     */
    protected $goodsCollection;
    /**
     * GoodsController constructor.
     * @param CargoRepository $cargoRepository
     * @param CategoryRepository $categoryRepository
     * @param GoodsRepository $goodsRepository
     * @param RelGoodsLabelRepository $relGoodsLabelRepository
     * @param CategoryAttributeRepository $categoryAttributeRepository
     * @param RelLabelCargoRepository $relLabelCargoRepository
     * @param ActivityRepository $activityRepository
     * @param RelGoodsActivityRepository $relGoodsActivityRepository
     * @param CommentsRepository $commentsRepository
     * @param UserInfoRepository $userInfoRepository
     */
    public function __construct
    (
        CargoRepository $cargoRepository,
        CategoryRepository $categoryRepository,
        GoodsRepository $goodsRepository,
        RelGoodsLabelRepository $relGoodsLabelRepository,
        CategoryAttributeRepository $categoryAttributeRepository,
        RelLabelCargoRepository $relLabelCargoRepository,
        ActivityRepository $activityRepository,
        RelGoodsActivityRepository $relGoodsActivityRepository,
        CommentsRepository $commentsRepository,
        UserInfoRepository $userInfoRepository,
        GoodsCollectionRepository $goodsCollectionRepository
    )
    {
        // 注入货品操作类
        $this->cargo = $cargoRepository;
        // 注入分类操作类
        $this->category = $categoryRepository;
        // 注入商品操作类
        $this->goods = $goodsRepository;
        // 注入商品规格操作类
        $this->relGoodsLabel = $relGoodsLabelRepository;
        // 注入分类属性值操作类
        $this->categoryAttr = $categoryAttributeRepository;
        // 分类标签值与货品关联表
        $this->relLabelCargo = $relLabelCargoRepository;
        // 注入活动操作类
        $this->activity = $activityRepository;
        // 商品活动操作类
        $this->relGoodsActivity = $relGoodsActivityRepository;
        // 评论
        $this->comment = $commentsRepository;
        // 用户详情
        $this->userInfo = $userInfoRepository;
        // 货品收藏操作类
        $this->goodsCollection = $goodsCollectionRepository;
    }

    /**
     * 商品列表页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function goodsList(Request $request, $category_id)
    {

        $req = $request->all();

        // 获取标签搜索条件
        if (isset($req['ev']) && !empty($req['ev'])) {
            $ev = explode('%', $req['ev']);
            foreach ($ev as $v) {
                $vs = explode('_', $v);
                $arr[$vs[0]] = $vs[1];
            }
            $data['ev'] = $arr;
        } else {
            $data['ev'] = [];
        }

        if (!empty($data['ev'])) {
            // 当前页
            $page = isset($req['page']) ? $req['page'] : 1;
            // 拼装查询条件
            $where = [];
            foreach ($data['ev'] as $k => $v) {
                $where['category_attr_ids->' . $k] = $v;
            }
            // 手动创建分页
            $cargoIds = $this->relLabelCargo->lists($where, ['cargo_id'])->toArray();
            $cargos = $this->cargo->selectWhereIn('id', $cargoIds);

            $cargos = new LengthAwarePaginator($cargos->forPage($page, PAGENUM), count($cargos), PAGENUM);
            $cargos->setPath('' . $category_id);
        } else {
            // 获取货品列表
            $cargos = $this->cargo->paging(['category_id' => $category_id], PAGENUM);
        }

        // 获取分类标签信息
        $labelInfo = $this->category->find(['id' => $category_id])->labels;

        // 分类标签ID/名称配对
        $labels = $labelInfo->pluck('category_label_name', 'id')->toArray();

        // 分类标签值ID/名称配对
        $lids = $labelInfo->pluck('id')->toArray();
        $attrs = $this->categoryAttr->selectByWhereIn('category_label_id', $lids)->pluck('attribute_name', 'id')->toArray();

        $data['category_id'] = $category_id;

        $data['cargos'] = $cargos;

        $data['labelInfo'] = $labelInfo;
        $data['labels'] = $labels;
        $data['attrs'] = $attrs;

        //每种货品的收藏数量


        return view('home.goods.list', compact('data'));
    }

    /**
     * 商品详情页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function goodsDetail($cargo_id)
    {
        // 获取分类信息
        $category = $this->category->select();

        // 获取货品信息
        $cargo = $this->cargo->find(['id' => $cargo_id]);

        // 获取正在进行的活动
        $currentTimestamp = time();
        $activity = $this->activity->ongoingActivities($currentTimestamp);
        if ($activity) {
            $activity->cargoActivity = $this->relGoodsActivity->find(['cargo_id' => $cargo->id, 'activity_id' => $activity->id]);
        }

        // 先判断当前商品拥有多少种规格
        $standards = $this->relGoodsLabel->select(['goods_id' => $cargo->goods_id], 'goods_label_id')->toArray();

        // 只有一种规格的情况
        $cids = [];
        if (count($standards) == 1) {
            $cids = $this->cargo->lists(['goods_id' => $cargo->goods_id], ['cargo_ids'])->toArray();
        // 多种规格的情况
        } else if (count($standards) > 1) {
            // 获取当前货品规格并转换格式
            $cargo_ids = json_decode($cargo->cargo_ids, 1);
            $tmp = [];
            foreach($cargo_ids as $k => $v){
                $tmp[] = $k.':'.$v;
            }
            $cargo_ids = $tmp;

            /**
             * array:3 [▼
             *     0 => "1:1"
             *     1 => "2:6"
             *     2 => "3:11"
             * ]
             */
//            dd($cargo_ids);

            // 货品规格两两排列组合
            $tmp = [];
            for($i=0; $i<count($cargo_ids); $i++){
                for($j=$i+1; $j<count($cargo_ids); $j++){
                    $tmp[] = $cargo_ids[$i].'@'.$cargo_ids[$j];
                }
            }
            $cargo_ids = $tmp;

            /**
             * array:3 [▼
             *     0 => "1:1@2:6"
             *     1 => "1:1@3:11"
             *     2 => "2:6@3:11"
             * ]
             */
//            dd($cargo_ids);

            // 获取满足所有组合条件的货品规格
            foreach ($cargo_ids as $val) {
                $val = explode('@', $val);
                // 组合查询条件
                $where = [];
                foreach($val as $v){
                    $v = explode(':', $v);
                    $where['cargo_ids->'.$v[0]] = $v[1];
                }
                /**
                 * array:2 [▼
                 *     "cargo_ids->1" => "1"
                 *     "cargo_ids->2" => "6"
                 * ]
                 */
                // 获取满足条件的货品（只取规格字段）
//                $w[] = $where;
//                $cids[] = $this->cargo->lists($where, ['cargo_ids'])->toArray();
                $cids = array_unique(array_merge($cids, $this->cargo->lists($where, ['cargo_ids'])->toArray()));
            }
        }

        /**
         * array:5 [▼
         *     0 => "{"1": "1", "2": "6", "3": "8"}"
         *     1 => "{"1": "1", "2": "6", "3": "9"}"
         *     2 => "{"1": "1", "2": "6", "3": "10"}"
         *     3 => "{"1": "1", "2": "6", "3": "11"}"
         *     4 => "{"1": "1", "2": "7", "3": "11"}"
         * ]
         */
//        dd($cids);

        // 转换格式
        $tmp = [];
        foreach ($cids as $val) {
            foreach (json_decode($val, 1) as $k => $v) {
                $tmp[] = $k . ':' . $v;
            }
        }
        $cids = array_unique($tmp);

        /**
         * array:7 [▼
         *     0 => "1:1"
         *     1 => "2:6"
         *     2 => "3:8"
         *     5 => "3:9"
         *     8 => "3:10"
         *     11 => "3:11"
         *     13 => "2:7"
         * ]
         */
//        dd($cids);

        // 查找家谱树
        $tree = array_reverse($this->tree($category->toArray(), $cargo->category_id));

        // 获取商品标签
        $goods = $this->goods->find(['id' => $cargo->goods_id]);

        $data['category'] = $category;
        $data['cargo'] = $cargo;
        $data['tree'] = $tree;
        $data['goods'] = $goods;
        $data['cids'] = $cids;
        $data['activity'] = $activity;
        // 统计好评
        $data['star']['good'] = $this->comment->count(['cargo_id'=>$cargo_id,'star' => 1]);
        // 统计中评
        $data['star']['almost'] = $this->comment->count(['cargo_id'=>$cargo_id,'star' => 2]);
        // 统计差评
        $data['star']['bad'] = $this->comment->count(['cargo_id'=>$cargo_id,'star' => 3]);
        return view('home.goods.detail', compact('data'));
    }

    /**
     * 立即抢购
     *
     * @param Request $request
     * @author zhulinjie
     */
    public function toSnapUp(Request $request)
    {
        $req = $request->all();

        $cargoActivity = $this->relGoodsActivity->find(['activity_id' => $req['activity_id'], 'cargo_id' => $req['cargo_id']]);

        if (!\Redis::get(STRING_ACTIVITY_CARGO_NUM_ . $req['activity_id'] . $req['cargo_id'])) {
            // 抢购的数量超出用来做活动的商品数量
            if($req['number'] >= $cargoActivity->number){
                \Redis::set(STRING_ACTIVITY_CARGO_NUM_ . $req['activity_id'] . $req['cargo_id'], $cargoActivity->number);
            }else{
                \Redis::set(STRING_ACTIVITY_CARGO_NUM_ . $req['activity_id'] . $req['cargo_id'], $req['number']);
            }
        }

        \Redis::incrBy(STRING_ACTIVITY_CARGO_NUM_ . $req['activity_id'] . $req['cargo_id'], $req['number']);
    }

    /**
     * 获取货品ID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author zhulinjie
     */
    public function getCargoId(Request $request)
    {
        $data = $request->all();
        // 判断货品ID在redis中是否存在
        $cargo_ids = md5(json_encode($data));
        if (\Redis::get(STRING_CARGO_STANDARD_ . $cargo_ids)) {
            return responseMsg(\Redis::get(STRING_CARGO_STANDARD_ . $cargo_ids));
        }

        // 组合查询条件
        $where = [];
        foreach ($data as $k => $v) {
            $where['cargo_ids->' . $k] = $v;
        }

        // 获取货品信息
        $cargo = $this->cargo->find($where);

        // 存入redis
        \Redis::set(STRING_CARGO_STANDARD_ . $cargo_ids, $cargo->id);

        if (!$cargo) {
            return responseMsg('该货品不存在', 404);
        }
        
        return responseMsg($cargo->id);
    }

    /**
     * 购物车
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function shopCart()
    {
        return view('home.goods.shopCart');
    }

    /**
     * 分类
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author zhulinjie
     */
    public function sort()
    {
        return view('home.goods.sort');
    }
    
    /**
     * 查找家谱树
     *
     * @param $arr
     * @param $id
     * @return array
     * @author zhulinjie
     */
    public function tree($arr, $id)
    {
        $tree = array();
        while ($id != 0) {
            foreach ($arr as $v) {
                if ($v['id'] == $id) {
                    $tree[] = $v;

                    $id = $v['pid'];
                    break;
                }
            }
        }
        return $tree;
    }

    public function comments(Request $request)
    {
        // 初始化分页数据
        $page = empty($request['page'])?1:$request['page'];
        // 拼装查询条件
        $where['cargo_id'] = $request['cargo_id'];
        // 增加指定的查询条件
        if(!empty($request['star'])) {
            $where['star'] = $request['star'];
        }
        // 获取评论数据
       $data = $this->comment->commentPaging($where,$page);
        if(!empty($data)) {
            // 便利评论数据
            foreach ($data as $item) {
                // 根据用户ID获取用户信息
                $user = $this->userInfo->find(['user_id' => $item->user_id],['nickname','avatar']);
                // 对用户名进行一次处理
                $user->nickname = substr_replace($user->nickname,'*******',1,5);
                // 重新组装函数
                $item->user = $user;
            }
            // 获取分页总数
            $totalPage =ceil($this->comment->getPage($where));
            // 返回数据
            return responseMsg(['data' => $data,'totalPage' => $totalPage]);
        }
        return responseMsg('',400);

    }
}
