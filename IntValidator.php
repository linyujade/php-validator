<?php
/**
 * Int类型验证器
 * Author: linyu(linyu03@baidu.com)
 * Date: 16/06/15
 */
class IntValidator extends BaseValidator {

    /**
     * 检查输入是否小于最小数值
     * @param object $value
     * @return bool true/false
     */
    protected function checkMinNumber($value) {

        // 检查是否有最小值约束
        if (isset($this->options['min'])) {

            // 如果有最小值约束，并且输入值是有效的int类型，满足输入值大于等于最小约束值，则验证通过
            return $this->checkInteger($value) && intval($value) >= intval($this->options['min']);
        }

        // 如果没有最小约束值，则验证通过
        return true;
    }

    /**
     * 检查输入是否超出最大数值
     * @param object $value
     * @return bool true/false
     */
    protected function checkMaxNumber($value) {

        // 检查是否有最大值约束
        if (isset($this->options['max'])) {

            // 如果有最大值约束，并且输入值是有效的int类型，满足输入值小于等于最大约束值，则验证通过
            return $this->checkInteger($value) && intval($value) <= intval($this->options['max']);
        }

        // 如果没有最大约束值，则验证通过
        return true;
    }

    /**
     * 验证整数
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

                // 验证是否是有效的int值
                if (!$this->checkInteger($value)) {
                    $this->setError(Validation::ERR_CODE_FIELD_INVALID, $arrParam);
                    return false;
                }

                // 检查输入项的数值是否小于最小值约束
                if (!$this->checkMinNumber($value)) {
                    $arrParam[] = $this->options['min'];
                    $this->setError(Validation::ERR_CODE_OUT_OF_RANGE_MIN, $arrParam);
                    return false;
                }

                // 检查输入项的数值是否大于最大值约束
                if (!$this->checkMaxNumber($value)) {
                    $arrParam[] = $this->options['max'];
                    $this->setError(Validation::ERR_CODE_OUT_OF_RANGE_MAX, $arrParam);
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