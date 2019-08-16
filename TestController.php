<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use frontend\controllers\CommonController;
use common\extensions\Security;
use common\Restclient;
use common\models\Product;
use common\models\Trademark;
use common\models\Area;
use abei2017\wx\Application;
use common\extensions\QRcode;
use common\models\TradeContent;
use common\models\TradeFgroup;

/**
 * Class HomeController
 *
 * @package frontend\controllers
 */
class TestController extends CommonController {

    public function actionIndex() {
        $url = 'https://zxgztest.fxnotary.com/onlineNotary/api/common/register';
        $str = json_encode(array('mobile' => '18500149714'));
        $encodeData = Security::encrypt($str, 'Qg3HURiAv2AOYQ9v');
        $params = [
            'data' => $encodeData,
            'appId' => '8e3f481824a44a3784958e1d74a8b204'
        ];
        $result = \common\Custom::curlpost($url, [], $params);
        print_r(json_decode($result, true));
    }

    public function actionCm() {
        $url = 'https://zxgztest.fxnotary.com/onlineNotary/api/common/companyRegister';
        $str = json_encode(array(
            'enterpriseName' => '深圳市众方达管理咨询有限公司',
            'businessLicenseId' => '91440300319566487M',
            'legalPersonName' => '何忠政',
            'legalPersonMobile' => '18688938586',
            'legalPersonCardNo' => '420626198210270019',
            'contactsName' => '何忠政',
            'contactsPhone' => '18688938586',
        ));
        $encodeData = Security::encrypt($str, 'Qg3HURiAv2AOYQ9v');
        $params = [
            'data' => $encodeData,
            'appId' => '8e3f481824a44a3784958e1d74a8b204'
        ];
        $result = $this->pcurl($url, $params);
        print_r($result);
    }

    public function actionEvid() {
        $url = 'https://zxgztest.fxnotary.com/onlineNotary/api/common/uploadEvid';
        $path = Yii::getAlias('@upload/') . '222.jpg';
        $str = json_encode(array(
            'orderId' => 'Oddsdsdf67sd8fsd9f8',
            'evidName' => '商标注册证 ',
            'evidType' => '1'
        ));
        $encodeData = Security::encrypt($str, 'Qg3HURiAv2AOYQ9v');
        $params = [
            'data' => $encodeData,
            'appId' => '8e3f481824a44a3784958e1d74a8b204',
            'file' => new \CURLFile(realpath($path))
        ];
        $result = $this->pcurl($url, $params);
        print_r($result);
    }

    public function actionShenban() {
        $url = 'https://zxgztest.fxnotary.com/onlineNotary/api/common/startNotary';
        $str = json_encode(array(
            'orderId' => 'Oddsdsdf67sd8fsd9f8',
            'userId' => 'ff808081699e3b1001699f7cc0c300da',
            'extendType' => '1',
            'extendInfo' => json_encode([
                'name' => '陈曦',
                'sbzch' => '3465455',
                'sbdjl' => '2'
            ]),
            'callbackUrl' => 'http://anpai.miwoxun.com/frontend/home/index'
        ));
        $encodeData = Security::encrypt($str, 'Qg3HURiAv2AOYQ9v');
        $params = [
            'data' => $encodeData,
            'appId' => '8e3f481824a44a3784958e1d74a8b204'
        ];
        $result = $this->pcurl($url, $params);
        print_r($result);
    }

    public function actionQuery() {
        $url = 'https://zxgztest.fxnotary.com/onlineNotary/api/common/queryProgress';
        $str = json_encode(array('orderId' => '1554890741802312159'));
        $encodeData = Security::encrypt($str, 'Qg3HURiAv2AOYQ9v');
        $params = [
            'data' => $encodeData,
            'appId' => '8e3f481824a44a3784958e1d74a8b204'
        ];
        $result = $this->pcurl($url, $params);
        print_r(json_decode($result, true));
    }

    public function actionDown() {
        $url = 'https://zxgztest.fxnotary.com/onlineNotary/api/common/downloadNotary';
        $str = json_encode(array('orderId' => '1554890741802312159'));
        $encodeData = Security::encrypt($str, 'Qg3HURiAv2AOYQ9v');
        $params = [
            'data' => $encodeData,
            'appId' => '8e3f481824a44a3784958e1d74a8b204'
        ];
        $result = $this->pcurl($url, $params);
        //file_put_contents(Yii::getAlias('@upload/') . 'aaa.pdf', $result);
        //file_put_contents(Yii::getAlias('@upload/') . 'download.log', date('Y-m-d H:i:s') . print_r($result, true));
    }

