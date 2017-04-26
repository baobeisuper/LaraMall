/**
 * 分页 Vue
 * @author Luoyan
 */
new Vue({
    // 绑定元素
    el: '.main-content',
    // 响应式参数
    data() {
        return {
            pagination: {
                total: 0, // 总条数
                from: 1, // 当前页码第一个栏数据是数据库第几条
                to: 0, // 当前页码最后一栏数据是数据库第几条
                current_page: 1 // 当前页
            },
            offset: 4, // 页码偏移量
            datas: [], // 页码内容
            search: {level: 1}, // 搜索条件
            oldSearch: [],// 返回上一页使用
            per_page: 10, // 一页显示的数据
            currentLevel: 1, // 当前分类层级
            category: [], // 修改时显示的单个分类数据
        }
    },
    // 第一次执行
    mounted() {
        // 获取第一页数据
        this.fetchDatas(this.pagination.current_page);

    },
    // 计算属性
    computed: {
        // 选中页
        isActived() {
            return this.pagination.current_page;
        },
        // 页码
        pagesNumber() {
            // 无数据返回空
            if (!this.pagination.to) return [];
            // 获取第一个页码
            var from = this.pagination.current_page - this.offset;
            if (from < 1) from = 1;
            // 最后一个页码
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) to = this.pagination.last_page;
            // 所有页码
            var pages = [];
            for (; from <= to; from++) {
                pages.push(from);
            }
            return pages;
        },
        // 上一页省略号
        preOmit() {
            return (this.pagination.current_page - 1) > this.offset;
        },
        // 下一页省略号
        nextOmit() {
            return (this.pagination.last_page - this.pagination.current_page) > this.offset;
        }

    },
    // 方法定义
    methods: {
        // 获取页码数据
        fetchDatas(page) {
            // 请求数据
            var data = {
                page: page, // 当前页
                where: this.search, // 搜索条件
                perPage: this.per_page, // 页面展示的数据
            };
            // 请求数据
            axios.post('/admin/classificationList', data).then(response => {
                // layer 加载层关闭
                layer.closeAll();
                // 响应式更新数据
                this.datas = response.data.data;
                this.pagination = response.data;
            }).catch(error => {
                // layer 加载层关闭
                layer.closeAll();
                sweetAlert("请求失败!", "用户列表请求失败!", "error");
            });
        },
        // 改变页码
        changePage(page) {
            // 防止重复点击当前页
            if (page == this.pagination.current_page) return;
            // layer 加载层
            layer.load(2);
            // 修正当前页
            this.pagination.current_page = page;
            // 执行修改
            this.fetchDatas(page);
        },
        // 获取层级
        getLevel(level) {
            if (level == 1) {
                return '顶级分类';
            } else if (level == 2) {
                return '二级分类';
            } else if (level == 3) {
                return '三级分类';
            } else {
                return '无';
            }
        },
        // 根据 id 获取分类数据
        fetchCategoryById(id, index) {
            // layer 加载层
            layer.load(2);
            // 请求数据
            axios.get('/admin/classification/' + id).then(response => {
                // 将查询分类数据赋值都变量方便提供给修改模态框
                this.category = response.data;
                // 记录分类列表中的中得索引
                this.category.index = index;
                // layer 加载层关闭
                layer.closeAll();
            }).catch(error => {
                // layer 加载层关闭
                sweetAlert("请求失败!", "分类信息请求失败!", "error");
            });
        },
        // 表单上传
        submit(id, event) {
            // layer 加载层
            layer.load(2);
            // FormData 支援把 Form 元素丟進去
            var formData = new FormData(event.target);
            // 请求数据
            axios.post('/admin/classificationUpdate/' + id, formData).then(response => {
                // 判断修改是否成功
                if (response.data.ServerNo != 200) {
                    return sweetAlert("修改失败!", "分类修改!", "error");
                }
                // layer 加载层关闭
                layer.closeAll();
                // 隐藏模态框
                $('#exampleModal').modal('hide');
                // 更新分类列表中修改后的数据
                this.$set(this.datas, this.category.index, response.data.ResultData);
                // 清空修改表单内容
                this.emptyForm();
            }).catch(error => {
                // layer 加载层关闭
                sweetAlert("请求失败!", "用户列表请求失败!", "error");
            });
        },
        // 查看子类方法
        catChild(data) {
            // 准备返回上一页的搜索条件
            this.oldSearch[this.currentLevel] = this.search;
            // 设置子类层级
            this.currentLevel = this.currentLevel + 1;
            // 设置搜索条件
            this.search = {
                level: this.currentLevel,
                pid: data.id
            };
            // layer 加载层
            layer.load(2);
            // 获取子类列表
            this.fetchDatas(1);
        },
        // 返回上一页
        backTo() {
            // 上一页的条件
            this.currentLevel = this.currentLevel - 1;
            this.search = this.oldSearch[this.currentLevel];
            // layer 加载层
            layer.load(2);
            // 获取子类列表
            this.fetchDatas(1);
        },
        // 清空表单内容
        emptyForm() {
            // 清空 vue 变量
            this.category = [];
            // 清空表单内容
            $('.level, .pid, .recipient-name, .describe, .img').val('');
            // 还原默认图标
            $('.img_img').attr({'src': 'https://dn-phphub.qbox.me/uploads/images/201704/11/4430/U0ctyGJUV7.png'});
        },
        // 给添加子分类表单中的隐藏域设置值
        childSet(id, level) {
            // 设置隐藏域中得 pid 与 level
            this.category = {
                pid: id,
                level: level + 1
            };
        },
        // 添加子分类请求操作
        createChild() {
            // layer 加载层
            layer.load(2);
            // FormData 支援把 Form 元素丟進去
            var formData = new FormData(event.target);
            // 请求数据
            axios.post('/admin/classificationCreate', formData).then(response => {
                // 判断修改是否成功
                if (response.data.ServerNo != 200) {
                    return sweetAlert("添加失败!", "添加子分类失败!", "error");
                }
                // layer 加载层关闭
                layer.closeAll();
                // 隐藏模态框
                $('#addModal').modal('hide');
                // 清空修改表单内容
                this.emptyForm();
            }).catch(error => {
                // layer 加载层关闭
                sweetAlert("请求失败!", "用户列表请求失败!", "error");
            });
        }
    }
});

// 获取图标编码
function getObjectURL(file) {
    var url = null;
    if (window.createObjectURL != undefined) {
        url = window.createObjectURL(file);
    } else if (window.URL != undefined) {
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) {
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}

// 表单立即显示图标
$('.img').on('change', function () {
    // 获取控件中得文件
    var files = $(this).prop('files')[0];
    // 获取当前 id
    var id = $(this).prop('id');
    // 获取图标编码
    var url = getObjectURL(files);
    // 立即显示图片
    $('.' + id + '_img').attr({'src': url});
});