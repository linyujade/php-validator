<?php
class ArrayValidatorTest extends PHPUnit_Framework_TestCase {

    /**
     * 测试验证数组是否有效，以及必填设置
     */
    public function testRequiredAndValid() {
        $objValue = '';
        $options  = array(
            'required' => true,
        );
        $objValidator = new ArrayValidator('Tags', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = '1';
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = array();
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = array('aaaa');
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = '';
        $options  = array(
            'required' => false,
        );
        $objValidator1 = new ArrayValidator('Tags', $options);
        $this->assertEquals(true, $objValidator1->validate($objValue));
    }

    /**
     * 测试验证数组的长度
     */
    public function testLength() {
        $objValue = array();
        $options  = array(
            'length' => 4,
        );
        $objValidator = new ArrayValidator('Tags', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = array('aaa');
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = array('aaa', 'bbb', 'ccc', 'ddd', 'eee');
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = array('aaa', 'bbb', 'ccc', 'ddd');
        $this->assertEquals(true, $objValidator->validate($objValue));
    }

    /**
     * 测试验证数组的最大最小长度
     */
    public function testMinAndMaxLength() {
        $objValue = array();
        $options  = array(
            'minLength' => 1,
            'maxLength' => 4,
        );
        $objValidator = new ArrayValidator('Tags', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = array('aaa');
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = array('aaa', 'bbb', 'ccc', 'ddd', 'eee');
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = array('aaa', 'bbb', 'ccc', 'ddd');
        $this->assertEquals(true, $objValidator->validate($objValue));
    }

    /**
     * 测试验证数组的是否包含指定的值
     */
    public function testContainKey() {
        $objValue = array();
        $options  = array(
            'containKey' => 'aaa',
        );
        $objValidator = new ArrayValidator('Tags', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = array('aaa');
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = array('aaa', 'bbb', 'ccc', 'ddd', 'eee');
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = array('abc', 'bbb', 'ccc', 'ddd');
        $this->assertEquals(false, $objValidator->validate($objValue));
    }
    
    /**
     * 测试验证数组的是否包含指定的一组值
     */
    public function testContainKeys() {
        $objValue = array();
        $options  = array(
            'containKeys' => array('aaaa', 'bbbb'),
        );
        $objValidator = new ArrayValidator('Tags', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = array('aaa');
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = array('aaa', 'bbb', 'ccc', 'ddd', 'eee');
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = array('abc', 'bbb', 'ccc', 'ddd');
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = array('aaaa', 'bbb', 'ccc', 'ddd', 'eee');
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = array('aaaa', 'bbbb', 'ccc', 'ddd', 'eee');
        $this->assertEquals(true, $objValidator->validate($objValue));
    }
}