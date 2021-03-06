<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\tableHeader\controllers;


use YiiHelper\abstracts\RestController;
use YiiHelper\features\tableHeader\services\HeaderService;
use YiiHelper\features\tableHeader\services\interfaces\IHeaderService;
use YiiHelper\models\tableHeader\Header;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器 ： 表头选项管理
 *
 * Class HeaderController
 * @package YiiHelper\features\tableHeader\controllers
 *
 * @property-read IHeaderService $service
 */
class HeaderController extends RestController
{
    public $serviceClass     = HeaderService::class;
    public $serviceInterface = IHeaderService::class;

    /**
     * 表头搜索列表
     *
     * @return array
     * @throws \Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['key', 'string', 'label' => '表头标记'],
            ['name', 'string', 'label' => '表头别名'],
            ['is_open', 'in', 'range' => array_keys(TLabelYesNo::isLabels()), 'label' => '是否公开'],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '表头列表');
    }

    /**
     * 添加表头
     *
     * @return array
     * @throws \Exception
     */
    public function actionAdd()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'name', 'sort_order', 'is_open'], 'required'],
            ['key', 'unique', 'label' => '表头标记', 'targetClass' => Header::class, 'targetAttribute' => 'key'],
            ['name', 'unique', 'label' => '表头别名', 'targetClass' => Header::class, 'targetAttribute' => 'name'],
            ['description', 'string', 'label' => '表头描述'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_open', 'in', 'range' => array_keys(TLabelYesNo::isLabels()), 'label' => '是否公开'],
        ]);

        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加表头成功');
    }

    /**
     * 编辑表头
     *
     * @return array
     * @throws \Exception
     */
    public function actionEdit()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key', 'name', 'sort_order', 'is_open'], 'required'],
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => Header::class, 'targetAttribute' => 'key'],
            [
                'name', 'unique', 'label' => '表头别名',
                'targetClass'             => Header::class,
                'targetAttribute'         => 'name',
                'filter'                  => ['!=', 'key', $this->getParam('key')]
            ],
            ['description', 'string', 'label' => '表头描述'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_open', 'in', 'range' => array_keys(TLabelYesNo::isLabels()), 'label' => '是否公开'],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑表头成功');
    }

    /**
     * 删除表头
     *
     * @return array
     * @throws \Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => Header::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除表头成功');
    }

    /**
     * 查看表头详情
     *
     * @return array
     * @throws \Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['key'], 'required'],
            ['key', 'exist', 'label' => '表头标记', 'targetClass' => Header::class, 'targetAttribute' => 'key'],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '表头详情');
    }
}