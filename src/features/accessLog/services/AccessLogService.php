<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\accessLog\services;


use YiiHelper\abstracts\Service;
use YiiHelper\features\accessLog\services\interfaces\IAccessLogService;
use YiiHelper\helpers\Pager;
use YiiHelper\models\accessLog\AccessLogs;
use YiiHelper\models\routeManager\RouteSystems;

/**
 * 服务 ： 接口访问日志
 *
 * Class AccessLogService
 * @package YiiHelper\features\accessLog\services
 */
class AccessLogService extends Service implements IAccessLogService
{
    /**
     * 接口访问日志列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        // 构建查询query
        $query = AccessLogs::find()
            ->alias("logs")
            ->leftJoin(RouteSystems::tableName() . ' AS system', 'system.code=logs.system_code')
            ->select(['logs.*', 'system.name'])
            ->andFilterWhere(['=', 'system.code', $params['system_code']])
            ->andFilterWhere(['=', 'logs.trace_id', $params['trace_id']])
            ->andFilterWhere(['=', 'logs.method', $params['method']])
            ->andFilterWhere(['=', 'logs.is_success', $params['is_success']])
            ->andFilterWhere(['=', 'logs.ip', $params['ip']])
            ->andFilterWhere(['=', 'logs.uid', $params['uid']])
            ->andFilterWhere(['like', 'logs.url_path', $params['url_path']])
            ->andFilterWhere(['like', 'logs.message', $params['message']])
            ->andFilterWhere(['>=', 'logs.created_at', $params['start_at']])
            ->andFilterWhere(['<=', 'logs.created_at', $params['end_at']]);
        // 分页查询返回
        return Pager::getInstance()
            ->setAsArray(true)
            ->pagination($query, $params['pageNo'], $params['pageSize']);
    }

    /**
     * 查看接口访问日志详情
     *
     * @param array $params
     * @return mixed
     */
    public function view(array $params)
    {
        // 构建查询query
        $query = AccessLogs::find()
            ->alias("logs")
            ->select(['logs.*'])
            ->andWhere(['=', 'logs.id', $params['id']]);
        return $query->one()->toArray([], ['system']);
    }
}