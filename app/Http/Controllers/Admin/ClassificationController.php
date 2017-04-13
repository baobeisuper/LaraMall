<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class ClassificationController
 * @package App\Http\Controllers\Admin
 */
class ClassificationController extends Controller
{

    /**
     * 文件操作
     *
     * @var \Storage
     */
    protected $disk;

    /**
     * @var CategoryRepository
     */
    protected $category;

    /**
     * 服务注入
     *
     * ClassificationController constructor.
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        // 注入七牛服务
        $this->disk = \Storage::disk('qiniu');
        // 注入分类操作类
        $this->category = $categoryRepository;
    }

    /**
     * 分类列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Luoyan
     */
    public function index()
    {
        // 返回分类列表视图
        return view('admin.classification.index');
    }

    /**
     * 添加分类视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @author: Luoyan
     */
    public function create()
    {
        // 返回创建分类视图
        return view('admin.classification.insert');
    }


    /**
     * 分类信息录入
     *
     * @param Request $request
     * @return $this
     * @author: Luoyan
     */
    public function store(Request $request)
    {
        // 文件处理函数
        $this->fileDo($request);

        // 录入分类信息，并且判断录入结果
        if ($this->category->createByCategory($request->all())) {
            // 录入成功跳转分类列表
            return redirect()->route('classification.index');
        }

        // 录入失败返回上一页，并且附带提交表单值
        return back()->withInput();
    }

    /**
     * 分类列表
     *
     * @param Request $request
     * @return mixed
     * @author: Luoyan
     */
    public function categoryList(Request $request)
    {
        // 获取分页或搜索后的数据
        return $this->category->categoryPaginate($request->get('perPage'), $request->get('where'));
    }

    /**
     * 查询分类数据方法
     *
     * @param $id
     * @return mixed
     * @author: Luoyan
     */
    public function show($id)
    {
        // 查询分类数据
        $category = $this->category->findById($id);
        // 查询是否成功
        if ($category) {
            $category->doma = env('QINIU_DOMAIN');
        }

        return $category;
    }

    /**
     * 修改分类信息
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author: Luoyan
     */
    public function update(Request $request, $id)
    {
        // 文件处理函数
        $this->fileDo($request);
        // 除去请求中得 _token 字段
        $data = $request->except(['_token', 'image']);
        // 修改分类数据, 判断返回结果
        if ($this->category->updateById($id, $data)) {
            // 查询更新后的值
            $data = $this->category->findById($id);

            // 成功返回修改数据
            return responseMsg($data, 200);
        }

        // 修改失败
        return responseMsg('修改失败!', 400);
    }

    /**
     * 文件处理函数
     *
     * @param Request $request
     * @return bool
     * @author: Luoyan
     */
    public function fileDo(Request $request)
    {
        // 判断是否有图标上传，并且检查图片是否合法
        if ($request->hasFile('image') && checkImage($file = $request->file('image'))) {
            // 上传七牛文件云存储后返回图片名字
            $imageName = $this->disk->put(IMAGE_PATH, $file);
            // 将图片名字塞入请求之中
            $request->merge(['img' => $imageName]);

            return true;
        }

        return false;
    }
}