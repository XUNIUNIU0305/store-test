<?php
/**
 * @var $this \yii\web\View
 */

$this->title = '递交审核信息';

$asset = \mobile\modules\membrane\assets\MembraneAsset::register($this);
$this->params = ['js' => 'js/auth.js','css'=>'css/auth.css'];
?>

<div class="new-account-submit">
	<div class="model" id="modelCon">
		<div class="content">
			<div class="title">
				九大爷金牌会员服务协议
			</div>
			<div class="main">
				<div>本协议是您与九大爷平台（简称“平台”，网址：www.9daye.com.cn）所有者创智汇（苏州）电子商务有限公司（以下简称为“创智汇电子商务”）之间就九大爷平台会员服务等相关事宜所订立的契约。请仔细阅读本注册协议，您点击“同意以下协议，提交”按钮后，本协议即构成对双方有约束力的法律文件。</div>
				<div>本站的各项电子服务的所有权和运作权归平台所有。用户同意所有注册协议条款并完成注册程序，才能成为平台的会员。用户确认本协议条款是处理双方权利义务的契约，始终有效，法律另有强制性规定或双方另有特别约定的，依其规定。</div>
				<div>用户点击同意本协议的，即视为用户确认自己具有享受本站服务、下单购物等相应的权利能力和行为能力，能够独立承担法律责任。</div>

				<h4>一、会员等级及有效期</h4>

				<div>1、会员等级：会员。</div>

				<div>2、有效期：会员有效期为1年，自审核通过之日起365天后自行失效，续费后可继续使用会员权益。</div>

				<h4>二、费用及启用条件</h4>

				<div>1、注册时支付的1元用于审核流程，如资质不符合条件将予以返还；</div>

				<div>2、完成在线注册并通过相关资料审核。注册使用的资料须真实有效，包括：企业法人身份证、企业营业执照或具有门头为背景的门店名片。</div>

				<h4>三、会员权益</h4>

				<div>1、注册成功即赠送300元九大爷平台现金券一张及1.3升N+洗车液一桶（可洗1200台车），实物由工作人员送至门店交付；</div>

				<div>2、会员推荐收益权：会员通过“推荐二维码”推荐其他单位完成注册，可获1.3升N+洗车液一桶，推荐次数不设上限。经推荐加入的新会员享有九大爷平台会员相同权益。</div>

				<div>3、九大爷平台产品采购权，厂家批发价直邮到店。</div>

				<div>4、年均100种优势产品信息推送。</div>

				<div>5、提供多种线下线上免费培训，如“帮销季”，帮助门店实现产品销售和管理提升。</div>

				<h4>四、平台权限</h4>

				<div>平台在未登录状态下仅显示商品市场指导价，会员登陆平台后，可见实际采购价格。</div>

				<h4>五、会员资格的取消</h4>

				<div>如发现任何会员有以下故意行为之一，九大爷平台保留取消其使用服务的权利，并无需做出任何补偿；</div>

				<div>1、可能造成本平台全部或局部的服务受影响，或危害本平台运行；</div>

				<div>2、以任何欺诈行为获得会员资格；</div>

				<div>3、在本平台内从事非法商业行为，发布涉及敏感政治、宗教、色情或其它违反有关国家法律和政府法规的文字、图片等信息；</div>

				<div>4、以任何非法目的而使用网络服务系统；</div>

				<h4>六、平台的权利</h4>

				<div>1、有权撤销或停止会员的全部或部分服务内容；</div>

				<div>2、在有效期内保证会员既定权益的前提下，有权修订会员的其他权利和义务，有权修改或调整本平台的服务内容；</div>

				<div>3、有权将修订的会员的权利和义务以e-mail或短信形式通知会员，会员收到通知后仍继续使用本平台服务者即表示会员同意并遵守新修订内容；</div>

				<div>4、本平台提供的服务仅供会员独立使用，未经平台授权，会员不得将会员号授予或转移给第三方。会员如果有违此例，本平台有权向客户追索商业损失并保留追究法律责任的权利；</div>

				<h4>七、平台的义务</h4>

				<div>1、认真做好本平台所涉及的网络及通信系统的技术维护工作，保证本平台的畅通和高效；</div>

				<div>2、 除不可抗拒的因素导致本平台临时停止或短时间停止服务以外，乙方如需停止本平台的全部或部分服务时，须提前在本平台上发布通知通告会员；</div>

				<div>3、如本平台因系统维护或升级等原因需暂停服务，将事先通过主页、电子邮件等方式公告会员；</div>

				<div>4、因不可抗力而使本平台服务暂停，所导致会员任何实际或潜在的损失，本平台不做任何补偿；</div>

				<div>5、平台不承担会员因遗失密码而受到的一切损失；</div>

				<h4>八、附则</h4>

				<div>1、以上规定的范围仅限于九大爷平台http://www.9daye.com.cn；</div>

				<div>2、本网会员因违反以上规定而触犯有关法律法规，一切后果自负，本平台不承担任何责任；</div>

				<div>3、本规则未涉及之问题参见有关法律法规，当本规定与有关法律法规冲突时，以相应的法律法规为准。在本条款规定范围内，九大爷平台拥有最终解释权；</div>
			</div>
			<div class="close">
				<button id="close">关闭</button>
			</div>
		</div>
	</div>
	<div class="header-tip hidden" id="J_header_tip"></div>
	<div class="head">*项为必填项</div>
	<div class="store-infomation info">
		<div class="store-name">
			<div class="name ">
				<span>*</span>门店名称
			</div>
			<input type="input" name="company-name" maxlength="10" id="J_store_name" placeholder="请输入门店名称">
		</div>
		<div class="company-name ">
			<div class="name ">
				<span></span>公司名称
			</div>
			<input type="input" name="company-name" maxlength="20" id="J_company_name" placeholder="请输入公司名称">
		</div>
		<div class="store-area ">
			<div class="name ">
				<span>*</span>门店所属区域
			</div>
			<div class="select-con">			
				<select id="selProvince">
					<option value="">省份</option>
				</select >
				<select id="selCity">
					<option value="">城市</option>
				</select >
				<select id="selDistrict">
					<option value="">区/县</option>
				</select >
			</div>
		</div>
		<div class="store-adress ">
			<div class="name ">
				<span>*</span>门店详细地址
			</div>
			<input type="input" name="store-adress" maxlength="50" id="J_detail_address" placeholder="请输入详细地址">
		</div>
		
	</div>
	<div class="person-infomation infos">
		<div class="respon-person-img">
			<div class="person ">
				<span class="red">*</span>负责人
			</div>
			<input type="input" name="store-name" maxlength="10" id="J_leader_name" placeholder="请填写门店负责人姓名">
		</div>
		<div class="respon-person">
			<span class="red"></span>负责人身份证（分别上传负责人身份证正反面照片）
		</div>
		<!-- <div class="clear"></div> -->
		<div class="imgcon">
			<div class="img1">
				<img src="/images/new_account/add.png">
				<input type="file" id="J_card_front">
				<b class="text">正面</b>
			</div>
			<div class="img2">
				<img src="/images/new_account/add.png">
				<input type="file" id="J_card_reverse">
				<b class="text">反面</b>
			</div>
			
		</div>
		
	</div>
	<div class="person-contact info">
		<div class="contact-name ">
			<div>
				<span></span>联系人
			</div>
			<input type="input" name="contact-name" maxlength="10" id="J_contact_name" placeholder="请填写联系人姓名">
		</div>
		<div class="contact-phone ">
			<div>
				<span></span>联系人手机
			</div>
			<input type="input" name="contact-phone" id="J_contact_phone" maxlength="11" placeholder="请填写联系人手机号">
		</div>
		<div class="contact-email ">
			<div>
				<span>*</span>电子邮箱
			</div>
			<input type="input" name="contact-name" id="J_email" placeholder="请填写联系人邮箱信息">
		</div>
	</div>
	<div class="business-license info">
		<div class="business-license-img">
			<span class="red"></span>营业执照照片
		</div>
		<div class="imgcon">
			<div class="img3">
				<img src="/images/new_account/add.png">
				<input type="file" name="img3" id="J_business_licence">
				<b class="text">营业执照照片</b>
			</div>
			
		</div>
		
	</div>
	<div class="stores-img info">
		<div class="stores-img-text">
			<span class="red"></span>门店照片
		</div>
		<div class="imgcon">
			<div class="img4">
				<img src="/images/new_account/add.png">
				<input type="file" name="img4" id="J_store_front">
				<b class="text">门店门面照片</b>
			</div>
			<div class="img5">
				<img src="/images/new_account/add.png">
				<input type="file" name="img5" id="J_store_reverse">
				<b class="text">门店店内照片</b>
			</div>
		</div>
	</div>
	<div id="agree" class="agree">
		<b></b>
		<span >我已阅读并同意</span>
		<span href="#" class="blue" id="model">《九大爷协议》</span>
		
	</div>
	<div class="clear"></div>
	<div class="btn btn-active">
		<button id="J_submit_btn">提交</button>
	</div>
	<div class="btn hidden" id="J_submit_disabled">
		<button>提交</button>
	</div>
	
</div>