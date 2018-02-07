<?php
namespace common\helpers;
use Yii;
use yii\db\Exception;


define ("KD100_KEY","GthMBamY3781");
define ("KD100_CUSTOMER","F82A3DB03F1AA7C9D091A449402676F9");

define ("KD100_CALLBACK","http://www.52jiaoshi.com/index.php/Home/Thirdapi/kd100callback?callbackid=");

define ("KD_NEED_NOT_SUBSCRIBE", -523); //无需发货
define ("KD_UNSUBSCRIBED",		  -522); //未订阅,即未发货
define ("KD_SUBSCRIBED",		  -521); //已订阅
//0~7 是已经订阅之后，轮询状态，0在途中、1已揽收、2疑难、3已签收、4退签、5同城派送中、6退回 、7转单 可参见KD100文档
//KD_ABORT时要，人工干预
define ("KD_SHUTDOWN",		   	   520); //结束
define ("KD_ABORT_DEALED",   	   521); //终止，但已自动修正
define ("KD_ABORT",  			   522); //终止 
define ("KD_OLD_DATA",		   	   529); //老数据


/**
APP解析： 
-522  -521	未发货
0~7  0在途中、1已揽收、2疑难、3已签收、4退签、5同城派送中、6退回 、7转单
521 已终止
529 老数据，隐藏发货信息
*/

class Kuaidi100{ 
	
