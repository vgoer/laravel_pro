<?php

/**
 * 成功打印的函数
 *
 * @param  array  $data 数据
 * @param  int  $code 状态码
 * @param  string  $msg   返回提示
 * @param  int  $count 获取次数
 * @return json 返回json
 */
function success($data = [], $code = 200, $msg = '', $count = 0)
{
    return json_encode([
        'code' => $code,
        'msg' => $msg == '' ? '操作成功' : $msg,
        'count' => $count == 0 ? count($data) : $count,
        'data' => $data,
    ]);
}

/**
 * 返回错误信息
 *
 * @param  int  $code 错误码
 * @return void|json $res_data 返回json
 */
function error($code = null)
{
    $error_code = [
        // 系统
        500000 => '系统繁忙',
        500001 => '操作失败',
        500002 => '无效参数',
        500003 => '文件上传失败',

        // Auth JWT 登录凭证
        500100 => '用户名不能为空',
        500101 => '用户密码不能为空',
        500102 => '用户邮箱不能为空',
        500103 => '用户不存在，请先注册',

        // 登录返回
        500900 => '账号不存在',

        // 等等
    ];

    if (empty($code)) {
        return false;
    }

    $res_json = [
        'code' => $code,
        'msg' => $error_code[$code],
    ];

    return json_encode($res_json);
}

/**
 * @ Salt 生成密码盐
 * param len 默认长度 4
 */
function salt($len = 4)
{
    $rand_str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $max = strlen($rand_str);
    $str = '';
    for ($i = 0; $i < $len; $i++) {
        $str .= $rand_str[mt_rand(0, $max - 1)];
    }

    return $str;
}

/**
 * 电话号码
 *
 * @param [type] $phone
 * @return void
 */
function validate_phone_number($phone) {
    // 去除字符串中的空格和特殊字符
    $cleaned_phone = preg_replace('/[^0-9]/', '', $phone);

    // 验证手机号码是否为11位数字
    if (strlen($cleaned_phone) !== 11) {
        return false;
    }

    // 验证手机号码是否符合中国的号码规则
    $pattern = '/^(?:\+?86)?1(?:3\d{3}|4[5-9]\d{2}|5\d{3}|6[2389]\d{2}|7[0-8]\d{2}|8\d{3}|9[189]\d{2})\d{6}$/';
    if (!preg_match($pattern, $cleaned_phone)) {
        return false;
    }

    // 手机号码验证通过
    return true;
}

/**
 * 验证ip
 *
 * @param [type] $ip
 * @return void
 */
function validate_ip_address($ip) {
    // 验证IPv4地址
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        return true;
    }

    // 验证IPv6地址
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        return true;
    }

    // IP地址格式不正确
    return false;
}