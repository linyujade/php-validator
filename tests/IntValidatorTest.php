<?php
class IntValidatorTest extends PHPUnit_Framework_TestCase {

    /**
     * 测试验证int类型里的必填选项
     */
    public function testRequired() {
        $objValue = '';
        $options  = array(
            'required' => true,
        );
        $objValidator = new IntValidator('Score', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = '10';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 'abcd';
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = '';
        $options  = array(
            'required' => false,
        );
        $objValidator = new IntValidator('Score', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));
    }

    /**
     * 测试int规则里设定的最大最小值约束
     */
    public function testMinAndMax() {
        $objValue = 75;
        $options  = array(
            'min' => 60,
            'max' => 100,
        );
        $objValidator = new IntValidator('Score', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 59;
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = 101;
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = '';
        $this->assertEquals(true, $objValidator->validate($objValue));
    }

    /**
     * 测试int类型的正则表达式匹配
     */
    public function testRegex() {
        $objValue = 70;
        $options  = array(
            'pattern' => '/^[7-9]0$/',
        );
        $objValidator = new IntValidator('Score', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 85;
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = '';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 80.66;
        $options  = array(
            'pattern' => '/^\d+$/',
        );
        $objValidator = new IntValidator('Score', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));
    }

    /**
     * 测试int类型是否在值集里
     */
    public function testValueSet() {
        $objValue = 75;
        $options  = array(
            'valueSet' => array(65, 75, 85, 95),
        );
        $objValidator = new IntValidator('Score', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 70;
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = null;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = array();
        $this->assertEquals(true, $objValidator->validate($objValue));
    }
}