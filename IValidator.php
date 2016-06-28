<?php
/**
 * 定义验证器接口
 * Author: linyu(linyu03@baidu.com)
 * Date: 16/06/15
 */
interface IValidator {

    /**
     * 设置错误信息
     */
    public function setError();

    /**
     * 获取错误信息
     */
    public function getError();

    /**
     * 验证输入
     * @param object $value 验证需要输入的参数
     * @return bool true/false
     */
    public function validate($value);
}