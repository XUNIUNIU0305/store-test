<?php
namespace business\modules\site\models;

use business\models\parts\Role;
use common\ActiveRecord\CustomUserAuthorizationAR;
use common\components\handler\PromoterHandler;
use common\ActiveRecord\PartnerPromoterAR;
use common\models\Model;
use common\models\parts\partner\Authorization;
use common\models\parts\partner\UrlParamCrypt;
use Yii;
use business\models\parts\Promoter;
use dosamigos\qrcode\QrCode;
use PHPImageWorkshop\ImageWorkshop;


class PromoterModel extends Model
{
    const SCE_LIST = 'get_list';
    const SCE_ADD = 'add';
    const SCE_DELETE = 'delete';
    const SCE_DOWNLOAD = 'download';
    const SCE_UPDATE = 'update';
    const SCE_INVITE_LOG = 'invite_log';
    const SCE_STREAM_LOG = 'stream_log';
    const SCE_STATUS_COUNT = 'status_count';
    const SCE_STREAM_COUNT = 'stream_count';
    const SCE_CODE_TITLE = 'code_title';

    public $id;
    public $current_page;
    public $page_size;
    public $title;
    public $status;//数组（账号生效[5] ,待审核[2]，待递交[1,3]）
    public $search_text;//


    //搜索条件
    public $mobile;//手机号
    public $account;//门店账号
    public $pay_time;//付款时间
    public $auth_time;//过申时间
    public $valid_time;//生效时间

    public $promoter_id;//邀请码id

    public function scenarios()
    {
        return  [
            self::SCE_LIST =>['current_page','page_size'],
            self::SCE_ADD =>[],
            self::SCE_DELETE =>['id'],
            self::SCE_DOWNLOAD =>['id'],
            self::SCE_UPDATE =>['id','title'],
            self::SCE_INVITE_LOG =>['id','current_page','page_size','status','search_text','mobile','account','pay_time','auth_time','valid_time'],
            self::SCE_STREAM_LOG =>['current_page','page_size','promoter_id','status','mobile','account','pay_time','auth_time','valid_time'],
            self::SCE_STATUS_COUNT => [],
            self::SCE_STREAM_COUNT => [],
            self::SCE_CODE_TITLE => ['id'],

        ];
    }

    public function rules()
    {
        return [
            [
                ['current_page'],
                'default',
                'value'=>1
            ],
            [
                ['page_size'],
                'default',
                'value'=>10
            ],
            [
                ['id','title','current_page','page_size'],
                'required',
                'message'=>9001,
            ],
            [
                ['id'],
                'exist',
                'targetClass'=>PartnerPromoterAR::className(),
                'targetAttribute'=>['id'=>'id'],
                'message'=>13341,
            ],
            [
                ['title'],
                'string',
                'length' => [
                    1,
                    10
                ],
                'tooShort' => 13344,
                'tooLong' => 13343,
                'message' => 5099,
            ],
            [
                ['status'],
                'common\validators\business\StatusValidator',
                'message'=>13346,//'审核状态 1 已支付 2 申请审核 3 审核失败 4 审核成功 5 账号生效',
            ],
        ];
    }


    /**
     *====================================================
     * 新增
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function add(){
        if (self::checkAccess() === false)  return false;
        $data = [
            'type'=>1,//business用户
            'business_user_id'=>Yii::$app->user->id,
        ];
        if(PromoterHandler::create($data) != false){
            return true;
        }
        $this->addError('create',13342);
        return false;

    }

    /**
     *====================================================
     * 更新
     * @return bool
     * @author shuang.li
     *====================================================
     */
    public function update(){
        if (self::checkAccess() === false)  return false;
        $model = new Promoter(['id'=>$this->id]);
        if ($model->setTitle($this->title) !==false){
            return true;
        }
        $this->addError('update',13345);
        return false;
    }


    /**
     *====================================================
     * 禁用
     * @author shuang.li
     * @return bool
     *====================================================
     */
    public function delete(){
        if (self::checkAccess() === false)  return false;
        $model = new Promoter(['id'=>$this->id]);
        if ($model->setAvailable(0) !== false){
            return true;
        }
        $this->addError('delete',13340);
        return false;
    }

