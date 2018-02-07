<?php
namespace common\tools;
use Yii;
use yii\db\Exception;
/** 初步抽离
 * Class SendMsg
 * @package Common\Common
 */
class SendMsg{

    public $target = 'http://106.ihuyi.cn/webservice/sms.php?method=Submit'; //短信平台url
    public $account = 'cf_boenpx'; //短信平台账号
    public $password = 'boen321'; //短信平台密码

    /**发送验证码
     * @param $mobile
     * @return array
     */
    public function send_code($mobile){
        $mobile_code = $this->random(4,1);
        $post_data = $this -> build_post_data($mobile,"您的验证码是：" . $mobile_code . "。请不要把验证码泄露给其他人。");
        $gets = $this->xml_to_array($this->Post($post_data, $this->target));
        $this -> message_status_record($mobile,$gets['SubmitResult']['code'], $gets['SubmitResult']['msg'], 2, 'boenxingzhi');
        if ($gets['SubmitResult']['code'] == 2) {
            session('mobile', $mobile);
            session('mobile_code', $mobile_code);
            return ['code'=>200,'msg'=>$gets['SubmitResult']['msg']];
        }else{
            return ['code'=>201,'msg'=>$gets['SubmitResult']['msg']];
        }

    }
    /*
       * 记录短信验证码发送
       * mobile   手机号
       * status   发送状态  2 成功  其他为错误码
       * description 返回值描述
       * type   1 APP注册、找回 2 PC注册、找回 3 支付回调
       */
//    public function message_status_record($mobile,$status,$description,$type,$sign){
//        if($sign == 'boenxingzhi'){
//            $data = array(
//                'mobile' => $mobile,
//                'status' => $status,
//                'description' => $description,
//                'type' => $type,
//                'createtime' => date("Y-m-d H:i:s",time())
//            );
//            M("tb_identifying_code_log")->add($data);
//        }
//    }
    public function build_post_data($mobile,$content){
        $postfields = [
            'account' => $this->account,
            'password' => $this->password,
            'mobile' => $mobile,
            'content' => $content,
        ];
        return  http_build_query($postfields);
    }
    /** 发送请求获取返回
     * @param $curlPost
     * @param $url
     * @return mixed
     */
    public function post($curlPost,$url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    /** xml to array
     * @param $xml
     * @return mixed
     */
    public function xml_to_array($xml){
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if(preg_match_all($reg, $xml, $matches)){
            $count = count($matches[0]);
            for($i = 0; $i < $count; $i++){
                $subxml= $matches[2][$i];
                $key = $matches[1][$i];
                if(preg_match( $reg, $subxml )){
                    $arr[$key] = $this->xml_to_array( $subxml );
                }else{
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    /** 生成随机值
     * @param int $length
     * @param int $numeric
     * @return string
     */
    public function random($length = 6 , $numeric = 0) {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        if($numeric) {
            $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
        } else {
            $hash = '';
            $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
            $max = strlen($chars) - 1;
            for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
            }
        }
        return $hash;
    }

    /** 发送请求 不等待返回
     * @param $url
     * @param string $method
     * @param null $postfields
     * @param array $headers
     */
    public static function post_without_result($url, $method = 'GET', $postfields = NULL, $headers = array()) {
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

        if (!$fp) {//不做任何处理
//            exit('Failed to establish socket connection: '.$url);
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
    /*
       * 购课成功短信发送
       * mobile   手机号
       * classname   课程名称
       * qqgroup 学员群号
       */
    public function message_class($mobile,$content){
        if(!empty($mobile)){
            $post_data = $this -> build_post_data($mobile,$content);
            $gets = $this->xml_to_array($this->Post($post_data, $this->target));
            //$this -> message_status_record($mobile,$gets['SubmitResult']['code'], $gets['SubmitResult']['msg'], 3, 'boenxingzhi');
            return $gets;
        }else{
            return false;
        }
    }
    /*
       * 面试课程短信发送
       * mobile   手机号
       * classid   课程名称
       */
//    public function message_alert($classid,$mobile){
//        $redis = DistStorage::getRedisConn(1);
//        $classinfo = $redis->hgetall("CLASS_".$classid);
//        if($classinfo&&!empty($mobile)){
//            $post_data = $this -> build_post_data($mobile,"您报名的".$classinfo['classname']."已经支付成功。上课地址：".$classinfo['address'].",学员QQ群号：".$classinfo['qqgroup']."，请务必入群哦,感谢您对我爱教师网的支持与信任。");
//            $gets = $this->xml_to_array($this->Post($post_data, $this->target));
//            $this -> message_status_record($mobile,$gets['SubmitResult']['code'], $gets['SubmitResult']['msg'], 3, 'boenxingzhi');
//            return $gets;
//        }else{
//            return false;
//        }
//
//    }
    //购买成功发送短信:kongbo
//    public function message_alert1($aid,$mobile,$id){
//        $activityinfo = M("tb_activity")->where("id='".$aid."'")->find();
//        if($activityinfo&&!empty($mobile)){
//            $post_data = $this -> build_post_data($mobile,"您报名的".$activityinfo['title']."活动已成功。时间：".$activityinfo['activity_time'].",地点：".$activityinfo['place']."。");
//            $gets = $this->xml_to_array($this->Post($post_data, $this->target));
//            $this -> message_status_record($mobile,$gets['SubmitResult']['code'], $gets['SubmitResult']['msg'], 3, 'boenxingzhi');
//            //            记录短信发送情况
//            if($gets['SubmitResult']['msg']=="提交成功"){
//                $list=array('state'=>1);
//                M("tb_activity_z_order_items")->where("id=".$id)->save($list);
//            }else{
//                $list=array('state'=>2);
//                M("tb_activity_z_order_items")->where("id=".$id)->save($list);
//            }
//            return $gets;
//        }else{
//            return false;
//        }
//
//    }
    
    //购买成功发送短信:kongbo
//    public function message_alert2($aid,$mobile,$name,$id){
//        $activityinfo = M("tb_activity")->where("id='".$aid."'")->find();
//        if($activityinfo&&!empty($mobile)){
//            if($aid==22){
//                $data=" <中国教育三十人论坛>";
//                $post_data = $this -> build_post_data($mobile,"Hi,".$name."，您已报名成功！请于2016年12月16日9:00—21:00到北京北辰五洲皇冠国际酒店（朝阳区北四环中路8号）一层大厅，进行参会注册并领取相关资料，感谢您的参与！咨询电话:18600921259".$data);
//            }else{
//                $post_data = $this -> build_post_data($mobile,"您报名的".$classinfo['classname']."Hi,".$name."老师，您报名的".$activityinfo['title']."已成功！".$activityinfo['address']."咨询电话:".$activityinfo['mobile']."感谢您的参与！");
//            }
//            $gets = $this->xml_to_array($this->Post($post_data, $this->target));
//            $this -> message_status_record($mobile,$gets['SubmitResult']['code'], $gets['SubmitResult']['msg'], 3, 'boenxingzhi');
////            记录短信发送情况
//            if($gets['SubmitResult']['msg']=="提交成功"){
//                $list=array('state'=>1);
//                M("tb_activity_z_order_items")->where("id=".$id)->save($list);
//            }else{
//                $list=array('state'=>2);
//                M("tb_activity_z_order_items")->where("id=".$id)->save($list);
//            }
//            return $gets;
//        }else{
//            return false;
//        }
//
//    }

    /**
     * 发送短信给机构
     * 提醒有新的简历代查看
     */
//    public function send_notice_to_org($mobile){
//        $post_data = $this -> build_post_data($mobile,"【52招聘】您的发布的职位收到了新简历，请及时查阅，谢谢");
//        $gets = $this->xml_to_array($this->Post($post_data, $this->target));
//        if ($gets['SubmitResult']['code'] == 2) {
//            return ['code'=>200,'msg'=>$gets['SubmitResult']['msg']];
//        }else{
//            return ['code'=>201,'msg'=>$gets['SubmitResult']['msg']];
//        }
//    }
}