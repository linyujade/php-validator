<?php
class StringValidatorTest extends PHPUnit_Framework_TestCase {

    /**
     * 测试验证字符串类型里的必填选项
     */
    public function testRequired() {
        $objValue = '';
        $options  = array(
            'required' => true,
        );
        $objValidator = new StringValidator('name', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = 'zhaoyun';
        $this->assertEquals(true, $objValidator->validate($objValue));
        
        $objValue = 1;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = '';
        $options  = array(
            'required' => false,
        );
        $objValidator1 = new StringValidator('name', $options);
        $this->assertEquals(true, $objValidator1->validate($objValue));
    }

    /**
     * 测试验证字符串的长度
     */
    public function testLength() {
        $objValue = '111';
        $options  = array(
            'length' => 11,
        );
        $objValidator = new StringValidator('mobile', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = '111111111111';
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = '11111111111';
        $this->assertEquals(true, $objValidator->validate($objValue));
    }

    /**
     * 测试验证字符串的最大最小长度限制
     */
    public function testMinAndMaxLength() {
        $objValue = '111';
        $options  = array(
            'minLength' => 10,
            'maxLength' => 20,
        );
        $objValidator = new StringValidator('name', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = '111111111111';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = '1111111111';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = '11111111111111111111';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = '111111111111111111111';
        $this->assertEquals(false, $objValidator->validate($objValue));
    }

    /**
     * 测试验证字符串是否符合某个正则表达式
     */
    public function testRegex() {
        $objValue = 'aaaa';
        $options  = array(
            'pattern' => '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',
        );
        $objValidator = new StringValidator('email', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = 'linyu03@baidu.com';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 'aaaa';
        $options  = array(
            'pattern' => '/^1\d{10}$/',
        );
        $objValidator1 = new StringValidator('mobile', $options);
        $this->assertEquals(false, $objValidator1->validate($objValue));

        $objValue = '11111111111';
        $this->assertEquals(true, $objValidator1->validate($objValue));

        $objValue = '21111111111';
        $this->assertEquals(false, $objValidator1->validate($objValue));
    }

    /**
     * 测试验证字符串的是否在指定值集里
     */
    public function testValueSet() {
        $objValue = 'aaa';
        $options  = array(
            'valueSet' => array('aaa', 'bbb', 'ccc', 'ddd'),
        );
        $objValidator = new StringValidator('tag', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 'abc';
        $this->assertEquals(false, $objValidator->validate($objValue));
    }
}