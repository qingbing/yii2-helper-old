<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\routeManager\services;

use Exception;
use YiiHelper\abstracts\Service;
use YiiHelper\features\routeManager\services\interfaces\ISystemService;
use YiiHelper\helpers\Instance;
use YiiHelper\helpers\Pager;
use YiiHelper\models\routeManager\RouteSystems;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务 ： 系统管理
 *
 * Class SystemService
 * @package YiiHelper\features\routeManager\services
 */
class SystemService extends Service implements ISystemService
{
    /**
     * 系统列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $query = RouteSystems::find()
            ->orderBy('sort_order ASC');
        // 等于查询
        $this->attributeWhere($query, $params, [
            'code',
            'type',
            'is_enable',
            'is_allow_new_interface',
            'is_record_field',
            'is_open_validate',
            'is_strict_validate'
        ]);
        // like 查询
        $this->likeWhere($query, $params, 'name');
        return Pager::getInstance()->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 添加系统
     *
     * @param array $params
     * @return bool
     * @throws Exception
     */
    public function add(array $params): bool
    {
        $model = new RouteSystems();
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑系统
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
     * 删除系统
     *
     * @param array $params
     * @return bool
     * @throws \Throwable
     * @throws Exception
     */
    public function del(array $params): bool
    {
        $model = $this->getModel($params);
        return $model->delete();
    }

    /**
     * 查看系统详情
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
     * 获取当前操作模型
     *
     * @param array $params
     * @return RouteSystems
     * @throws BusinessException
     */
    protected function getModel(array $params): RouteSystems
    {
        $model = RouteSystems::findOne([
            'id' => $params['id'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("系统不存在");
        }
        return $model;
    }
}