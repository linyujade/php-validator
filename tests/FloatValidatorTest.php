<?php
class FloatValidatorTest extends PHPUnit_Framework_TestCase {

    /**
     * 测试验证float类型里的必填选项
     */
    public function testRequired() {
        $objValue = '';
        $options  = array(
            'required' => true,
        );
        $objValidator = new FloatValidator('Amount', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = '80.55';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = '60';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 'abcd';
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = '';
        $options  = array(
            'required' => false,
        );
        $objValidator1 = new FloatValidator('Amount', $options);
        $this->assertEquals(true, $objValidator1->validate($objValue));
    }

    /**
     * 测试float规则里设定的最大最小值约束
     */
    public function testMinAndMax() {
        $objValue = 1000;
        $options  = array(
            'min' => 0.0001,
            'max' => 10000,
        );
        $objValidator = new FloatValidator('Amount', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 0;
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = 10001;
        $this->assertEquals(false, $objValidator->validate($objValue));
    }

    /**
     * 测试float类型的正则表达式匹配
     */
    public function testRegex() {
        $objValue = 1.00001;
        $options  = array(
            'pattern' => '/^\d+(\.\d{1,4})?$/',
        );
        $objValidator = new FloatValidator('Amount', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = 85.001;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 80.66;
        $this->assertEquals(true, $objValidator->validate($objValue));
    }

    /**
     * 测试float类型是否在值集里
     */
    public function testValueSet() {
        $objValue = 75.55;
        $options  = array(
            'valueSet' => array(65.3, 75.4, 85.5, 95.6),
        );
        $objValidator = new FloatValidator('Amount', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = 75.4;
        $this->assertEquals(true, $objValidator->validate($objValue));
    }
}