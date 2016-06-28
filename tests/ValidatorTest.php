<?php
class ValidatorTest extends PHPUnit_Framework_TestCase {

    /**
     * 模拟真正应用场景，如果设定一组规则，验证输入的项是否都满足这组规则
     */
    public function testValidate() {
        $schema = array(
            'name' => array(
                'title'   => '姓名',
                'type'    => Validation::TYPE_STRING,
                'options' => array(
                    'required'   => true,
                    'minLength'  => 2,
                    'maxLength'  => 20,
                ),
            ),
            'email' => array(
                'title'   => 'email',
                'type'    => Validation::TYPE_STRING,
                'options' => array(
                    'required'   => true,
                    'pattern'    => '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',
                ),
            ),
            'mobile' => array(
                'title'   => '手机号码',
                'type'    => Validation::TYPE_STRING,
                'options' => array(
                    'required'   => true,
                    'length'     => 11,
                    'pattern'    => '/^1\d{10}$/',
                ),
            ),
            'amount' => array(
                'title'   => '金额',
                'type'    => Validation::TYPE_FLOAT,
                'options' => array(
                    'required'   => true,
                    'min'        => 39,
                    'max'        => 2000,
                    'pattern'    => '/^\d+(\.\d{1,2})?$/',
                ),
            ),
            'age' => array(
                'title'   => '年龄',
                'type'    => Validation::TYPE_INT,
                'options' => array(
                    'required'   => true,
                    'min'        => 18,
                    'max'        => 35,
                    'pattern'    => '/^\d+$/',
                ),
            ),
            'healthy' => array(
                'title'   => '是否健康',
                'type'    => Validation::TYPE_BOOL,
                'options' => array(
                    'required'   => true,
                    'valueSet'   => array('0', '1'),
                ),
            ),
        );

        $errMsg = '';
        $arrInput = array(
            'name'     => '林玉',
            'email'    => 'linyu03@baidu.com',
            'mobile'   => '15911071159',
            'amount'   => '120.5',
            'age'      => '30',
            'healthy'  => '1',
        );
        $this->assertEquals(true, Validator::validate($schema, $arrInput, $errMsg));

        $arrInput = array(
            'name'     => 'A',
            'email'    => 'linyu03@baidu.com',
            'mobile'   => '15911071159',
            'amount'   => '120.5',
            'age'      => '30',
            'healthy'  => '1',
        );
        $this->assertEquals(false, Validator::validate($schema, $arrInput, $errMsg));

        $arrInput = array(
            'name'     => '林玉',
            'email'    => 'linyu03',
            'mobile'   => '15911071159',
            'amount'   => '120.5',
            'age'      => '30',
            'healthy'  => '1',
        );
        $this->assertEquals(false, Validator::validate($schema, $arrInput, $errMsg));

        $arrInput = array(
            'name'     => '林玉',
            'email'    => 'linyu03@baidu.com',
            'mobile'   => '25911071159',
            'amount'   => '120.5',
            'age'      => '30',
            'healthy'  => '1',
        );
        $this->assertEquals(false, Validator::validate($schema, $arrInput, $errMsg));

        $arrInput = array(
            'name'     => '林玉',
            'email'    => 'linyu03@baidu.com',
            'mobile'   => '15911071159',
            'amount'   => '12.5',
            'age'      => '30',
            'healthy'  => '1',
        );
        $this->assertEquals(false, Validator::validate($schema, $arrInput, $errMsg));

        $arrInput = array(
            'name'     => '林玉',
            'email'    => 'linyu03@baidu.com',
            'mobile'   => '15911071159',
            'amount'   => '120.5',
            'age'      => '38',
            'healthy'  => '1',
        );
        $this->assertEquals(false, Validator::validate($schema, $arrInput, $errMsg));

        $arrInput = array(
            'name'     => '林玉',
            'email'    => 'linyu03@baidu.com',
            'mobile'   => '15911071159',
            'amount'   => '120.5',
            'age'      => '30',
            'healthy'  => '12',
        );
        $this->assertEquals(false, Validator::validate($schema, $arrInput, $errMsg));
    }
}