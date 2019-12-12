<?php

namespace app\Models;
class OrderOrder extends BaseModel
{
    protected $table = "lj_orderorder";

    /**
     * @param int $type 1最小的订单 2大订单
     * @param array $ids [1,2] 包含的小订单id
     * @param int $big_ids 大订单ID
     */
    public function setOrderMsg($type = 1, $ids = [], $big_ids = 0)
    {
        if (empty($ids)) {
            return false;
        }
        if ($type == 1) { //最小的订单
            foreach ($ids as $k => $v) {
                $addData = [];
                $addData['orderid'] = $v;
                $addData['norderid'] = $v;
                $this->MAdd($addData);
            }
        } elseif ($type == 2) { //大订单
            foreach ($ids as $k => $v) {
                $_wh = [];
                $_wh['orderid'] = $v;
                $_wh['del'] = 0;
                $field = 'norderid,id';
                $tempfield = $this->MFind($_wh, '', $field);
                if(!empty($tempfield)){
                    $_whs = [];
                    $_whs['id'] = $tempfield['id'];
                    $_update['norderid'] = $tempfield['norderid'] . ','.$big_ids;
                    $this->MUpdate($_whs,$_update);
                }
            }
        }
        return true;
    }
}