    /**
     *====================================================
     * 获取生成二维码列表
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function getList(){
        if (self::checkAccess() === false)  return false;
        $provides = Yii::$app->BusinessUser->account->getQrcode($this->current_page,$this->page_size);
        return [
            'count'=>$provides->count,
            'total_count'=>$provides->totalCount,
            'data'=>$provides->models,
        ];
    }

    /**
     *====================================================
     * 下载二维码
     * @author shuang.li
     *====================================================
     */
    public function getQrCode(){
        if (self::checkAccess() === false)  return false;
        $encryptModel = new UrlParamCrypt();
        if(!$this->validate()) return false;
        ob_start();
        QrCode::encode(Yii::$app->params['ADMIN_Hostname'].'/partner?q='.$encryptModel->encrypt($this->id),false,0,15,1);
        $a = ob_get_contents();
        ob_clean();
        $imageStr = imagecreatefromstring($a);
        $downImage = Yii::getAlias('@app/web/images/down_code.png');
        $norwayLayer = ImageWorkshop::initFromPath($downImage);
        $watermarkLayer = ImageWorkshop::initFromResourceVar($imageStr);

        $fontPath = Yii::getAlias('@common/assets/font/simhei.ttf');
        $textId = '序列号：'.$this->id;
        $textTitle = '运营商信息：'.$this->codeTitle()['invite_person']."\n";
        $textRemark = $this->codeTitle()['title'] ;
        $fontSize = 25;
        $fontColor = "0F0F0F";
        $textRotation = 0;
        $backgroundColor = "FCFCFC";
        $textId = ImageWorkshop::initTextLayer($textId,$fontPath,$fontSize,$fontColor,$textRotation,$backgroundColor);
        $textTitle = ImageWorkshop::initTextLayer($textTitle,$fontPath,$fontSize,$fontColor,$textRotation,$backgroundColor);

        //lb（左下） rb  rt(右顶)
        $norwayLayer->addLayerOnTop($watermarkLayer, 10, 10,'mm');
        $norwayLayer->addLayerOnTop($textId, 10, 300,'mm');
        $norwayLayer->addLayerOnTop($textTitle, 10, 350,'mm');
        if($textRemark){
            $textRemark = ImageWorkshop::initTextLayer($textRemark,$fontPath,$fontSize,$fontColor,$textRotation,$backgroundColor);
            $norwayLayer->addLayerOnTop($textRemark, 10, 400,'mm');
        }

        $image = $norwayLayer->getResult();
        imagejpeg($image, null, 95);
        imagedestroy($imageStr);
        imagedestroy($image);
     }


    /**
     *====================================================
     * 邀请纪录
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function inviteLog(){
        if (self::checkAccess() === false)  return false;
        $model = new Promoter(['id'=>$this->id]);
        $where = $this->searchCondition();
        if(!empty($this->search_text)){
            $where .= ' and p.mobile = '.$this->search_text .'  or r.account = '.$this->search_text;
        }
        $inviteLog = $model->getInviteLog($this->current_page,$this->page_size,$where,$this->status);
        return [
            'count'=>$inviteLog->count,
            'total_count'=>$inviteLog->totalCount,
            'data'=>$inviteLog->models,
        ];

    }

    /**
     *====================================================
     * 流水纪录
     * @author shuang.li
     *====================================================
     */
    public function streamLog(){
        if (self::checkAccess() === false)  return false;
        $where = $this->searchCondition();
        if (!empty($this->promoter_id)){
            $where .= ' and p.partner_promoter_id = '.$this->promoter_id;
        }
        $streamLog = Yii::$app->BusinessUser->account->getStreamLog($this->current_page,$this->page_size,$where,$this->status);
        return [
            'count'=>$streamLog->count,
            'total_count'=>$streamLog->totalCount,
            'data'=>$streamLog->models,
        ];
    }


