<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\features\form\services;


use YiiHelper\abstracts\Service;
use YiiHelper\features\form\services\interfaces\IFormOptionService;
use YiiHelper\helpers\AppHelper;
use YiiHelper\models\form\FormCategory;
use YiiHelper\models\form\FormOption;
use Zf\Helper\Exceptions\BusinessException;

/**
 * 服务 ： 表单选项管理
 *
 * Class FormOptionService
 * @package YiiHelper\features\from\services
 */
class FormOptionService extends Service implements IFormOptionService
{
    /**
     * 表单选项列表
     *
     * @param array|null $params
     * @return array
     */
    public function list(array $params = []): array
    {
        $category = FormCategory::findOne([
            'key' => $params['key'],
        ]);
        return $category->options;
    }

    /**
     * 添加表单选项
     *
     * @param array $params
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function add(array $params): bool
    {
        $model = \Yii::createObject(FormOption::class);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 编辑表单选项
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \yii\db\Exception
     */
    public function edit(array $params): bool
    {
        $model = $this->getModel($params);
        unset($params['id'], $params['key']);
        $model->setFilterAttributes($params);
        return $model->saveOrException();
    }

    /**
     * 删除表单选项
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function del(array $params): bool
    {
        $model = $this->getModel($params);
        return $model->delete();
    }

    /**
     * 查看表单选项详情
     *
     * @param array $params
     * @return mixed|FormOption
     * @throws BusinessException
     */
    public function view(array $params)
    {
        return $this->getModel($params);
    }

    /**
     * 刷新表单中所有选项排序
     *
     * @param array $params
     * @return bool
     * @throws \Throwable
     */
    public function refreshOrder(array $params): bool
    {
        $category = FormCategory::findOne([
            'key' => $params['key'],
        ]);
        $options  = $category->options;
        AppHelper::app()->getDb()->transaction(function () use ($options) {
            $sort_order = 1;
            foreach ($options as $option) {
                $option->sort_order = $sort_order++;
                $option->saveOrException();
            }
        });
        return true;
    }

    /**
     * 上移选项顺序
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \Throwable
     */
    public function orderUp(array $params): bool
    {
        // 当前模型
        $model = $this->getModel($params);
        // 获取上一顺序模型
        $prevModel = $this->getBrother($model, PREV);
        // 交换 sort_order
        return $this->switchSortOrder($model, $prevModel);
    }

    /**
     * 下移选项顺序
     *
     * @param array $params
     * @return bool
     * @throws BusinessException
     * @throws \Throwable
     */
    public function orderDown(array $params): bool
    {
        // 当前模型
        $model = $this->getModel($params);
        // 获取上一顺序模型
        $nextModel = $this->getBrother($model, NEXT);
        // 交换 sort_order
        return $this->switchSortOrder($model, $nextModel);
    }

    /**
     * 交换两个模型的 sort_order
     *
     * @param FormOption $sourceModel
     * @param FormOption $targetModel
     * @return bool
     * @throws \Throwable
     */
    protected function switchSortOrder(FormOption $sourceModel, ?FormOption $targetModel)
    {
        AppHelper::app()->getDb()->transaction(function () use ($sourceModel, $targetModel) {
            if (null === $targetModel) {
                return;
            }
            $sort_order              = $sourceModel->sort_order;
            $sourceModel->sort_order = $targetModel->sort_order;
            $sourceModel->saveOrException();

            $targetModel->sort_order = $sort_order;
            $targetModel->saveOrException();
        });
        return true;
    }

    /**
     * 获取当前操作模型
     *
     * @param array $params
     * @return FormOption
     * @throws BusinessException
     */
    protected function getModel(array $params): FormOption
    {
        $model = FormOption::findOne([
            'id' => $params['id'] ?? null
        ]);
        if (null === $model) {
            throw new BusinessException("表单选项不存在");
        }
        return $model;
    }

    /**
     * 获取对调的兄弟模型
     *
     * @param FormOption $model
     * @param string $type
     * @return array|FormOption|null
     */
    protected function getBrother(FormOption $model, string $type)
    {
        $differs = [
            PREV => ['oper' => '<', 'sort' => 'sort_order DESC'],
            NEXT => ['oper' => '>', 'sort' => 'sort_order ASC'],
        ];
        return FormOption::find()
            ->andWhere(['=', 'key', $model->key])
            ->andWhere([$differs[$type]['oper'], 'sort_order', $model->sort_order])
            ->orderBy($differs[$type]['sort'])
            ->one();
    }
}