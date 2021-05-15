<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\traits;


use function Webmozart\Assert\Tests\StaticAnalysis\false;
use yii\base\InvalidConfigException;
use YiiHelper\helpers\DynamicModel;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 数据验证片段
 *
 * Trait TValidator
 * @package YiiHelper\traits
 */
trait TValidator
{
    /**
     * 将参数放入验证规则进行规则验证，并返回规则字段的值
     *
     * @param array $rules
     * @param array|null $data
     * @param bool $withPageRule 是否含有分页参数
     * @return array|bool
     * @throws BusinessException
     * @throws InvalidConfigException
     */
    protected function validateParams($rules = [], ?array $data = null, $withPageRule = false)
    {
        // 数据获取
        $request = \Yii::$app->getRequest();
        if (null === $data) {
            $data = array_merge($request->getQueryParams(), $request->getBodyParams());
        }
        if (empty($rules)) {
            return [];
        }
        if ($withPageRule) {
            // 统一规范分页信息
            $rules = array_merge($rules, $this->pageRules());
        }
        // 验证并返回数据
        $model = DynamicModel::validateData($data, $rules);
        if ($model->hasErrors()) {
            // 验证失败
            $error = $model->getErrorSummary(false);
            throw new BusinessException(reset($error), 10000);
        }
        $values = $model->values;
        if ($withPageRule) {
            // 统一规范分页信息
            $values['pageNo']   = $values['pageNo'] ?: 1;
            $values['pageSize'] = $values['pageSize'] ?: 10;
        }
        return $values;
    }

    /**
     * 通过规则验证数据，返回是否通过
     *
     * @param array $data
     * @param array $rules
     * @return bool
     * @throws BusinessException
     * @throws InvalidConfigException
     */
    protected function validate(array $data, $rules = [])
    {
        if (empty($rules)) {
            return true;
        }
        $model = DynamicModel::validateData($data, $rules);
        if ($model->hasErrors()) {
            // 验证失败
            $error = $model->getErrorSummary(false);
            throw new BusinessException(reset($error), 10000);
        }
        return true;
    }
}