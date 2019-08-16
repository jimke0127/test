<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use common\models\ProductClass;
use yii\data\Pagination;
use common\Custom;
use frontend\controllers\CommonController;
use common\models\Trademark;
use common\models\Download;
use common\models\DownloadDetail;
use common\models\Search;

/**
 * @copyright bill.zhong
 */
class TempController extends CommonController {

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function sandSns() {

        $title_array = array(
            'a' => 'phone',
        );

        $file_path = '../upload/xlsx_temp/phone.xlsx';

        $xlsArr = Custom::excelToArray($file_path, $title_array, '1');

        foreach ($xlsArr as $val) {

            //队列
            $params = array(
                'phone' => $val['a'],
                'templete' => '',
                'content' => '安牌网老用户：由于公司产品2.0版本2-18日上线迭代需要，1.0版只保留账号信息，所出售商标资源请移步2.0版重新上传；全新升级版，让交易更简单！',
                'extend' => [],
            );

            Yii::$app->queue1->push(new \common\job\SmsJob($params));

        }
    }
    /**
     * @inheritdoc
     */
    public function actionIndex() {
//
//        die();
//
//        $this->sandSns();
//
//        die();
        echo(1);
        $model = new Search;
        $model->ID = 1;
        $model->TMCN           = 'ss';
        $model->RegNO          = 'ss';
        $model->TMEN           = 'ss';
        $model->TMTy           = 'ss';
        $model->TMDy           = 'ss';
        $model->SBMC           = 'ss';
        $model->ISPRO          = 1;
        $model->SimilarGroup   = 'ss';
        $model->IntCls         = '01';
        $ret = $model->save();
        print_r($ret);
        
        $query = Search::find(); 
        
//        $condition = '时光'; 

        $query->where($condition);

        $query->andWhere(['and', ['IntCls' => '43']]);
        
        $query->andWhere(['and', ['TMCN' => '不二']]);
        
        $count = $query->count();

        print_r($count);
        
        $pagination = new Pagination([
            'pageSize' => 100,
            'totalCount' => $count,
        ]);
            
        $list = $query->offset($pagination->offset)
                    ->limit($pagination->limit)
                    ->asArray()
                    ->all();

        print_r($list);
        
        die();

        list($usec, $sec) = explode(" ", microtime());

        $str = date('YmdHis', $sec) . sprintf('%03d', $usec * 1000) . sprintf('%04d', mt_rand(0, 9999));

        echo($this->random(20, 1));
        die();
        $mail = Yii::$app->mailer;

        $mail = $mail->compose(
                ['html' => 'register'], ['data' => ['username' => '']]
        );

        $mail->setTo('coolzbw@qq.com');

        $mail->setSubject('安牌网');

        $mail->send();
        echo('ok');
        die();
        $list = (new \common\models\Product())->getProductList(['tm_state' => 1]);

        foreach ($list as $val) {
            $ret = (new Trademark())->updateTrademark(['ISPRO' => 1], ['RegNO' => $val['reg_no']]);
            print_r($ret);
        }

        die();
        $year = 2;
        $b = date('Y-m-d H:i:s', time());
//            print_r($b);

        $a = date('Y-m-d H:i:s', strtotime("$b-" . $year . "year"));

        print_r($a);
        // $query->andWhere(['and', ['<', 'reg_date', strtotime("$dt-".$year."year")]]);

        die();
        print_r(Yii::$app->user->identity->customers_id);

        die();

        $images = \common\models\TrademarkImage::find()->where(['RegNO' => '10001992'])->one();

        $response = Yii::$app->getResponse();

        $response->headers->set('Content-Type', 'image/jpeg');

        $response->format = Response::FORMAT_RAW;

        return $images['TMImage'];
    }

    /**
     * @inheritdoc
     */
    public function actionTemp() {

        //$images = Yii::$app->db3->createCommand("SELECT * FROM `lctmimage` WHERE RegNO='5997159'")->queryAll();
        //$a = file_get_contents('http://b2b.kn.jipi.cc/public/upload/mall/avatar/avatar_2.jpg');

        $images = \common\models\TrademarkImage::find()->where(['RegNO' => '7632081'])->one();

        $file_path = '../upload/img/a.png';


        // $filePath = \Yii::getAlias('@webroot/img/' . $path . '/' .$filename);
        $tp = @fopen($file_path, 'a');
        fwrite($tp, $images['TMImage']);
        fclose($tp);

        die();


        //echo ($data);
//        $im = imagecreatefromstring(base64_encode($images['TMImage']));
//        if($im != false){
//            echo '<p>图片正常...</p>';
//        }else{
//            echo '<p>图片已损坏...</p>';
//        }
//            die();
//        $response = Yii::$app->getResponse();
//        $response->headers->set('Content-Type', 'image/jpeg');
        //header('Content-type: image/jpg');base64_decode($image)
        ob_clean();
        echo(2);
        echo('<img src="data:image/jpg;base64,' . base64_encode($images['TMImage']) . '"/>');
        die();

//        $response->format = Response::FORMAT_RAW;
//        return $images['TMImage'];
//        $sms = new \common\api\sms\Sms(Yii::$app->params['sms_app_key'], Yii::$app->params['sms_secret'], Yii::$app->params['sms_sign']);
//        
//        $res = $sms->sendContent('18926767229', 'test');
//
//        print_r($res);
    }

