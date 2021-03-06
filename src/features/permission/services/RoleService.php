<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\permission\services;


use Exception;
use YiiHelper\abstracts\Service;
use YiiHelper\features\permission\services\interfaces\IRoleService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\permission\PermissionMenu;
use YiiHelper\models\permission\PermissionRole;
use YiiHelper\models\permission\PermissionRoleMenu;
use Zf\Helper\Exceptions\BusinessException;
use Zf\Helper\Util;

/**
 * 服务 : 角色管理
 *
 * Class RoleService
 * @package YiiHelper\features\permission\services
 */
class RoleService extends Service implements IRoleService
{
    /**
     * 角色列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = PermissionRole::find()
            ->orderBy('id ASC');
        // 等于查询
        $this->attributeWhere($query, $params, [
            'id',
            'code',
            'is_enable',
        ]);
        // like 查询
        $this->likeWhere($query, $params, ['remark']);
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加角色
     *
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function add(array $params): bool
    {
        $model = new PermissionRole();
        $model->setFilterAttributes($params);
        if (!isset($params['code']) || empty($params['code'])) {
            $model->code = Util::uniqid();
        }
        return $model->saveOrException();
    }

    /**
     * 编辑角色
     *
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['id']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除角色
     *
     * @param array $params
     * @return bool
     * @throws \Throwable
     * @throws Exception
     */
    public function del(array $params): bool
    {
        return $this->getModel($params)->delete();
    }

    /**
     * 查看角色详情
     *
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 为角色分配菜单
     *
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function assignMenu(array $params = []): bool
    {
        $model = $this->getModel($params);
        if ($params['is_enable']) {

            foreach ($params['menu_codes'] as $menu_code) {
                $dbData   = [
                    'role_code' => $model->code,
                    'menu_code' => $menu_code,
                ];
                $viaModel = PermissionRoleMenu::findOne($dbData);
                $viaModel = $viaModel ?: new PermissionRoleMenu();
                $viaModel->setAttributes($dbData);
                $viaModel->saveOrException();
            }
            return true;
        } else {
            return PermissionRoleMenu::deleteAll([
                'role_code' => $model->code,
                'menu_code' => $params['menu_codes'],
            ]);
        }
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return PermissionRole
     * @throws Exception
     */
    protected function getModel(array $params): PermissionRole
    {
        $model = PermissionRole::findOne([
            'id' => $params['id'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("角色不存在");
        }
        return $model;
    }
}