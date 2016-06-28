<?php
class BaseValidatorTest extends PHPUnit_Framework_TestCase {

    /**
     * 测试每次只替换一处的方法
     */
    public function testReplaceOnce() {
        $objValue     = '[?]aaa[?]bbb[?]ccc[?]ddd';
        $options      = array();
        $objValidator = new BaseValidator('name', $options);

        // 测试第一次替换
        $objValue     = $objValidator->replaceOnceString('?', '1', $objValue);
        $this->assertEquals('[1]aaa[?]bbb[?]ccc[?]ddd', $objValue);

        // 测试第二次替换
        $objValue     = $objValidator->replaceOnceString('?', '2', $objValue);
        $this->assertEquals('[1]aaa[2]bbb[?]ccc[?]ddd', $objValue);

        // 测试第三次替换
        $objValue     = $objValidator->replaceOnceString('?', '11', $objValue);
        $this->assertEquals('[1]aaa[2]bbb[11]ccc[?]ddd', $objValue);

        // 测试循环替换掉字符串中的?
        $args     = array('1', '2', '3', '4');
        $objValue = '[?]aaa[?]bbb[?]ccc[?]ddd';
        $newValue = $objValue;
        while (count($args)) {
            $str      = array_shift($args); 
            $newValue = $objValidator->replaceOnceString('?', $str, $newValue);
        }
        $this->assertEquals('[1]aaa[2]bbb[3]ccc[4]ddd', $newValue);
    }

    /**
     * 测试验证是否为空的方法，包含一些临界点的测试
     */
    public function testCheckEmpty() {
        // 如果字符串是空字符串，应该返回true
        $objValue     = '';
        $options      = array();
        $objValidator = new BaseValidator('name', $options);
        $this->assertEquals(true, $objValidator->checkEmpty($objValue));

        // 如果字符串为非空字符串，包括‘0’，也应该返回false
        $objValue = '1';
        $this->assertEquals(false, $objValidator->checkEmpty($objValue));

        $objValue = '0';
        $this->assertEquals(false, $objValidator->checkEmpty($objValue));

        // 如果变量没设定值，应该就是空，会返回true
        $objValue = null;
        $this->assertEquals(true, $objValidator->checkEmpty($objValue));

        // 测试int／float类型，如果是0或者0.0，都应该表示已经设置了值，所以是非空，即返回false
        $objValue = 1;
        $this->assertEquals(false, $objValidator->checkEmpty($objValue));

        $objValue = 0;
        $this->assertEquals(false, $objValidator->checkEmpty($objValue));

        $objValue = 0.0;
        $this->assertEquals(false, $objValidator->checkEmpty($objValue));

        // 测试数组，如果数组长度为0，应该就是空数组，应返回true
        $objValue = array();
        $this->assertEquals(true, $objValidator->checkEmpty($objValue));

        // 测试数组，如果数组长度大于0，应返回false
        $objValue = array('0');
        $this->assertEquals(false, $objValidator->checkEmpty($objValue));
    }

    /**
     * 测试必填项方法，包含一些临界点的测试
     */
    public function testRequired() {

        // 如果规则选项里没有设置必填选项，则就不是必填，返回false
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->isRequired());

        // 如果规则选项里设置了必填选项，并且必填选项为true，则返回true
        $options      = array(
            'required' => true,
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->isRequired());

        // 如果规则选项里设置了必填选项，并且必填选项为false，则返回false
        $options      = array(
            'required' => false,
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->isRequired());

        // 必填选项的值，也应该支持0/1的表现方式
        $options      = array(
            'required' => 1,
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->isRequired());

        $options      = array(
            'required' => 0,
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->isRequired());

        // 必填选项的值，也应该支持字符串‘0’/‘1’的表现方式
        $options      = array(
            'required' => '1',
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->isRequired());

        $options      = array(
            'required' => '0',
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->isRequired());

        // 必填选项的值，也应该支持字符串‘true’/‘false’的表现方式
        $options      = array(
            'required' => 'true',
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->isRequired());

        $options      = array(
            'required' => 'false',
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->isRequired());

        // 必选项中的值如果是其他字符串，应该都是表示false
        $options      = array(
            'required' => 'aaa',
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->isRequired());

        // 如果是设置为null，也应该是false
        $options      = array(
            'required' => null,
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->isRequired());

