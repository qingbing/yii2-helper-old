<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\operateLog\controllers;


use Exception;
use YiiHelper\abstracts\RestController;
use YiiHelper\features\operateLog\services\interfaces\IOperateLogService;
use YiiHelper\features\operateLog\services\OperateLogService;
use YiiHelper\models\operateLog\OperateLog;
use YiiHelper\models\operateLog\OperateLogs;
use Zf\Helper\Traits\Models\TLabelYesNo;

/**
 * 控制器 : 操作日志
 *
 * Class OperateLogController
 * @package YiiHelper\features\operateLog\controllers
 *
 * @property-read IOperateLogService $service
 */
class OperateLogController extends RestController
{
    public $serviceInterface = IOperateLogService::class;
    public $serviceClass     = OperateLogService::class;

    /**
     * 操作日志列表
     *
     * @return array
     * @throws Exception
     */
    public function actionList()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            ['id', 'string', 'label' => '日志ID'],
            ['trace_id', 'string', 'label' => 'Trace ID'],
            ['type', 'string', 'label' => '日志类型'],
            ['keyword', 'string', 'label' => '关键字'],
            ['system_alias', 'string', 'label' => '系统别名'],
            ['message', 'string', 'label' => '消息'],
            ['uid', 'string', 'label' => '用户ID'],
            ['nickname', 'string', 'label' => '用户昵称'],

            ['start_at', 'datetime', 'label' => '开始时间', 'format' => 'php:Y-m-d H:i:s'],
            ['end_at', 'datetime', 'label' => '结束时间', 'format' => 'php:Y-m-d H:i:s'],
        ], null, true);

        // 业务处理
        $res = $this->service->list($params);
        // 渲染结果
        return $this->success($res, '操作日志列表');
    }

    /**
     * 操作日志详情
     *
     * @return array
     * @throws Exception
     */
    public function actionView()
    {
        // 参数验证和获取
        $params = $this->validateParams([
            [['id'], 'required'],
            ['id', 'exist', 'label' => '日志ID', 'targetClass' => OperateLogs::class, 'targetAttribute' => 'id'],
        ]);

        // 业务处理
        $res = $this->service->view($params);
        // 渲染结果
        return $this->success($res, '操作日志详情');
    }
}