<?php
namespace common\helpers;
class HttpRequest{
    /**
     * 模拟请求
     */
    public static function http_request($url, $method = 'GET', $postfields = NULL, $headers = array()) {
        $parse = parse_url($url);

        isset($parse['host']) ||$parse['host'] = '';
        isset($parse['path']) || $parse['path'] = '';
        isset($parse['query']) || $parse['query'] = '';
        isset($parse['port']) || $parse['port'] = '';

        $path = $parse['path'] ? $parse['path'].($parse['query'] ? '?'.$parse['query'] : '') : '/';
        $host = $parse['host'];

        //协议
        if ($parse['scheme'] == 'https') {
            $version = '1.1';
            $port = empty($parse['port']) ? 443 : $parse['port'];
            $host = 'ssl://'.$host;
        } else {
            $version = '1.0';
            $port = empty($parse['port']) ? 80 : $parse['port'];
        }

        //Headers
        $headers[] = "Host: {$parse['host']}";
        $headers[] = 'Connection: Close';
        $headers[] = "User-Agent: $_SERVER[HTTP_USER_AGENT]";
        $headers[] = 'Accept: */*';

        //包体信息
        if ($method == 'POST') {
            if(is_array($postfields)){
                $postfields = http_build_query($postfields);
            }
            $headers[] = "Content-type: application/x-www-form-urlencoded";
            $headers[] = 'Content-Length: '.strlen($postfields);
            $out = "POST $path HTTP/$version\r\n".join("\r\n", $headers)."\r\n\r\n".$postfields;
        } else {
            $out = "GET $path HTTP/$version\r\n".join("\r\n", $headers)."\r\n\r\n";
        }
        //发送请求
        $limit = 0;
        $fp = fsockopen($host, $port, $errno, $errstr, 30);

        if (!$fp) {
            exit('Failed to establish socket connection: '.$url);
        } else {
            fputs($fp, $out);

            //实现异步把下面去掉
//             $receive = '';
//             while (!feof($fp)) {
//             $receive .= fgets($fp, 128);
//             }
//             echo "<br />".$receive;
            //实现异步把上面去掉
            fclose($fp);

        }
    }

}