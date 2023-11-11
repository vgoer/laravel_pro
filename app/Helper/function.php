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
