<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\permission\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\permission\services\interfaces\IRoleService;
use YiiHelper\features\permission\services\RoleService;
use YiiHelper\models\permission\PermissionMenu;
use YiiHelper\models\permission\PermissionRole;
use Zf\Helper\Traits\Models\TLabelEnable;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器 : 角色管理
 *
 * Class RoleController
 * @package YiiHelper\features\permission\controllers
 *
 * @property-read IRoleService $service
 */
class RoleController extends RestController
{
    public $serviceInterface = IRoleService::class;
    public $serviceClass     = RoleService::class;

    /**
     * 角色列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['id', 'exist', 'label' => '角色ID', 'targetClass' => PermissionRole::class, 'targetAttribute' => 'id'],
            ['code', 'exist', 'label' => '角色标识', 'targetClass' => PermissionRole::class, 'targetAttribute' => 'code'],
            ['remark', 'string', 'label' => '路径描述'],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '角色列表');
    }

    /**
     * 添加角色
     *
     * @return array
     * @throws Exception
     */
    public function actionAdd()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['remark'], 'required'],
            ['remark', 'unique', 'label' => '角色描述', 'targetClass' => PermissionRole::class, 'targetAttribute' => 'remark'],
            ['code', 'unique', 'label' => '角色代码', 'targetClass' => PermissionRole::class, 'targetAttribute' => 'code'],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
        ], null);

        // 业务处理
        $res = $this->service->add($params);
        // 渲染结果
        return $this->success($res, '添加角色');
    }

    /**
     * 编辑角色
     *
     * @return array
     * @throws Exception
     */
    public function actionEdit()
    {
        // 参数验证和获取
        $id     = $this->getParam('id');
        $params = $this->validateParams([
            [['id', 'remark'], 'required'],
            ['id', 'exist', 'label' => '角色ID', 'targetClass' => PermissionRole::class, 'targetAttribute' => 'id'],
            ['remark', 'unique', 'label' => '角色描述', 'targetClass' => PermissionRole::class, 'targetAttribute' => 'remark', 'filter' => ['!=', 'id', $id]],
            ['is_enable', 'in', 'label' => '启用状态', 'range' => array_keys(TLabelEnable::enableLabels())],
        ], null);

        // 业务处理
        $res = $this->service->edit($params);
        // 渲染结果
        return $this->success($res, '编辑角色');
    }

    /**
     * 删除角色
     *
     * @return array
     * @throws Exception
     */
    public function actionDel()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['id', 'required'],
            ['id', 'exist', 'label' => '角色ID', 'targetClass' => PermissionRole::class, 'targetAttribute' => 'id'],
        ], null);

        // 业务处理
        $res = $this->service->del($params);
        // 渲染结果
        return $this->success($res, '删除角色');
    }

    /**
     * 查看角色详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['id', 'required'],
            ['id', 'exist', 'label' => '角色ID', 'targetClass' => PermissionRole::class, 'targetAttribute' => 'id'],
        ], null);

        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '查看角色详情');
    }

    /**
     * 为角色分配菜单
     *
     * @return array
     * @throws Exception
     */
    public function actionAssignMenu()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id', 'is_enable', 'menu_codes'], 'required'],
            ['is_enable', 'in', 'label' => '是否有效', 'range' => array_keys(TLabelYesNo::isLabels())],
            ['id', 'exist', 'label' => '角色ID', 'targetClass' => PermissionRole::class, 'targetAttribute' => 'id'],
            [
                'menu_codes',
                'each',
                'rule' => [
                    'exist',
                    'message'         => '菜单标记不存在',
                    'targetClass'     => PermissionMenu::class,
                    'targetAttribute' => 'code',
                    'filter'          => ['=', 'is_enable', 1]
                ]
            ]
        ], null, false, ['menu_codes'], ',');

        // 业务处理
        $res = $this->service->assignMenu($params);
        // 渲染结果
        return $this->success($res, '为角色分配菜单');
    }
}