        // 或者空数组，也应该是false
        $options      = array(
            'required' => array(),
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->isRequired());
    }

    /**
     * 验证正则表达式方法，包含一些临界点的测试
     */
    public function testCheckRegex() {
        // 没有设置任何正则表达式的时候，调用验证正则表达式的方法，应该通过
        $objValue     = '';
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->checkRegex($objValue));

        // 设置了11位数字的正则表达式，如果调用验证正则表达式的方法，当前值是空字符串，应该不通过的
        $options      = array(
            'pattern' => '/^1\d{10}$/',
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->checkRegex($objValue));

        // 下面这个“字符串”满足11位数字的正则表达式，所以验证通过
        $objValue     = '11111111111';
        $this->assertEquals(true, $objValidator->checkRegex($objValue));

        // 下面的这个int类型的数字，也是11位，也应该满足条件
        $objValue     = 11111111111;
        $this->assertEquals(true, $objValidator->checkRegex($objValue));

        // 下面的正则表达式是去验证是否是EMail值，显然当前值11111111111不符合规则
        $options      = array(
            'pattern' => '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->checkRegex($objValue));

        // 空对象也不符合规则
        $objValue     = null;
        $this->assertEquals(false, $objValidator->checkRegex($objValue));

        // 空数组也不符合规则
        $objValue     = array();
        $this->assertEquals(false, $objValidator->checkRegex($objValue));

        // 下面邮箱应该符合规则
        $objValue     = 'linyu03@baidu.com';
        $this->assertEquals(true, $objValidator->checkRegex($objValue));
    }

    /**
     * 测试是否在值集里的方法
     */
    public function testCheckValueSet() {

        // 如果规则里没有设置值集数组，那使用checkValueSet方法是，应该返回true
        $objValue     = '';
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->checkValueSet($objValue));

        // 即使规则里设置了值集数组，但是该数组为空数组，也应该返回true
        $options      = array(
            'valueSet' => array(),
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->checkValueSet($objValue));

        // 如果值集不为空数组，而当前的输入值又不在这个值集里，应该返回false
        $options      = array(
            'valueSet' => array(1, 2, 3),
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->checkValueSet($objValue));

        // 如果值集不为空数组，而当前的输入值又在这个值集里，应该返回true
        $objValue     = '1';
        $this->assertEquals(true, $objValidator->checkValueSet($objValue));

        $objValue     = '2';
        $this->assertEquals(true, $objValidator->checkValueSet($objValue));

        $objValue     = 3;
        $this->assertEquals(true, $objValidator->checkValueSet($objValue));

        $objValue     = 5;
        $this->assertEquals(false, $objValidator->checkValueSet($objValue));

        $objValue     = 'a';
        $this->assertEquals(false, $objValidator->checkValueSet($objValue));

        $options      = array(
            'valueSet' => array('a', '2', '3'),
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->checkValueSet($objValue));

        // 测试整数和浮点数，只有这个值在值集里，就应该OK
        $objValue     = 3;
        $this->assertEquals(true, $objValidator->checkValueSet($objValue));

        $objValue     = 3.0;
        $this->assertEquals(true, $objValidator->checkValueSet($objValue));

        // 测试数组的值集
        $options      = array(
            'valueSet' => array(
                'aaa' => array(1, 2, 3),
                'bbb' => array(4, 5, 6),
                'ccc' => array(7, 8, 9),
            ),
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->checkValueSet($objValue));

        // 应该是包含在值集里的值，而不是key
        $objValue     = 'aaa';
        $this->assertEquals(false, $objValidator->checkValueSet($objValue));

        $objValue     = array(1, 2, 3);
        $this->assertEquals(true, $objValidator->checkValueSet($objValue));
    }

    // 测试整数类型，和一些临界点
    public function testCheckInteger() {
        // 空字符串不是整数
        $objValue     = '';
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->checkInteger($objValue));

        // 空对象也不是整数
        $objValue     = null;
        $this->assertEquals(false, $objValidator->checkInteger($objValue));

        // 1是整数
        $objValue     = 1;
        $this->assertEquals(true, $objValidator->checkInteger($objValue));
        
        // 0是整数
        $objValue     = 0;
        $this->assertEquals(true, $objValidator->checkInteger($objValue));
        
        // 8进制数字
        $objValue     = 0101;
        $this->assertEquals(true, $objValidator->checkInteger($objValue));
        
        // 16进制数字
        $objValue     = 0x101;
        $this->assertEquals(true, $objValidator->checkInteger($objValue));
        
        // 8进制数字
        $objValue     = 0201;
        $this->assertEquals(true, $objValidator->checkInteger($objValue));

        // 浮点数如果不等于取整，不是整数
        $objValue     = 1.2;
        $this->assertEquals(false, $objValidator->checkInteger($objValue));

        // 16进制数字
        $objValue     = 0xabcd;
        $this->assertEquals(true, $objValidator->checkInteger($objValue));

        // 16进制字符串，只能当一般的字符串处理
        $objValue     = '0xabcd';
        $this->assertEquals(false, $objValidator->checkInteger($objValue));

        // 浮点数取整等于整数的数字，就可以算为整数
        $objValue     = 0.0;
        $this->assertEquals(true, $objValidator->checkInteger($objValue));

        // 浮点数取整等于整数的数字，就可以算为整数
        $objValue     = 3.0;
        $this->assertEquals(true, $objValidator->checkInteger($objValue));
    }

    public function testCheckFloat() {
        // 空字符串不是浮点数
        $objValue     = '';
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->checkFloat($objValue));

        // 0.0是浮点数
        $objValue     = 0.0;
        $this->assertEquals(true, $objValidator->checkFloat($objValue));

        // 1.2是浮点数
        $objValue     = 1.2;
        $this->assertEquals(true, $objValidator->checkFloat($objValue));

        // 整数可以自动转为浮点数，数值等价就行
        $objValue     = 0xabcd;
        $this->assertEquals(true, $objValidator->checkFloat($objValue));

        // 下面这个字符串被当成一般的字符串处理
        $objValue     = '0xabcd';
        $this->assertEquals(false, $objValidator->checkFloat($objValue));

        // 空对象不是浮点数
        $objValue     = null;
        $this->assertEquals(false, $objValidator->checkFloat($objValue));

        $objValue     = 1;
        $this->assertEquals(true, $objValidator->checkFloat($objValue));
        
        $objValue     = 0;
        $this->assertEquals(true, $objValidator->checkFloat($objValue));
        
        $objValue     = 0101;
        $this->assertEquals(true, $objValidator->checkFloat($objValue));

        $objValue     = 0.000101;
        $this->assertEquals(true, $objValidator->checkFloat($objValue));
    }

    /**
     * 测试bool类型
     */
    public function testCheckBool() {

        // 空字符串表示bool值里的false，所以也算bool值
        $objValue     = '';
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->checkBool($objValue));

        $objValue     = true;
        $this->assertEquals(true, $objValidator->checkBool($objValue));

        $objValue     = false;
        $this->assertEquals(true, $objValidator->checkBool($objValue));

        // 字符串中的‘true’和‘false’能自动识别对应到bool值里的true和false，所以也是bool值
        $objValue     = 'true';
        $this->assertEquals(true, $objValidator->checkBool($objValue));

        $objValue     = 'false';
        $this->assertEquals(true, $objValidator->checkBool($objValue));

        // 下面这个字符串不能对应到bool类型中的任何值，所以不是bool值
        $objValue     = 'aaa';
        $this->assertEquals(false, $objValidator->checkBool($objValue));

        // 下面的0/1/'0'/'1'都能对应到对应的bool值
        $objValue     = 0;
        $this->assertEquals(true, $objValidator->checkBool($objValue));

        $objValue     = 1;
        $this->assertEquals(true, $objValidator->checkBool($objValue));

        $objValue     = '0';
        $this->assertEquals(true, $objValidator->checkBool($objValue));

        $objValue     = '1';
        $this->assertEquals(true, $objValidator->checkBool($objValue));

        // 数字11不是bool值
        $objValue     = 11;
        $this->assertEquals(false, $objValidator->checkBool($objValue));
    }

    /**
     * 测试是否是数组
     */
    public function testCheckArray() {

        // 空字符串不是数组
        $objValue     = '';
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->checkArray($objValue));

        // 空数组是数组，只是数组长度为0
        $objValue     = array();
        $this->assertEquals(true, $objValidator->checkArray($objValue));

        $objValue     = array('a');
        $this->assertEquals(true, $objValidator->checkArray($objValue));

        // 下面的整数0/1、已经空对象null都不是数组
        $objValue     = 1;
        $this->assertEquals(false, $objValidator->checkArray($objValue));

        $objValue     = 0;
        $this->assertEquals(false, $objValidator->checkArray($objValue));

        $objValue     = null;
        $this->assertEquals(false, $objValidator->checkArray($objValue));

        // 字符串不是数组
        $objValue     = 'aaa';
        $this->assertEquals(false, $objValidator->checkArray($objValue));

        $objValue     = array(
            'aaa' => 1,
            'bbb' => 2,
        );
        $this->assertEquals(true, $objValidator->checkArray($objValue));
    }

    /**
     * 测试对象长度是否符合规则
     */
    public function testCheckLength() {

        // 没有指定固定长度约束，所以验证应该通过
        $objValue     = '';
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->checkLength($objValue));

        $objValue     = '111';
        $this->assertEquals(true, $objValidator->checkLength($objValue));

        $objValue     = 111;
        $this->assertEquals(true, $objValidator->checkLength($objValue));

        $objValue     = 0.01;
        $this->assertEquals(true, $objValidator->checkLength($objValue));

        $objValue     = array('a');
        $this->assertEquals(true, $objValidator->checkLength($objValue));

        // 设定了固定长度为5的约束，所以空字符串不满足条件
        $objValue     = '';
        $options      = array(
            'length' => 5,
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->checkLength($objValue));

        // 满足长度为5的字符串，测试应该通过
        $objValue     = '1aa11';
        $this->assertEquals(true, $objValidator->checkLength($objValue));

        // 满足长度为5的整数
        $objValue     = 11111;
        $this->assertEquals(true, $objValidator->checkLength($objValue));

        // 满足长度为5的浮点数
        $objValue     = 0.001;
        $this->assertEquals(true, $objValidator->checkLength($objValue));

        // 满足长度为5的数组
        $objValue     = array(1, 2, 3, 4, 5);
        $this->assertEquals(true, $objValidator->checkLength($objValue));

        // 空对象、bool类型数值，不能测试长度，应该验证不通过
        $objValue     = null;
        $this->assertEquals(false, $objValidator->checkLength($objValue));

        $objValue     = false;
        $this->assertEquals(false, $objValidator->checkLength($objValue));

        $objValue     = true;
        $this->assertEquals(false, $objValidator->checkLength($objValue));

        $objValue     = 'false';
        $this->assertEquals(true, $objValidator->checkLength($objValue));

        $objValue     = 'true';
        $this->assertEquals(false, $objValidator->checkLength($objValue));
    }

    public function testCheckMinLength() {
        $objValue     = '';
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = '111';
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = 111;
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = 0.01;
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = array('a');
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = '';
        $options      = array(
            'minLength' => 5,
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->checkMinLength($objValue));

        $objValue     = '1aa11';
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = 11111;
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = 1111;
        $this->assertEquals(false, $objValidator->checkMinLength($objValue));

        $objValue     = 111111;
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = 0.001;
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = 0.01;
        $this->assertEquals(false, $objValidator->checkMinLength($objValue));

        $objValue     = 0.0001;
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = array(1, 2, 3, 4, 5);
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = array(1, 2, 3, 4);
        $this->assertEquals(false, $objValidator->checkMinLength($objValue));

        $objValue     = null;
        $this->assertEquals(false, $objValidator->checkMinLength($objValue));

        $objValue     = false;
        $this->assertEquals(false, $objValidator->checkMinLength($objValue));

        $objValue     = true;
        $this->assertEquals(false, $objValidator->checkMinLength($objValue));

        $objValue     = 'false';
        $this->assertEquals(true, $objValidator->checkMinLength($objValue));

        $objValue     = 'true';
        $this->assertEquals(false, $objValidator->checkMinLength($objValue));
    }

    public function testCheckMaxLength() {
        $objValue     = '';
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = '111';
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = 111;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = 0.01;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = array('a');
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = '';
        $options      = array(
            'maxLength' => 5,
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = '1aa11';
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = 11111;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = 1111;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = 111111;
        $this->assertEquals(false, $objValidator->checkMaxLength($objValue));

        $objValue     = 0.001;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = 0.01;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = 0.0001;
        $this->assertEquals(false, $objValidator->checkMaxLength($objValue));

        $objValue     = array(1, 2, 3, 4, 5);
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = array(1, 2, 3, 4);
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = null;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = false;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = true;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = 'false';
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = 'true';
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $options      = array(
            'maxLength' => 3,
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->checkMaxLength($objValue));

        $objValue     = null;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = false;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = true;
        $this->assertEquals(true, $objValidator->checkMaxLength($objValue));

        $objValue     = '1113';
        $this->assertEquals(false, $objValidator->checkMaxLength($objValue));
    }


    /**
     * 测试设置空错误信息、以及取错误信息的方法
     */
    public function testSetEmptyMsg() {
        // 设置空错误信息，如果规则里没有指定emptyMsg的值，即没有指定如果数值为空该展现的信息，系统默认返回系统定义的错误信息
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $objValidator->setEmptyMsg();
        $this->assertEquals(Validation::$errCodeDesc[Validation::ERR_CODE_FIELD_EMPTY], $objValidator->getError());

        $objValidator = new BaseValidator('aaa', $options);
        $objValidator->setEmptyMsg();
        $this->assertEquals(str_replace('?', 'aaa', Validation::$errCodeDesc[Validation::ERR_CODE_FIELD_EMPTY]), $objValidator->getError());

        // 下面设置了emptyMsg的语句，所以getError()返回的应该等于指定的emptyMsg的值
        $options      = array(
            'emptyMsg' => 'there is an error',
        );
        $objValidator = new BaseValidator('', $options);
        $objValidator->setEmptyMsg();
        $this->assertEquals('there is an error', $objValidator->getError());

        $objValidator = new BaseValidator('bbb', $options);
        $objValidator->setEmptyMsg();
        $this->assertEquals('there is an error', $objValidator->getError());
    }

    /**
     * 测试设置错误信息、以及取错误信息的方法
     */
    public function testSetError() {
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $objValidator->setError();
        $this->assertEquals('', $objValidator->getError());

        $objValidator = new BaseValidator('aaa', $options);
        $objValidator->setError();
        $this->assertEquals('', $objValidator->getError());

        $options      = array(
            'errMsg' => 'there is an error',
        );
        $objValidator = new BaseValidator('', $options);
        $objValidator->setError();
        $this->assertEquals('there is an error', $objValidator->getError());

        $objValidator = new BaseValidator('bbb', $options);
        $objValidator->setError();
        $this->assertEquals('there is an error', $objValidator->getError());

        $objValidator = new BaseValidator('bbb', $options);
        $objValidator->setError(Validation::ERR_CODE_OUT_OF_RANGE_MAX);
        $this->assertEquals('there is an error', $objValidator->getError());

        $objValidator = new BaseValidator('bbb', $options);
        $objValidator->setError(Validation::ERR_CODE_OUT_OF_RANGE_MAX, 'aaa');
        $this->assertEquals('there is an error', $objValidator->getError());

        $objValidator = new BaseValidator('bbb', $options);
        $objValidator->setError(Validation::ERR_CODE_OUT_OF_RANGE_MAX, array('aaa'));
        $this->assertEquals('there is an error', $objValidator->getError());

        $options      = array();
        $objValidator = new BaseValidator('bbb', $options);
        $objValidator->setError(Validation::ERR_CODE_OUT_OF_RANGE_MAX);
        $this->assertEquals(Validation::$errCodeDesc[Validation::ERR_CODE_OUT_OF_RANGE_MAX], $objValidator->getError());

        $objValidator = new BaseValidator('bbb', $options);
        $objValidator->setError(Validation::ERR_CODE_OUT_OF_RANGE_MAX, 'aaa');
        $str1 = str_replace('?', 'aaa', Validation::$errCodeDesc[Validation::ERR_CODE_OUT_OF_RANGE_MAX]);
        $str2 = $objValidator->getError();
        $this->assertEquals($str1, $str2);

        $objValidator = new BaseValidator('bbb', $options);
        $objValidator->setError(Validation::ERR_CODE_OUT_OF_RANGE_MAX, array('aaa'));
        $str1 = $objValidator->replaceOnceString('?', 'aaa', Validation::$errCodeDesc[Validation::ERR_CODE_OUT_OF_RANGE_MAX]);
        $str2 = $objValidator->getError();
        $this->assertEquals($str1, $str2);

        $objValidator = new BaseValidator('bbb', $options);
        $objValidator->setError(Validation::ERR_CODE_OUT_OF_RANGE_MAX, array('aaa', 'bbb'));
        $str1 = $objValidator->replaceOnceString('?', 'aaa', Validation::$errCodeDesc[Validation::ERR_CODE_OUT_OF_RANGE_MAX]);
        $str1 = $objValidator->replaceOnceString('?', 'bbb', $str1);
        $str2 = $objValidator->getError();
        $this->assertEquals($str1, $str2);
    }

    /**
     * 测试基类里的验证方法，其实就是验证是否必填项
     */
    public function testValidate() {
        $objValue     = '';
        $options      = array();
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = false;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = null;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = array();
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = '';
        $options      = array(
            'required' => true,
        );
        $objValidator = new BaseValidator('', $options);
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue     = false;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = null;
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue     = array();
        $this->assertEquals(false, $objValidator->validate($objValue));

        $objValue     = '1';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = '0';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = 1;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = 0;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = array('a');
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = '1asdafa';
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = 110;
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = array('a', 'd');
        $this->assertEquals(true, $objValidator->validate($objValue));

        $objValue     = 11.012;
        $this->assertEquals(true, $objValidator->validate($objValue));

    }
}