<?php
/**
 * object, string, int, float, bool, array类型验证器
 * Author: linyu(linyu03@baidu.com)
 * Date: 16/06/15
 */
class Validator {

    /**
     * 验证数据的合法性
     * @param array $schema 验证规则描述
     * @param array $arrInput 需要验证的数据
     * @param string $errMsg 返回错误信息
     * @return bool 返回true/false
     */
    public static function validate($schema, $arrInput, &$errMsg) {
        $tmpErrMsg = '';
        
        // 循环每个输入的键值，把所有字段的验证的错误信息通过换行符连接起来
        foreach ($arrInput as $key => $item) {

            // 如果输入的键存在验证规则的键中，则进行验证
            if (isset($schema[$key])) {
                
                // 得到当前验证对象的验证规则
                $schemaItem = $schema[$key];

                // 根据验证规则中的type生成对应的验证器，所有的验证器构造函数需要输入字段的标题，和验证字段的规则数组
                $objValidator = new Validation::$arrClassNameByType[$schemaItem['type']]($schemaItem['title'], $schemaItem['options']);
                if (!$objValidator->validate($item)) {
                    $tmpErrMsg .= $objValidator->getError() . "\n";
                }
            }
        }
        $errMsg .= $tmpErrMsg;
        return empty($tmpErrMsg);
    }

}