    protected function searchCondition(){
        $where = '';
        //注册成功搜索
        if (!empty($this->mobile)){
            $where .= ' and p.mobile = '.$this->mobile;
        }

        if (!empty($this->account)){
            $where .= ' and r.account = '.$this->account;
        }

        if (!empty($this->pay_time) &&  $this->pay_time != ' '){
            list($startTime, $endTime) = explode(' ',$this->pay_time);
            if ($startTime) {
                $where .= ' and p.pay_unixtime >= ' .strtotime($startTime);
            }
            if($endTime){
                $where .= ' and p.pay_unixtime <= ' .strtotime($endTime.'23:59:59');
            }

        }


        if (!empty($this->auth_time) &&  $this->auth_time != ' '){

            list($startTime, $endTime) = explode(' ',$this->auth_time);
            $whereTime = '';
            if ($startTime) {
                $whereTime .= ' and authorized_unixtime >= ' .strtotime($startTime);
            }
            if($endTime){
                $whereTime .= ' and authorized_unixtime>0  and authorized_unixtime <= ' .strtotime($endTime.'23:59:59');
            }
            if($applyArr = $this->getPromoterIdByTime($whereTime)){
                $applyId = implode(',',$applyArr);
                $where .= " and p.id in ($applyId) ";
            }else{
                $where .= " and p.id = 0 ";
            }

        }

        if (!empty($this->valid_time) &&  $this->valid_time != ' '){
            list($startTime, $endTime) = explode(' ',$this->valid_time);
            $whereTime = '';
            if ($startTime) {
                $whereTime .= ' and account_valid_unixtime >= ' .strtotime($startTime);
            }
            if($endTime){
                $whereTime .= ' and account_valid_unixtime > 0 and account_valid_unixtime <= ' .strtotime($endTime.'23:59:59');
            }

            if($applyArr = $this->getPromoterIdByTime($whereTime)){
                $applyId = implode(',',$applyArr);
                $where .= " and p.id in ($applyId) ";
            }else{
                $where .= " and p.id = 0 ";
            }
        }
        return $where;
    }

    /**
     *====================================================
     * 获取不同状态下的数量
     * @author shuang.li
     *====================================================
     */
    public function statusCount(){
        if (self::checkAccess() === false)  return false;
        $promoterId = Yii::$app->BusinessUser->account->promoterId;
        $applyCodeCount = Yii::$app->RQ->AR(new PartnerPromoterAR())->count([
            'where'=>[
                'business_user_id'=>Yii::$app->user->id,
                'is_available'=>1
            ]]);
        $count = array_column(PromoterHandler::statusCount(),'count','status');
        return [
            'apply_code'=>$applyCodeCount,
            'success_register'=>$count[Authorization::STATUS_ACCOUNT_VALID] ?? 0,
            'wait_auth'=>$count[Authorization::STATUS_AUTHORIZE_APPLY] ?? 0,
            'wait_submit'=>PromoterHandler::waitCount($promoterId) ?? 0,
            'amount'=>PromoterHandler::getAmount() ?? 0,
        ];
    }


    /**
     *====================================================
     * 流水纪录 数量统计
     * @return mixed
     * @author shuang.li
     *====================================================
     */
    public function streamCount(){
        if (self::checkAccess() === false)  return false;
        $promoterId = Yii::$app->BusinessUser->account->getPromoterId();
        $streamCount =  PromoterHandler::getStreamCount($promoterId);
        $streamCount['rmb'] = PromoterHandler::getAmount() ?? 0;
        return $streamCount;

    }

    /**
     *====================================================
     * 获取二维码信息
     * @return array
     * @author shuang.li
     *====================================================
     */
    public function codeTitle(){
        if (self::checkAccess() === false)  return false;
        $model = new Promoter(['id'=>$this->id]);
        return [
            'title'=>$model->title,
            'invite_person'=>$model->account->area->name,
        ];
    }

    private function checkAccess(){
        return true;
    }

    private function getPromoterIdByTime($whereTime){
        return   Yii::$app->RQ->AR(new CustomUserAuthorizationAR())->column([
            'select'=>['partner_apply_id'],
            'where'=> 'promoter_type = 1 and promoter_user_id = '.Yii::$app->user->id .$whereTime,
        ]);
     }

}
