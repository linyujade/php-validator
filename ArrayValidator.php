<?php
/**
 * Array类型验证器
 * Author: linyu(linyu03@baidu.com)
 * Date: 16/06/15
 */
class ArrayValidator extends BaseValidator {

    /**
     * 检查key是否在对应数组里
     * @param object $value
     * @return bool true/false
     */
    public function checkContainKey($value) {
        if (isset($this->options['containKey'])) {

            /**
             * 如果规则选项中存在containKey
             * 1. 判断输入项是否是数组，如果不是数组，则表示验证直接不通过
             * 2. 判断数组里是否包括containKey指定的值
             */
            return $this->checkArray($value) && in_array($this->options['containKey'], $value);
        }
        return true;
    }

    /**
     * 检查一组key是否在对应数组里
     * @param object $value
     * @return bool true/false
     */
    public function checkContainKeys($value) {
        if (isset($this->options['containKeys'])) {

            /**
             * 如果规则选项中存在containKeys
             * 1. 判断输入项是否为数组，如果不是数组，则验证不通过
             * 2. 如果输入项是数组，containKeys的值不是数组，则验证也不通过
             * 3. 如果都是数组，则循环containKeys的值，判断是不是每个key都在输入项里
             * 4. 如果输入项里不包含其中任何一个key，则验证不通过
             */
            $keys = $this->options['containKeys'];
            if (!is_array($value) || !is_array($keys)) {
                return false;
            }
            foreach ($keys as $key) {
                if (!in_array($key, $value)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 验证数组
     * @param object $value 传入的对象
     * @return bool true/false
     */
    public function validate($value) {

        // 调用基类的验证方法，如果验证通过，则继续验证其他信息
        if (parent::validate($value)) {

            // 如果输入不为空的话，再去验证其他项
            if (!$this->checkEmpty($value)) {
                $arrParam   = array();
                $arrParam[] = $this->title;

                // 验证是否是数组，如果不是，则设置错误信息，返回false
                if (!$this->checkArray($value)) {
                    $this->setError(Validation::ERR_CODE_ARRAY_INVALID, $arrParam);
                    return false;
                }

                // 验证数组是否有固定长度限制
                if (!$this->checkLength($value)) {
                    $arrParam[] = $this->options['length'];
                    $this->setError(Validation::ERR_CODE_ARRAY_LENGTH_WRONG, $arrParam);
                    return false;
                }

                // 验证数组是否有最小长度限制
                if (!$this->checkMinLength($value)) {
                    $arrParam[] = $this->options['minLength'];
                    $this->setError(Validation::ERR_CODE_ARRAY_MIN_LENGTH_WRONG, $arrParam);
                    return false;
                }

                // 验证数组是否有最大长度限制
                if (!$this->checkMaxLength($value)) {
                    $arrParam[] = $this->options['maxLength'];
                    $this->setError(Validation::ERR_CODE_ARRAY_MAX_LENGTH_WRONG, $arrParam);
                    return false;
                }

                // 验证数组是否需要包含指定值
                if (!$this->checkContainKey($value)) {
                    $arrParam[] = $this->options['containKey'];
                    $this->setError(Validation::ERR_CODE_ARRAY_NOT_CONTAIN_KEY, $arrParam);
                    return false;
                }

                // 验证数组是否需要包含特定的一组值
                if (!$this->checkContainKeys($value)) {
                    $arrParam[] = join(',', $this->options['containKeys']);
                    $this->setError(Validation::ERR_CODE_ARRAY_NOT_CONTAIN_KEY, $arrParam);
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