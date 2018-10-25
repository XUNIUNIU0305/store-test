<?php
/**
 * time => [
 *     `minute (0-59)`,
 *     `hour (0-23)`,
 *     `day of month (1-31)`,
 *     `month (1-12)`,
 *     `day of week (0-6)`,
 *     //'*' == ALL
 *     //multi value e.g.: '1,2,3'
 * ],
 *
 * options => [
 *     `option_name1` => `option_value1`,
 *     `option_name2` => `option_value2`,
 *     ...
 * ],
 *
 * params => [
 *     `first_param`,
 *     `second_param`,
 *     ...
 * ],
 */
return [
    //自动取消订单
    'order/cancel-order' => [
        'time' => ['*', '*', '*', '*', '*'],
    ],
    //自动确认订单
    'order/confirm-order' => [
        'time' => ['*', '*', '*', '*', '*'],
    ],
    //自动关闭订单
    'order/close-order' => [
        'time' => ['*', '*', '*', '*', '*'],
    ],

    //优惠券更新过期时间
    'coupon/update-expire' => [
        'time' => ['*', '*', '*', '*', '*'],
    ],

    //消息队列处理
    'crontab/amqp' => [
        'time' => ['*', '*', '*', '*', '*'],
    ],

    //门店数量
    'business/count-custom-quantity' => [
        'time' => ['15', '0', '*', '*', '*'],
    ],
    //每日统计
    'business/record-day' => [
        'time' => ['30', '0', '*', '*', '*'],
    ],
    //每周统计
    'business/record-week' => [
        'time' => ['0', '1', '*', '*', '1'],
    ],
    //每月统计
    'business/record-month' => [
        'time' => ['0', '2', '1', '*', '*'],
    ],

    //邀请账号生效
    'partner/make-account-valid' => [
        'time' => ['*', '*', '*', '*', '*'],
    ],

    //Business提现自动审核
    'nanjing/auto-validate' => [
        'time' => ['*', '*', '*', '*', '*'],
    ],

    //网关支付结算状态查询
    'nanjing/query-deposit-status' => [
        'time' => ['0', '*', '*', '*', '*'],
    ],

    //换货单确认收货
    'order-refund/finish-refund' => [
        'time' => ['*', '*', '*', '*', '*'],
    ],

    //非交易出入账
    'non-transaction-deposit-and-draw/execute' => [
        'time' => ['*', '*', '*', '*', '*'],
    ],

    //消费统计
    'consumption-statistics/index' => [
        'time' => ['30', '*', '*', '*', '*'],
    ],

    //拼购团取消
    'gpubs/cancel-expire-group' => [
        'time' => ['*', '*', '*', '*', '*'],
    ],

    //南京银行余额预警
    'nanjing/balance-warning' => [
        'time' => ['0', '9', '*', '*', '*'],
    ],
];
