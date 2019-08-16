<?php

namespace frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use common\models\Trademark;
use common\models\ProductClass;
use yii\data\Pagination;
use frontend\controllers\CommonController;
use frontend\models\product\SearchSearch;
use frontend\models\product\QuerySearch;
use frontend\models\product\QuerySimilar;
use frontend\models\product\QueryTrial;
use frontend\models\product\IndexIndex;

/**
 * Class HomeController
 *
 * @package frontend\controllers
 */
class ProductController extends CommonController {

    public $enableCsrfValidation = true;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'search', 'detail','hunter', 'add', 'save', 'query', 'init-data','init-data-query', 
                            'init-data-query-trial','init-data-query-similar','export','notice-img'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['buy-add'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'search' => ['get'],
                    'init-data' => ['get'],
                    'init-data' => ['get'],
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex() {

        return $this->render('index', ['model' => '']);
    }

    /**
     * @return string
     */
    public function actionQuery() {

        $class = ProductClass::find()->asArray()->all();
        
        return $this->render('query', ['class' => $class,'customers_type'=>Yii::$app->user->identity->customers_type]);
    }

    /**
     * @return string
     */
    public function actionHunter() {

        return $this->render('hunter', []);
    }

    /**
     * @return string
     */
    public function actionSearch() {

        $class = ProductClass::find()->asArray()->all();

        $query = Trademark::find();

        $count = $query->count();
        
        return $this->render('search', ['count' => intval($count) > 3000 ? 3000 : intval($count), 'class' => $class,'customers_type'=>Yii::$app->user->identity->customers_type]);
    }

	/**
     * @return string
     */
    public function actionDetail() {
        
        $this->layout = '@app/views/layouts/null.php';
        
        $model = new IndexIndex();

        $ret = $model->view();

        return $this->render('detail', ['info' => $ret['data']]);
    }


    /**
     * @return string
     */
    public function actionAdd() {

        $model = new AddForm();

        $this->layout = '@app/views/layouts/null.php';

        $ret = $model->view();

        return $this->render('search-add', ['info' => $ret['data']]);
    }

    /**
     * @return string
     */
    public function actionSave() {

        $model = new SaveForm();

        if (Yii::$app->request->isPost) {

            $ret = $model->saves();

            Yii::$app->response->format = Response::FORMAT_JSON;

            return $ret;
        }
    }

    /**
     * @inheritdoc
     */
    public function actionInitData() {

        $model = new SearchSearch();

        $ret = $model->search(Yii::$app->request->queryParams);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function actionInitDataQuery() {

        $model = new QuerySearch();

        $ret = $model->search(Yii::$app->request->queryParams);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $ret;
    }

    /**
     * @inheritdoc
     */
    public function actionInitDataQuerySimilar() {

        $model = new QuerySimilar();

        $ret = $model->search(Yii::$app->request->queryParams);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $ret;
    }
    /**
     * @inheritdoc
     */
    public function actionInitDataQueryTrial() {

        $model = new QueryTrial();

        $ret = $model->search(Yii::$app->request->queryParams);

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $ret;
    }
    public function actionExport() {
        $regNos = Yii::$app->request->get('regno');
        $regNos = explode(',', $regNos);
        $regList = [];
        $i = 0;
        foreach($regNos as $v){
            $reg = explode('_', $v);
            $regList[$i]['regno'] = $reg[0];
            $regList[$i]['intcls'] = (int)$reg[1];
            $i++;
        }
        //导出选中商标
        $tm = new \frontend\models\product\Tm();
        $tm->exportByRegnoAndIntcls($regList);
//        $query = Trademark::find();
//        $condition = [];
//        $query->where($condition);
//        if($regNos){
//            $regNoList = explode(',',substr($regNos, 0,-1));
//            $query->andWhere(['in', 'RegNO',$regNoList]);
//        }
//        $count = $query->count();
//        $pagination = new Pagination([
//            'pageSize' => 1000,
//            'totalCount' => $count,
//        ]);
//
//        $page = Yii::$app->request->get('page',0);
//        $pagination->setPage($page);
//        $datas = $query->orderBy('IntCls')
//                ->select(['RegNO','TMCN','TMEN','IntCls','RegDate','TMDetail','SimilarGroup'])
//                ->offset($pagination->offset)
//                ->limit($pagination->limit)
//                ->asArray()
//                ->all();
    }
    public function actionNoticeImg() {
        $this->layout = '@app/views/layouts/null.php';
        $trialnum = Yii::$app->request->get('trialnum');
        $pagenum = Yii::$app->request->get('pagenum');
        $tm = new \frontend\models\product\Tm();
        $res = $tm->getNoticeImage(['trialnum'=>$trialnum,'pagenum'=>$pagenum]);
        $image = $res ? $res['data'] : '';
        return $this->render('notice_image', ['image' => $image]);
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
