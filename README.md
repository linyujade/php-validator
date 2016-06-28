# php-validator

跟前端页面验证一样，需要设定页面某些字段的验证规则，验证页面提交的信息。

一般会用js去验证一遍，数据提交到后台，后台也应该再次验证一遍。

而这个php-validator就是在php端对页面提交的数据进行验证的一个静态类。

使用方法:

Validator::validate($schema, $arrInput, $errMsg);

$schema是根据业务需求定义的一组业务规则；

$arrInput是前端提交的一组数据；

$errMsg是验证过程中，如果验证不通过，会记录一些错误信息。