    /**
     * @return string
     */
    public function actionEmail() {

        //队列
        $data = [
            'extend' => [
                'username' => 'bill',
            ],
            'templete' => 'register', //模板
            'email' => '609008@qq.com', //邮箱
            'subject' => '注册成功', //标题
        ];

        Yii::$app->queue2->push(new \common\job\EmailJob($data));
        die('ok');
    }

    /**
     * @return string
     */
    public function actionSms() {

        //队列
        $params = array(
            'phone' => '18926767229',
            'templete' => '',
            'content' => '队列测试',
            'extend' => [],
        );

        Yii::$app->queue1->push(new \common\job\SmsJob($params));
        die('ok');
    }

    /**
     * @return string
     */
    public function actionTemp1() {

        return $this->render('temp1', ['model' => '']);
    }

    /**
     * @return string
     */
    public function actionTemp2() {
        $this->layout = '@app/views/layouts/null.php';
        return $this->render('temp2', ['model' => '']);
    }

    /**
     * @return string
     */
    public function actionTemp3() {

        return $this->render('temp3', ['model' => '']);
    }

    /**
     * 取得随机数
     *
     * @param int $length 生成随机数的长度
     * @param int $numeric 是否只产生数字随机数 1是0否
     * @return string
     */
    function random($length, $numeric = 0) {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        $hash = '';
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed{mt_rand(0, $max)};
        }
        return $hash;
    }

    /**
     * @inheritdoc
     */
    public function actionImport() {

        $mode_class = new ProductClass();

        $title_array = array(
            'a' => Yii::t('buy', '类别'),
            'b' => Yii::t('buy', '群组'),
        );

        $file_path = '../upload/xlsx_temp/test.xlsx';

        $xlsArr = Custom::excelToArray($file_path, $title_array, '2');

        foreach ($xlsArr as $val) {

            $insert = $mode_class->updateProductClass(['similar_group' => $val['b']], ['int_cls' => intval($val['a'])]);

            print_r($insert);
        }
    }

    /**
     * @inheritdoc
     */
    public function actionExport() {

        $title_array = array(
            array(
                'tm_image' => array(
                    'title' => Yii::t('common', '商标图片'),
                    'width' => 15
                ),
                'tm_cn' => array(
                    'title' => Yii::t('common', '中文名称'),
                    'width' => 20
                ),
                'TMEN' => array(
                    'title' => Yii::t('common', '英文名称'),
                    'width' => 20
                ),
                'int_cls' => array(
                    'title' => Yii::t('common', '商标分类'),
                    'width' => 15
                ),
                'reg_no' => array(
                    'title' => Yii::t('common', '注册号'),
                    'width' => 15
                ),
                'RegDate' => array(
                    'title' => Yii::t('common', '注册日期'),
                    'width' => 15
                ),
                'TMDetail' => array(
                    'title' => Yii::t('common', '使用商品'),
                    'width' => 50
                ),
                'SimilarGroup' => array(
                    'title' => Yii::t('common', '保护群组'),
                    'width' => 50
                ),
                'price' => array(
                    'title' => Yii::t('common', '价格'),
                    'width' => 15
                ),
            )
        );

        $condition = array(
            'download_id' => 6,
        );

        $datas = (new DownloadDetail())->getDownloadDetailList($condition);

        if (empty($datas)) {
            return;
        }

        foreach ($datas as $val) {

            $images = $this->_save_trademark_image($val['reg_no']);

            $tmtab = Trademark::find()->where(['RegNO' => $val['reg_no']])->asArray()->one();

            $dataArr[] = array(
                'tm_image' => $images,
                'tm_cn' => $val['tm_cn'],
                'TMEN' => $tmtab['TMEN'],
                'int_cls' => $val['int_cls'],
                'reg_no' => $val['reg_no'],
                'RegDate' => $tmtab['RegDate'],
                'TMDetail' => $tmtab['TMDetail'],
                'SimilarGroup' => $tmtab['SimilarGroup'],
                'price' => $val['price'],
            );
        }

        Custom::exportExcelIamgs(array($dataArr), $title_array, 'download.xls');
    }

    /**
     * @inheritdoc
     */
    private function _save_trademark_image($reg_no = '') {

        $images = \common\models\TrademarkImage::find()->where(['RegNO' => $reg_no])->asArray()->one();

        if (empty($images)) {
            return false;
        }

        $files = '../upload/img/' . $reg_no . '.png';

        if (!file_exists($files)) {

            $tp = @fopen($files, 'a');

            fwrite($tp, $images['TMImage']);

            fclose($tp);
        }

        return $files;
    }

}