    /**
    *	回调轮询接口
    **/ 
    public static function callback(){
    
   		$param=$_POST['param'];
		//TODO: 存储日志
	 	try{
	 	
	 		if( empty($param ) || empty($_GET['callbackid'] )  ){
	 			throw new Exception("para error".json_encode($_POST).json_encode($_GET) );
	 		}
			//$param包含了文档指定的信息，...这里保存您的快递信息,$param的格式与订阅时指定的格式一致
			$data = json_decode($param,true);
			
			if(  !isset($data['status'] ) || !isset($data['lastResult']['state'] ) ){
	 			throw new Exception("parse error:".$param);
	 		}
	 		
	 		
			list($product_type, $product_id ) = explode( "__", $_GET['callbackid'] );
			
			$save_para = [
				"kd100_data"=> $param
			];
			
			$fahuostatus = -10000;
			
			//throw new Exception("test $product_type, $product_id" );
			if( $data['status'] == "polling" ){
					$fahuostatus = $data['lastResult']['state']; //KD100定义的 0~6 种状态
			}else if( $data['status'] == "shutdown"   ){  
					//$fahuostatus = KD_SHUTDOWN;//存疑
					$fahuostatus = $data['lastResult']['state']; 
			}else if( $data['status'] == "abort"   ){ 
				if( !empty($data['comNew'])  ){
					//$fahuostatus = KD_ABORT_DEALED;
				}else
					$fahuostatus = KD_ABORT; 
			} 
			
			
			if($fahuostatus != -10000){
				$save_para['fahuostatus'] = $fahuostatus ;
			}
			
			if( !empty($data['lastResult']['data'][0]) ){
				$save_para['kd100_latest'] = json_encode(  $data['lastResult']['data'][0]  );
			}
			
			if($fahuostatus  || !empty( $param ) ){
				if($product_type == "book"){ 
					$result = M('tb_g_books_order')->where( "id = '$product_id'" )->save( $save_para  );
				}else{
					$result = M('z_order')->where( "ID = '$product_id'" )->save( $save_para  );
				}
				if( $result === false ){ 
					throw new Exception("save db error".$param);
				}
			}else{ 
				throw new Exception("err fahuostatus ".$param );
			}
			echo  '{"result":"true",	"returnCode":"200","message":"成功"}';
			//要返回成功（格式与订阅时指定的格式一致），不返回成功就代表失败，没有这个30分钟以后会重推
		} catch(Exception $e) {
			//TODO: 存储异常日志
			echo  '{"result":"false",	"returnCode":"500","message":"失败'.$e->getMessage().'"}';
			//保存失败，返回失败信息，30分钟以后会重推
	    }
    }
    
   
    /**
    *	订阅接口
    **/
    public static function subscribe( $product_id, $product_type,$number,$company  =false ,$from =false,$to  =false){
	 	$post_data = array();
		$post_data["schema"] = 'json' ;
                $tb_data = array();
		//callbackurl请参考callback.php实现，key经常会变，请与快递100联系获取最新key
		//$param = '{"company":"yuantong", "number":"12345678","from":"广东深圳", "to":"北京朝阳", "key":"testkuaidi1031", "parameters":{"callbackurl":"http://www.yourdmain.com/kuaidi"}}';
		
		$param = [
			//"company"=>"yuantong", 
			"number"=> $number ,
			//"from"=>"广东深圳",
			//"to"=>"北京朝阳", 
			"key"=> KD100_KEY , 
			"parameters"=>[
				"callbackurl"=> KD100_CALLBACK."${product_type}__${product_id}",
				//"autoCom"=>"1",
			]
		];
		//exit(json_encode(array("result"=>false,"returnCode"=>201,"message"=>json_encode($param) )) );
		if( !empty( $company ) ) {
			$param['company']= $company;
		}else{
			$param['parameters']['autoCom']= "1";
		}
		
		if( $from ) $param['from']= $from;
		if( $to ) $param['to']= $to;
		
		$post_data["param"] = json_encode($param);
		$url='http://www.kuaidi100.com/poll';
                $result = self::post($url, $post_data);
                $dingyue_status = false;
		if ($result){
                    $json = json_decode($result,true);
                    if ($json){
                        if (isset($json["returnCode"]) && $json["returnCode"] == 200){
                            //将订阅成功记录写入数据库tb_kuaidi_subscribe_record
//                            $tb_data = array(
//                                'order_id' => $product_id,
//                                'order_type' => $product_type,
//                                'kuaidicode' => $number,
//                                'createtime' => date("Y-m-d H:i:s",time()),
//                            );
//                            M("tb_kuaidi_subscribe_record")->add($tb_data);
                            Yii::$app->db->createCommand()->insert('tb_kuaidi_subscribe_record', [
                                'order_id' => $product_id,
                                'order_type' => $product_type,
                                'kuaidicode' => $number,
                                'createtime' => date("Y-m-d H:i:s",time())
                            ])->execute();
                        }
                    }
                    $dingyue_status = $json;
                }
		//exit(json_encode(array("result"=>false,"returnCode"=>201,"message"=>"ris " )) );
		//注意：不能输出任何东西 
                //if($status !== false){
                    return $dingyue_status;
//                }else{
//                    exit();
//                }
    }
    
    /**
    	订阅接口
    	orderid 订单类型
    	ordertype 订单ID
    **/
    public static function subscribe_common($product_id,$product_type ="class"){
    	
    	if($product_type == "class" ){
			//$order = M("z_order")->where("id=$product_id")->find();
                        $order = \common\models\ZOrder::find()
                        ->where('id=:id',[
                            ':id'=>$product_id
                        ])
                        ->one();
			$number = $order["kuaidicode"];
			$company = $order["kuaiditype"]; 
		}else{
			//$order = M("tb_g_books_order")->where("id=$product_id")->find(); 
                        $order = \common\models\TbGBooksOrder::find()
                        ->where('id=:id',[
                            ':id'=>$product_id
                        ])
                        ->one();
			$number = $order["kuaidicode"];
                        $company = $order["kuaiditype"]; 
		}
                
        //exit(json_encode(array("result"=>false,"returnCode"=>201,"message"=>json_encode($order) )) ); 
                return self::subscribe( $product_id, $product_type,$number,$company);
                //return 222222;
    }
    
    
    public static function subscribe_confirm($product_id,$product_type ="class"){
			if($product_type == "book"){ 
				$r = M('tb_g_books_order')->where( "id = '$product_id' and fahuostatus < " .KD_SUBSCRIBED )
					->save( [ "fahuostatus"=> KD_SUBSCRIBED ] );
			}else{
				$r = M('z_order')->where( "ID = '$product_id' and fahuostatus < " .KD_SUBSCRIBED )
					->save( [ "fahuostatus"=> KD_SUBSCRIBED ] );//返回更新的行数
			}
			exit(json_encode( [
				"result"=>$r? true:false,
				"returnCode"=>$r? 200 : 201,
				"message"=>$r 
				]
				));
    }
    
