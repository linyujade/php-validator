<?php
class BoolValidatorTest extends PHPUnit_Framework_TestCase {

    /**
     * 测试验证bool类型里的必填选项
     */
    public function testRequired() {
        $objValue = '';
        $options  = array(
            'required' => true,
        );
        $objValidator = new BoolValidator('Is Close', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue = '1';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = '';
        $options  = array(
            'required' => false,
        );
        $objValidator1 = new BoolValidator('Is Close', $options);
        $this->assertEquals(true, $objValidator1->validate($objValue));
    }

    /**
     * 测试是否是有效的bool类型
     */
    public function testValid() {
        $objValue = '1';
        $options  = array();
        $objValidator = new BoolValidator('Is Close', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = '0';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 1;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 0;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = true;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 'false';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 'true';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue = 'aaaa';
        $this->assertEquals(false, $objValidator->validate($objValue));
    }
}