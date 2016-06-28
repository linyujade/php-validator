<?php
/**
 * 基本项验证器
 * Author: linyu(linyu03@baidu.com)
 * Date: 16/06/15
 */
class BaseValidator implements IValidator {

    /**
     * 验证的信息标题
     */
    protected $title;

    /**
     * 验证的选项
     */
    protected $options;

    /**
     * 错误信息
     */
    protected $error;

    /**
     * 构造函数
     * @param string $title 验证字段的标题
     * @param array $options 验证字段的选项
     */
    public function __construct($title = '', $options = false) {
        $this->title   = $title;
        $this->options = $options;
    }

    /**
     * 只替换一次字符串的方法
     * @param string $needle 规定要查找的值
     * @param string $replace 替换 $needle 的值
     * @param string $haystack 规定被搜索的字符串
     * @return string 返回替换后的字符串
     */
    public function replaceOnceString($needle, $replace, $haystack) {
        $pos = strpos($haystack, $needle);
        if (false === $pos) {
            return $haystack;
        }
        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    /**
     * 检查是否空字符串, 是否空数组, 是否空值
     * 1. 如果输入项是字符串，则检查字符串的长度是否为0
     * 2. 如果是数组，则检查是否是空数组
     * 3. 如果既不是字符串，也不是数组，则检查输入项是否设置了数值
     * @param object $value
     * @return bool true/false
     */
    public function checkEmpty($value) {
        return (is_string($value) && !strlen(trim($value))) || (is_array($value) && empty($value)) || !isset($value);
    }

    /**
     * 检查是否必填项
     * @param object $value
     * @return bool true/false
     */
    public function isRequired() {
        if (isset($this->options['required'])) {
            $required = $this->options['required'];
            return $this->checkBool($required) && filter_var($required, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }
        return false;
    }

    /**
     * 检查正则表达式匹配，目前只提供字符串、int、float类型的正则验证
     * @param string/int/float $value
     * @return bool true/false
     */
    public function checkRegex($value) {
        if (isset($this->options['pattern'])) {
            return (is_string($value) || $this->checkInteger($value) || $this->checkFloat($value)) && preg_match($this->options['pattern'], $value);
        }
        return true;
    }

    /**
     * 检查是否在指定集合里
     * @param string/int/float $value
     * @return bool true/false
     */
    public function checkValueSet($value) {
        if (!empty($this->options['valueSet'])) {
            $arrValueSet = $this->options['valueSet'];
            return is_array($arrValueSet) && in_array($value, $arrValueSet);
        }
        return true;
    }

    /**
     * 检查是整数
     * @param string/int $value
     * @return bool true/false
     */
    public function checkInteger($value) {
        return is_numeric($value) && (int) $value == $value;
    }

    /**
     * 检查是浮点数
     * @param string/float $value
     * @return bool true/false
     */
    public function checkFloat($value) {
        return is_float(filter_var($value, FILTER_VALIDATE_FLOAT));
    }

    /**
     * 检查是bool类型值
     * @param string/bool $value
     * @return bool true/false
     */
    public function checkBool($value) {
        return is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
    }

    /**
     * 检查是数组类型
     * @param object $value 验证的对象
     * @return bool true/false
     */
    public function checkArray($value) {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    /**
     * 检查字符串或者数组的长度是否符合规则
     * @param object $value
     * @return bool true/false
     */
    public function checkLength($value) {
        if (isset($this->options['length'])) {
            $len     = $this->options['length'];
            $isArray = $this->checkArray($value);
            return ($isArray && $len === sizeof($value)) || (!$isArray && $len === mb_strlen(strval($value), 'UTF-8'));
        }
        return true;
    }

    /**
     * 检查字符串或者数组的长度是否符合最小长度约束的规则
     * @param object $value
     * @return bool true/false
     */
    public function checkMinLength($value) {
        if (isset($this->options['minLength'])) {
            $minlen  = $this->options['minLength'];
            $isArray = $this->checkArray($value);
            return ($isArray && $minlen <= sizeof($value)) || (!$isArray && $minlen <= mb_strlen(strval($value), 'UTF-8'));
        }
        return true;
    }

    /**
     * 检查字符串或者数组的长度是否符合最大长度约束的规则
     * @param object $value
     * @return bool true/false
     */
    public function checkMaxLength($value) {
        if (isset($this->options['maxLength'])) {
            $maxlen  = $this->options['maxLength'];
            $isArray = $this->checkArray($value);
            return ($isArray && $maxlen >= sizeof($value)) || (!$isArray && $maxlen >= mb_strlen(strval($value), 'UTF-8'));
        }
        return true;
    }

    /**
     * 添加必填错误提示信息
     */
    public function setEmptyMsg() {
        if (isset($this->options['emptyMsg'])) {
            $this->error = $this->options['emptyMsg'];
        } else {
            $this->setError(Validation::ERR_CODE_FIELD_EMPTY, $this->title);
        }
    }

    /**
     * 添加错误信息
     * @param int $errCode
     * @param array/string $args 错误信息中需要填充的参数信息
     */
    public function setError($errCode = false, $args = false) {
        if (isset($this->options['errMsg'])) {
            $this->error = $this->options['errMsg'];
        } else if ($errCode) {
            $errMsg = Validation::$errCodeDesc[$errCode];
            if ($args) {
                if (is_array($args)) {

                    // 如果参数是数组的话，循环把数组中的值取出来替换错误编码中对应的参数
                    while (count($args)) {
                        $str    = array_shift($args); 
                        $errMsg = $this->replaceOnceString('?', $str, $errMsg);
                    }
                } else {

                    // 如果参数是字符串，则直接把参数替换掉错误编码中的?
                    $errMsg = str_replace('?', $args, $errMsg);
                }
            }
            $this->error = $errMsg;
        }
    }

    /**
     * 输出错误信息
     * @return string 返回错误信息
     */
    public function getError() {
        return $this->error;
    }

    /**
     * 实现IValidator的接口
     * @param string $value 输入的值
     * @return bool true/false
     */
    public function validate($value) {
        // 检查是否是必填字段
        $required = $this->isRequired();

        /**
         * 1. 如果是必填字段，则验证是否输入值为空，为空则验证不通过
         * 2. 如果不是必填信息，直接返回true
         */
        $result = ($required && !$this->checkEmpty($value) || !$required);
        if (!$result) {
            $this->setEmptyMsg();
        }
        return $result;
    }
}