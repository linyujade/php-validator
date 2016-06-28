<?php
/**
 * string类型验证器
 * Author: linyu(linyu03@baidu.com)
 * Date: 16/06/15
 */
class StringValidator extends BaseValidator {

    /**
     * 验证字符串
     * @param string $value
     * @return bool true/false
     */
    public function validate($value) {

        // 调用基类的验证方法，如果验证通过，则继续验证其他信息
        if (parent::validate($value)) {

            // 如果输入不为空的话，再去验证其他项
            if (!$this->checkEmpty($value)) {
                $arrParam   = array();
                $arrParam[] = $this->title;
                
                // 检查是否符合固定长度要求
                if (!$this->checkLength($value)) {
                    $arrParam[] = $this->options['length'];
                    $this->setError(Validation::ERR_CODE_FIELD_LENGTH, $arrParam);
                    return false;
                }
                
                // 检查是否符合最小长度约束
                if (!$this->checkMinLength($value)) {
                    $arrParam[] = $this->options['minLength'];
                    $this->setError(Validation::ERR_CODE_FIELD_MIN_LENGTH, $arrParam);
                    return false;
                }
                
                // 检查是否符合最大长度约束
                if (!$this->checkMaxLength($value)) {
                    $arrParam[] = $this->options['maxLength'];
                    $this->setError(Validation::ERR_CODE_FIELD_MAX_LENGTH, $arrParam);
                    return false;
                }

                // 检查是否有指定正则匹配，并符合指定的正则表达式
                if (!$this->checkRegex($value)) {
                    $this->setError(Validation::ERR_CODE_FIELD_INVALID, $arrParam);
                    return false;
                }
                
                // 检查是否有指定的值集，如果有，是否在指定值集里
                if (!$this->checkValueSet($value)) {
                    $arrParam[] = join(',', $this->options['valueSet']);
                    $this->setError(Validation::ERR_CODE_NOT_IN_VALUESET, $arrParam);
                    return false;
                }
            }

            // 如果所有的验证选项都验证通过，则返回true
            return true;
        }

        // 如果基类验证方法不通过，直接返回false
        return false;
    }
}