    public function actionSend() {
        $url = 'https://zxgztest.fxnotary.com/onlineNotary/api/common/sendNotary';
        $str = json_encode(array(
            'orderId' => '1554890741802312159',
            'sendName' => '黄三',
            'sendPhone' => '18787879452',
            'sendPlace' => '广东省深圳市宝安区兴东社区130号'
        ));
        $encodeData = Security::encrypt($str, 'Qg3HURiAv2AOYQ9v');
        $params = [
            'data' => $encodeData,
            'appId' => '8e3f481824a44a3784958e1d74a8b204'
        ];
        $result = $this->pcurl($url, $params);
        print_r($result);
        //file_put_contents(Yii::getAlias('@upload/') . 'download.log', date('Y-m-d H:i:s') . print_r($result, true));
    }

    private function post($url, $params) {
        $obj = new Restclient;
        $obj->url = $url;
        $obj->params = $params;
        $obj->post();
        return json_decode($obj->response, true);
    }

    public function pcurl($url, $params) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        //curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($curl, CURLOPT_HEADER, false);
        //curl_setopt($curl, CURLOPT_HEADER, true);   如不输出json, 请打开这行代码，打印调试头部状态码。
        //状态码: 200 正常；400 URL无效；401 appCode错误； 403 次数用完； 500 API网管错误
        if (1 == strpos("$" . $url, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        $out_put = curl_exec($curl);
        curl_close($curl);
        return $out_put;
    }

    private function query($orderCode) {
        $url = Yii::$app->params['zxgz']['url'] . '/onlineNotary/api/common/downloadNotary';
        $data = json_encode(array('orderId' => $orderCode));
        $encodeData = \common\extensions\Security::encrypt($data, Yii::$app->params['zxgz']['key']);
        $params = [
            'data' => $encodeData,
            'appId' => Yii::$app->params['zxgz']['appId']
        ];
        $result = \common\Custom::curlpost($url, [], $params);
        return $result;
    }

    public function actionAutoDown() {
        //file_put_contents(Yii::getAlias('@upload/') . 'auto_jd.log', date('Y-m-d H:i:s') . print_r('AutoUpdate', true).PHP_EOL);
        $query = \common\models\GzOrders::find();
        $where = ['order_status' => 15, 'tm_notarization' => ''];
        $list = $query->select(['order_id', 'order_code'])->where($where)->asArray()->all();
        if ($list) {
            foreach ($list as $k => $v) {
                $result = $this->query($v['order_code']);
                $name = time() . substr(microtime(), 2, 5) . sprintf('%04d', rand(0, 9999)) . '.pdf';
                $tm_notarization = 'judge/' . $name;
                //file_put_contents(Yii::getAlias('@upload/') . $tm_notarization, $result);
                Yii::$app->db->createCommand()->update('ap_gz_orders', ['tm_notarization' => $tm_notarization], 'order_id=:id', ['id' => $v['order_id']])->execute();
            }
        }
        echo('success');
    }

    private function cb_coupon($giftId, $memberId) {
        $coupon_code = '';
        $model_gift = \common\models\GzGift::findOne($giftId);
        if ($model_gift) {
            $model = new \common\models\GzCoupon();
            $model->gift_id = $giftId;
            $model->member_id = $memberId;
            $model->available_qty = $model_gift->qty;
            $model->amount = $model_gift->parvalue;
            $model->end_date = strtotime("+" . $model_gift->expire . " year");
            $model->create_time = time();
            $model->coupon_code = $coupon_code;
            $model->coupon_type = 2;
            $model->save();
        }
    }

    function timediff($begin_time, $end_time) {
        if ($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        //计算天数
        $timediff = $endtime - $starttime;
        $days = ceil($timediff / 86400);
        $month = ceil($days / 30);
        return $month;
    }

    public function actionCh() {

        die;
        $where = [];
        $query = Product::find();
        $query->where($where);
        $types = '1,2';
        $arr_type = explode(',', $types);

        if ($arr_type && !empty($types)) {
            $or[] = 'and';
            $and[] = 'and';
            foreach ($arr_type as $val) {
                if (empty($val)) {
                    continue;
                }
                if (intval($val) == 1) {
                    $or[] = ['and', ['NOT', ['tm_cn' => '']], ['NOT', ['sbmc' => '图形']], ['tm_en' => '']];
                }
                if (intval($val) == 2) {
                    $and[] = ['and', ['tm_cn' => ''], ['NOT', ['tm_en' => '']], ['>', 'LENGTH(tm_en)', 2], ['tmdy' => ''], ['not', "sbmc='图形'"]];
                    $or[] = $and;
                }
                if (intval($val) == 3) {
                    $and[] = ['NOT', ['tm_en' => '']];
                    $and[] = ['NOT', ['tmdy' => '']];
                    $or[] = $and;
                }
                if (intval($val) == 4) {
                    $and[] = ['or', ['tm_cn' => ''], ['tm_cn' => '图形']];
                    $and[] = ['<', 'length(tm_en)', 3];
                    $and[] = ['<', 'length(tmdy)', 3];

                    $or[] = $and;
                }
            }

            $query->andWhere($or);
        }

        $list = $query->asArray()->all();
        print_r($query->createCommand()->rawSql);
        die;
        $res = strtotime('2019-03-20 12:12:30');
        print_r($res);
        die;
        $query = \common\models\GzOrders::find();
        $where = ['order_status' => 99, 'member_id' => 5];
        $list = $query->select(['order_id', 'order_code', 'member_id'])->where($where)->asArray()->all();
        if ($list) {
            foreach ($list as $k => $v) {
                $cbCount = \common\models\GzCoupon::find()->where(['member_id' => $v['member_id'], 'coupon_type' => 2])->count();
                $qe = ($cbCount + 1) * 5;
                $gzCount = \common\models\GzOrders::find()->where(['member_id' => $v['member_id'], 'order_status' => 99])->count();
                //每当公证订单满10次（已出证状态），则赠送100元面值一张优惠卷；
                if ($gzCount == $qe) {
                    $this->cb_coupon(20, $v['member_id']);
                }
            }
        }
        echo('success1');
        die;


        die;
        $str = ['attach/155601211585433931.jpg', 'attach/155591470727516599.jpg'];
        $res = json_encode($str);
        print_r($res);
        die;
        $order_info['order_code'] = '1553226532035756581111';
        $pay = \common\models\GzPayment::find()->select('pay_time')->where(['order_code' => $order_info['order_code']])->one();
        $pay_time = $pay && $pay->pay_time > 0 ? Yii::$app->formatter->asDate($pay->pay_time, 'php:Y-m-d H:i:s') : '';

        print_r($pay_time);
        die;
        $host = "https://ocridcard.market.alicloudapi.com";
        $path = "/idimages";
        $method = "POST";
        $appcode = "35a5482d228d48c383537d36df4c3527";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type" . ":" . "application/x-www-form-urlencoded; charset=UTF-8");
        $querys = "";
        $idFiles = 'http://anpai.miwoxun.com/upload/id_front.jpg';
        $bodys = 'image=' . $idFiles . '&idCardSide=front'; //图片 + 正反面参数 默认正面，背面请传back
        //或者base64
        //$bodys = 'image=data:image/jpeg;base64,......'.'&idCardSide=front';  //jpg图片base64 + 正反面参数 默认正面，背面请传back
        //$bodys = 'image=data:image/png;base64,......'.'&idCardSide=front';   //png图片base64 +  正反面参数 默认正面，背面请传back
        $url = $host . $path;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        //curl_setopt($curl, CURLOPT_HEADER, true);   如不输出json, 请打开这行代码，打印调试头部状态码。
        //状态码: 200 正常；400 URL无效；401 appCode错误； 403 次数用完； 500 API网管错误
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        $out_put = curl_exec($curl);
        print_r($out_put);
        die;
        set_time_limit(0);
        $regs = Product::find()->select('reg_no')->where(['and', ['between', 'price', 0.1, 999], ['tm_state' => 0]])->column();
        foreach ($regs as $reg_no) {
            $count = Product::find()->where(['reg_no' => $reg_no, 'tm_state' => 1])->count();
            if ($count == 0) {
                Trademark::updateAll(['ISPRO' => 0], ['RegNO' => $reg_no]);
            }
        }
        echo 'oklo';
    }

    public function actionCcc() {
        $arr = [
            ['name'=>'相同','val'=>'xt'],
            ['name'=>'换序','val'=>'hx'],
            ['name'=>'同音','val'=>'ty'],
            ['name'=>'包含','val'=>'bh']
        ];
        echo json_encode($arr);die;
    }

    public function getMonth($date) {
        $firstday = date("Y-m-01", strtotime($date));
        $lastday = date("Y-m-d", strtotime("$firstday +1 month -1 day"));
        return array('firstday'=>$firstday, 'lastday'=>$lastday);
    }

}
