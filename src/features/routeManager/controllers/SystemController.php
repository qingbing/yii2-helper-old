<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeManager\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\routeManager\services\interfaces\ISystemService;
use YiiHelper\features\routeManager\services\SystemService;
use YiiHelper\models\routeManager\RouteSystems;
use YiiHelper\validators\JsonValidator;
use Zf\Helper\Traits\Models\TLabelEnable;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 服务 ： 系统管理
 *
 * Class SystemController
 * @package YiiHelper\features\routeManager\controllers
 *
 * @property-read ISystemService $service
 */
class SystemController extends RestController
{
    public $serviceInterface = ISystemService::class;
    public $serviceClass     = SystemService::class;

    /**
     * 系统列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['code', 'string', 'label' => '系统别名'],
            ['name', 'string', 'label' => '系统名称'],
            ['type', 'in', 'label' => '系统类型', 'range' => array_keys(RouteSystems::types())],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
            ['is_allow_new_interface', 'in', 'label' => '接受新接口', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_record_field', 'in', 'label' => '记录新字段', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_open_validate', 'in', 'label' => '开启接口校验', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_strict_validate', 'in', 'label' => '开启严格校验', 'range' => array_keys(TLabelYesNo::isLabels())],
        ], null, true);
        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '系统列表');
    }

    /**
     * 添加系统
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['code', 'name', 'type'], 'required'],
            ['code', 'unique', 'label' => '系统别名', 'targetClass' => RouteSystems::class, 'targetAttribute' => 'code'],
            ['name', 'unique', 'label' => '系统名称', 'targetClass' => RouteSystems::class, 'targetAttribute' => 'name'],
            ['type', 'in', 'label' => '系统类型', 'range' => array_keys(RouteSystems::types())],
            ['proxy', 'string', 'label' => '代理组件ID'],
            ['uri_prefix', 'string', 'label' => 'URI前缀'],
            ['description', 'string', 'label' => '系统描述'],
            ['ext', JsonValidator::class, 'label' => '扩展字段'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
            ['is_allow_new_interface', 'in', 'label' => '接受新接口', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_record_field', 'in', 'label' => '记录新字段', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_open_validate', 'in', 'label' => '开启接口校验', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_strict_validate', 'in', 'label' => '开启严格校验', 'range' => array_keys(TLabelYesNo::isLabels())],
        ]);
        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加系统成功');
    }

    /**
     * 编辑系统
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 参数获取
        $id = $this->getParam('id');
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'], // 必填字段减少，为了表格编辑
            ['id', 'exist', 'label' => '系统ID', 'targetClass' => RouteSystems::class, 'targetAttribute' => 'id'],
            ['name', 'unique', 'label' => '系统名称', 'targetClass' => RouteSystems::class, 'targetAttribute' => 'name', 'filter' => ['!=', 'id', $id]],
            ['uri_prefix', 'string', 'label' => 'URI前缀'],
            ['description', 'string', 'label' => '系统描述'],
            ['ext', JsonValidator::class, 'label' => '扩展字段'],
            ['sort_order', 'integer', 'label' => '排序'],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
            ['is_allow_new_interface', 'in', 'label' => '接受新接口', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_record_field', 'in', 'label' => '记录新字段', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_open_validate', 'in', 'label' => '开启接口校验', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['is_strict_validate', 'in', 'label' => '开启严格校验', 'range' => array_keys(TLabelYesNo::isLabels())],
        ]);
        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑系统成功');
    }

    /**
     * 删除系统
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => '系统ID', 'targetClass' => RouteSystems::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除系统成功');
    }

    /**
     * 查看系统详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => '系统ID', 'targetClass' => RouteSystems::class, 'targetAttribute' => 'id'],
        ]);
        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '系统详情');
    }
}