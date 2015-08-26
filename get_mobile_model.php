<?php
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
//$ua = 'mozilla/5.0 (linux; u; android 4.1.2; zh-cn; hw-huawei_c8815/c8815v100r001c92b123; 540*960; ctc/2.0) applewebkit/534.30 (khtml, like gecko) mobile safari/534.30';

if (preg_match('/android/', $ua)) {
    if (preg_match('/opera/', $ua)) { //特殊浏览器(opera/9.80 (android; opera mini/7.8.35885/36.2596; u; zh) presto/2.12.423 version/12.16)
        $arr = explode(" ", $ua);
        $model_arr = explode('/', $arr[0]);
        $mobile_model = $model_arr[0];
        $sys_version = $model_arr[1];
    } else if (preg_match('/firefox/', $ua)) { //特殊浏览器(mozilla/5.0 (android; mobile; rv:40.0) gecko/40.0 firefox/40.0)
        $mobile_model = 'firefox';
        $sys_version = '0';
    } else if (preg_match('/mozilla/', $ua)) { //主流(mozilla/5.0 (linux; android 4.4.4; coolpad y76 build/ktu84p) applewebkit/537.36 (khtml, like gecko) version/4.0 chrome/33.0.0.0 mobile safari/537.36 liebaofast/3.17.4)
        $arr = explode(";", $ua);
        if (preg_match('/u;/', $ua)) {
            $sys_version = $arr[2];
            $model_str = $arr[4];
        } else {
            $sys_version = $arr[1];
            $model_str = $arr[2];
        }
        $sys_version = substr($sys_version, 9);

        $model_arr = explode(')', $model_str);
        $mobile_model = $model_arr[0];
    } else { //其他(lenovo-a808t_td/v2.0 linux/3.4.5 android/4.4.2 release/03.20.2014 browser/applewebkit537.36 mobile safari/537.36)
        $arr = explode(" ", $ua);
        $model_arr = explode('/', $arr[0]);
        $mobile_model = $model_arr[0];

        if (preg_match('/linux\//', $ua)) {
            $sys_arr = explode('/', $arr[2]);
            $sys_version = $sys_arr[1];
        } else {
            $sys_arr = explode('/', $arr[1]);
            $sys_version = $sys_arr[1];
        }
    }
    $this->load->model('ua_model');
    $data['ua'] = $ua;
    $data['sign'] = md5($ua);
    $data['sys_version'] = $sys_version;

    $mobile_model = trim($mobile_model);
    $pos = strpos($mobile_model, 'build');
    if ($pos != false) {
        $model_len = strlen($mobile_model);
        $len = $model_len - $pos;
        $mobile_model = substr($mobile_model, 0, -$len);
    }
    if (preg_match('/\//', $ua)) {
        $m_arr = explode('/', $mobile_model);
        $mobile_model = $m_arr[0];
    }

    echo '浏览器信息: ' . $ua;
    echo '<br>';
    echo '系统版本号: ' . $sys_version;
    echo '<br>';
    echo '手机型号: ' . $mobile_model;
    echo '<br>';
} else {
    echo '非android设备';
}