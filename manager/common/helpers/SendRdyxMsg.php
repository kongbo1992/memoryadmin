<?php
namespace common\helpers;
use Common\tools\DistStorage;
/** 初步抽离
 * Class SendMsg
 * @package Common\Common
 */
class SendRdyxMsg{

    public $username_24 = "hlwjy24"; //教师24小时验证码用户名
    public $pwd_24 = "513504"; //教师24小时验证码密码
    public $url='http://www.youxinyun.com:3070/Platform_Http_Service/servlet/SendSms';//短信平台url
    public $username = "hlwjy";//教师普通营销类
    public $pwd = "402453";//教师普通营销类
    public $zp_username = "hlwjy52zhaopin";//招聘验证通知类
    public $zp_pwd = "350244";//招聘验证通知类
    //时间戳动态生成

    //获得当前的毫秒值，因为smsid不能为空，所以使用此数值
    public function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    public function build_post_data($mobile,$content,$type = 1){
        $timestamp = date('mdHis');
        $post_data = [
//            'UserName' => $this->username,
//            'Key' => md5($this->username.$this->pwd.$timestamp),
            'Timestemp' => $timestamp,
            'Mobiles' => $mobile,
            'Content' => urlencode($content),
            'CharSet' => "utf-8",
            'SchTime' => "",
            'Priority' => "5",
            'PackID' => "",
            'PacksID' => "",
            'ExpandNumber' => "",
            'SMSID' => $this->getMillisecond(),

        ];
        switch($type){
            case 1:
                $post_data['UserName'] = $this->username;
                $post_data['Key'] = md5($this->username.$this->pwd.$timestamp);
                break;
            case 2:
                $post_data['UserName'] = $this->username_24;
                $post_data['Key'] = md5($this->username_24.$this->pwd_24.$timestamp);
                break;
            case 3:
                $post_data['UserName'] = $this->zp_username;
                $post_data['Key'] = md5($this->zp_username.$this->zp_pwd.$timestamp);
                break;
            default :
                $post_data['UserName'] = $this->username;
                $post_data['Key'] = md5($this->username.$this->pwd.$timestamp);
                break;
        }
        $o='';
        foreach ($post_data as $k=>$v)
        {
            $o.= "$k=".$v."&";
        }
        $post_data=substr($o,0,-1);
        return  $post_data;
    }

    /** 发送请求获取返回
     * @param $curlPost
     * @param $url
     * @return mixed
     */
    public function post($curlPost,$url){
        $this_header = array("content-type: application/x-www-form-urlencoded;charset=UTF-8");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,$this_header);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }
    /*
     * 发送购买成功提醒短信
     * mobile
     * classname
     * gggroup
     */
    public function message_class($mobile,$content,$type = 1){
        if(!empty($mobile)){
            $post_data = $this -> build_post_data($mobile,$content,$type);
            $gets = $this->post($post_data, $this->url);
            return $gets;
        }else{
            return false;
        }
    }
    /**发送验证码
     * @param $mobile
     * @return array
     */
    public function send_code($mobile,$type = 1){
        $mobile_code = $this->random(4,1);
        $content = "【52招聘】您的验证码是：" . $mobile_code . "。请不要把验证码泄露给其他人。";
        $post_data = $this -> build_post_data($mobile,$content,$type);
        $gets = $this->post($post_data, $this->url);
        if($gets['result'] == 0){
            session('mobile', $mobile);
            session('mobile_code', $mobile_code);
            return ['code'=>200,'msg'=>"发送成功"];
        }else{
            return ['code'=>201,'msg'=>"发送失败"];
        }
    }

//    private function shuoming($result){
//        switch($result){
//            case -2001:return "内容中存在黑字典";break;
//            case -2002:return "号码中存在黑名单";break;
//            case -2004:return "用户名错误";break;
//            case -2005:return "密码错误";break;
//            case -2006:return "内容长度错";break;
//            case -2007:return "号码长度超出范围";break;
//            case -2008:return "余额为零";break;
//            case -2001:return "内容中存在黑字典";break;
//            case -2001:return "内容中存在黑字典";break;
//            case -2001:return "内容中存在黑字典";break;
//            case -2001:return "内容中存在黑字典";break;
//            case -2001:return "内容中存在黑字典";break;
//            case -2001:return "内容中存在黑字典";break;
//            case -2001:return "内容中存在黑字典";break;
//        }
//    }
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
}