    public static function fahuostatus_str($fahuostatus){
  
    	switch( $fahuostatus ){
    	case KD_NEED_NOT_SUBSCRIBE:return "无需发货";
    	case KD_UNSUBSCRIBED:	return "未订阅";
    	case KD_SUBSCRIBED:		return "已订阅";
    	case 0:return "在途中";
    	case 1:return "已揽收";
    	case 2:return "疑难";
    	case 3:return "已签收";
    	case 4:return "退签";
    	case 5:return "同城派送中";
    	case 6:return "退回";
    	case 7:return "转单";
    	case KD_SHUTDOWN:		return "结束";
    	case KD_ABORT_DEALED:	return "已自动修正";
    	case KD_ABORT:			return "终止";
    	}
    	return $fahuostatus;
    	
    }
    
    public static function test(){  
		self::subscribe("5588", "class",  "12345678");
    }
    
    public static function send($url, $data,$method='POST',$headers = []) {
		$postdata = http_build_query($data);
		
		if(strtoupper($method) =="GET"){
			$jointer = "";
			if( substr($url,-1) != '?' ){//如果末尾字符不是 '?' 则继续判断
				//如果有 '?' 则添加 '&' 否则添加 '?'
				$jointer = ((strpos($url, '?') !== false) ? '&' : '?');
			}
			$url .= $jointer.$postdata;
			$postdata = "";
		}
		
		$headerStr = "";
		if(!empty( $headers )) foreach($headers as $k => $v){
			$headerStr .= $k.":".$v."\r\n";
		}
		
		$options = array(
				'http' => array(
						'method' => $method,
						'header' =>$headerStr. 'Content-type:application/x-www-form-urlencoded',
						'content' => $postdata,
						'timeout' => 15 * 60 // 超时时间（单位:s）
				)
		);
		$context = stream_context_create($options);
		$result = @file_get_contents($url, false, $context);
		//echo " $method";
		return $result;
	}
	
	public static function post($url, $post_data){
		$o=""; 
		foreach ($post_data as $k=>$v)
		{
			$o.= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
		}

		$post_data=substr($o,0,-1);

		$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                        //屏蔽curl返回的输出
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $result = curl_exec($ch);		//返回提交结果，格式与指定的格式一致（result=true代表成功）
                        return $result;
	}
	public static function queryOrder($ordertype,$orderid,$kuaiditype,$orderno){
            $data = array(
                'customer' => KD100_CUSTOMER,
                'param' => json_encode(array('com' => $kuaiditype,"num" => $orderno)),
            );
            $data['sign'] = strtoupper(md5($data["param"].KD100_KEY.$data["customer"]));
            $url='http://www.kuaidi100.com/poll/query.do';
            $ret = self::post($url, $data);
            $result = null;
            if ($ret){
                $json = json_decode($ret,true);
                if ($json){
                    if (isset($json["data"])){
                        $result = array(
                            "status" => "shutdown",
                            "billstatus" => "check",
                            "message" => "",
                            "lastResult" => $json,
                        );
                        
                        //更新下数据库
                        $save_para = array(
                            'kd100_data' => json_encode($result),
                            'kd100_latest' => json_encode($json["data"][0]),
                        );
                        if($ordertype == "book"){ 
                            M('tb_g_books_order')->where( "id = $orderid" )->save( $save_para  );
                        }else{
                            M('z_order')->where( "ID = $orderid" )->save( $save_para  );
                        }
                    }
                }
            }
            return $result;
        }
}