<?php

namespace Package\Validator;

use Exception;

class Validator
{
    static $template = [
        'name'    => ['char' => '名称', 'rule' => self::STRING_RULE],
        'address' => ['char' => '地址', 'rule' => self::STRING_RULE],
        'age'     => ['char' => '年龄', 'rule' => self::INTEGER_RULE, 'required' => true],
        'mobile'  => ['char' => '电话', 'rule' => '@^[0-9]{4}-[0-9]{7}@'],
        'phone'   => ['char' => '手机', 'rule' => '@^[0-9]{11}$@']
    ];

    const STRING_RULE  = '@^[^\s]{1,255}$@';

    const INTEGER_RULE = '@^[0-9]{0,8}$@';

    /**
     * @param $name
     * @param null $items
     * @throws Exception
     */
    static function match($name, $items = null)
    {
        $items = $items ?? $_REQUEST;
        if ($items[$name] || !self::$template[$name]['required'])
        {
            preg_match(self::$template[$name]['rule'], $items[$name], $match);
            if (count($match) === 1) return;
        }
        $message = sprintf('%s 字段类型异常', self::$template[$name]['char']);
        throw new Exception(json_encode([$name => $message], JSON_UNESCAPED_UNICODE));
    }
}