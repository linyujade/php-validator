<?php
/**
 * 验证类型常量、验证错误信息
 * Author: linyu(linyu03@baidu.com)
 * Date: 16/06/15
 */
class Validation {

    /**
     * 有效的字符串类型
     */
    const TYPE_STRING = 1;

    /**
     * 有效的int类型
     */
    const TYPE_INT    = 2;

    /**
     * 有效的float类型
     */
    const TYPE_FLOAT  = 3;

    /**
     * 有效的bool类型
     */
    const TYPE_BOOL   = 4;

    /**
     * 有效的数组类型
     */
    const TYPE_ARRAY  = 5;

    /**
     * 验证类型数组，每个类型对应使用的验证器
     */
    public static $arrClassNameByType = array(
        self::TYPE_STRING => 'StringValidator',
        self::TYPE_INT    => 'IntValidator',
        self::TYPE_FLOAT  => 'FloatValidator',
        self::TYPE_BOOL   => 'BoolValidator',
        self::TYPE_ARRAY  => 'ArrayValidator',
    );

    /**
     * 字段填写为空的错误编码
     */
    const ERR_CODE_FIELD_EMPTY             = 1;

    /**
     * 字段填写无效的错误编码
     */
    const ERR_CODE_FIELD_INVALID           = 2;

    /**
     * 值不在指定值集内的错误编码
     */  
    const ERR_CODE_NOT_IN_VALUESET         = 3;

    /**
     * 数值大小小于指定值的错误编码
     */
    const ERR_CODE_OUT_OF_RANGE_MIN        = 4;

    /**
     * 数值大小大于指定值的错误编码
     */
    const ERR_CODE_OUT_OF_RANGE_MAX        = 5;

    /**
     * 字段长度的错误编码
     */
    const ERR_CODE_FIELD_LENGTH            = 6;

    /**
     * 字符串最小长度错误的错误编码
     */
    const ERR_CODE_FIELD_MIN_LENGTH        = 7;

    /**
     * 字符串最大长度错误的错误编码
     */
    const ERR_CODE_FIELD_MAX_LENGTH        = 8;

    /**
     * 无效的数组类型的错误编码
     */
    const ERR_CODE_ARRAY_INVALID           = 501;

    /**
     * 数组长度错误的错误编码
     */
    const ERR_CODE_ARRAY_LENGTH_WRONG      = 502;

    /**
     * 数组长度不能小于?的错误编码
     */
    const ERR_CODE_ARRAY_MIN_LENGTH_WRONG  = 503;

    /**
     * 数组长度不能大于?的错误编码
     */
    const ERR_CODE_ARRAY_MAX_LENGTH_WRONG  = 504;

    /**
     * 数组没有包含指定键值的错误编码
     */
    const ERR_CODE_ARRAY_NOT_CONTAIN_KEY   = 505;

    /**
     * 验证错误信息描述
     */
    public static $errCodeDesc = array(
        self::ERR_CODE_FIELD_EMPTY               => '[?]为必填项，不能为空',
        self::ERR_CODE_FIELD_INVALID             => '[?]填写格式无效，请重新填写',
        self::ERR_CODE_NOT_IN_VALUESET           => '[?]填写的值必须在指定的值集内([?])',
        self::ERR_CODE_OUT_OF_RANGE_MIN          => '[?]填写的数值大小不能小于[?]',
        self::ERR_CODE_OUT_OF_RANGE_MAX          => '[?]填写的数值大小不能大于[?]',
        self::ERR_CODE_FIELD_LENGTH              => '[?]填写的字符串长度必须是[?]个字符',
        self::ERR_CODE_FIELD_MIN_LENGTH          => '[?]的字符串长度不能小于[?]个字符',
        self::ERR_CODE_FIELD_MAX_LENGTH          => '[?]的字符串长度不能大于[?]个字符',
        self::ERR_CODE_ARRAY_INVALID             => '[?]为无效数组',
        self::ERR_CODE_ARRAY_LENGTH_WRONG        => '[?]数组长度必须为[?]',
        self::ERR_CODE_ARRAY_MIN_LENGTH_WRONG    => '[?]数组长度不能小于[?]',
        self::ERR_CODE_ARRAY_MAX_LENGTH_WRONG    => '[?]数组长度不能大于[?]',
        self::ERR_CODE_ARRAY_NOT_CONTAIN_KEY     => '[?]数组没有包含指定键值[?]',
    );
}