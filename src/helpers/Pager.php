<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace YiiHelper\helpers;


use yii\db\ActiveQuery;
use Zf\Helper\Abstracts\Factory;

/**
 * 数据分页获取
 *
 * Class Pager
 * @package YiiHelper\helpers
 */
class Pager extends Factory
{
    /**
     * @var int 数据总条数
     */
    private $_totalCount;
    /**
     * @var bool 返回模型是否为数组
     */
    private $_asArray = false;

    /**
     * 设置总条数，用于特殊SQL外部计算数据总量
     *
     * @param int $count
     * @return $this
     */
    public function setTotalCount(int $count)
    {
        $this->_totalCount = $count;
        return $this;
    }

    /**
     * 设置返回数据是否为数组形式，设置为数组返回，否则为对象（模型）返回
     *
     * @param bool $asArray
     * @return $this
     */
    public function setAsArray(bool $asArray = true)
    {
        $this->_asArray = $asArray;
        return $this;
    }

    /**
     * 数据分页查询
     *
     * @param ActiveQuery $query
     * @param int $pageNo
     * @param int $pageSize
     * @return array
     */
    public function pagination(ActiveQuery $query, $pageNo = 1, $pageSize = 10)
    {
        if (null !== $this->_totalCount) {
            $totalCount = $this->_totalCount;
        } else {
            // 自动获取数据总条数
            $queryCount = clone $query;
            $queryCount->select('COUNT(*) as count')
                ->limit(1)
                ->orderBy('');

            $re         = $queryCount->asArray()->one();
            $totalCount = $re['count'];
        }
        if ($totalCount > 0) {
            // 强制参数校验
            $pageNo   = $pageNo > 1 ? $pageNo : 1;
            $pageSize = $pageSize > 1 ? $pageSize : 10;
            // 数据查询
            $query->limit($pageSize);
            $query->offset($pageNo < 1 ? 0 : ($pageNo - 1) * $pageSize);
            $data = $this->_asArray ? $query->asArray()->all() : $query->all();
        } else {
            $data = [];
        }
        return [
            'total' => $totalCount > 0 ? $totalCount : 0,
            'data'  => $data,
        ];
    }
}