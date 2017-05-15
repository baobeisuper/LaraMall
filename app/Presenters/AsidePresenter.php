<?php

namespace App\Presenters;

class AsidePresenter
{
    /**
     * 通过 URL 保持标签打开状态
     *
     * @param array $path
     * @return string
     * @author: Luoyan
     */
    public function openTag(array $path)
    {

        $data = explode('/',\Request::path());
        if(count($data) == 1) {
            $data[1] = 'index';
        }
        if (in_array($data[1], $path)) {
            return 'open active';
        }
    }

    /**
     * 显示标签
     *
     * @param array $path
     * @return string
     * @author: Luoyan
     */
    public function displayBlock(array $path)
    {

        $data = explode('/',\Request::path());
        if(count($data) == 1) {
            $data[1] = 'index';
        }
        if (in_array($data[1], $path) || count($data) == 1) {
            return 'style=display:block;overflow:hidden;';
        }
    }
}