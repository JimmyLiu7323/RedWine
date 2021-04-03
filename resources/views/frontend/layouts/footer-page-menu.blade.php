<div class="col-left sidebar col-left-first">
	<div class="cms-menu-wrapper">
		<div class="footer-menu-block block-cms-menu">
			<div class="block-title" onclick="enableList(this)">
				<strong>
					<span>{{$LANGUAGE==='EN'?'ABOUT US':'关于我们'}}</span>
					<i class="fas fa-chevron-right rightArrow"></i>
					<i class="fas fa-chevron-down downArrow"></i>
				</strong>
			</div>
			<div class="block-content no-display">
				<ul>
					<li><a href="/contacts#what_are_your_opening_hours" title="{{$LANGUAGE==='EN'?'Opening Hours':'营业时间'}}"><span>{{$LANGUAGE==='EN'?'Opening Hours':'营业时间'}}</span></a></li>
					<li><span><span><a href="/contacts" rel="nofollow">{{$LANGUAGE==='EN'?'Contact Us':'联络我们'}}</a></span></span></li>
					<li><span><span class="widget widget-cms-link-inline"><a href="/about-history" title="{{$LANGUAGE==='EN'?'Our History':'历史沿革'}}"><span>{{$LANGUAGE==='EN'?'Our History':'历史沿革'}}</span></a></span></span></li>
					<li><span class="widget widget-cms-link-inline"><a href="/awards" title="{{$LANGUAGE==='EN'?'Awards':'历年奖项'}}"><span>{{$LANGUAGE==='EN'?'Awards':'历年奖项'}}</span></a></span></li>
				</ul>
			</div>
		</div>
		<div class="footer-menu-block block-cms-menu">
			<div class="block-title" onclick="enableList(this)">
				<strong>
					<span>{{$LANGUAGE==='EN'?'ONLINE ORDERING':'线上购物'}}</span>
					<i class="fas fa-chevron-right rightArrow"></i>
					<i class="fas fa-chevron-down downArrow"></i>
				</strong>
			</div>
			<div class="block-content no-display">
				<ul>
					<li><span class="widget widget-cms-link-inline"><a href="/orders-and-payment" title="{{$LANGUAGE==='EN'?'Orders And Payment':'订购与付款'}}"><span>{{$LANGUAGE==='EN'?'Orders And Payment':'订购与付款'}}</span></a></span></li>
					<li><span class="widget widget-cms-link-inline"><a href="/delivery-page" title="{{$LANGUAGE==='EN'?'Delivery Informmation':'运送资讯'}}"><span>{{$LANGUAGE==='EN'?'Delivery Informmation':'运送资讯'}}</span></a></span></li>
				</ul>
			</div>
		</div>
		<div class="footer-menu-block block-cms-menu">
			<div class="block-title" onclick="enableList(this)">
				<strong>
					<span>{{$LANGUAGE==='EN'?'CUSTOMER SERVICE':'顾客服务'}}</span>
					<i class="fas fa-chevron-right rightArrow"></i>
					<i class="fas fa-chevron-down downArrow"></i>
				</strong>
			</div>
			<div class="block-content no-display">
				<ul>
					<li><span class="widget widget-cms-link-inline"><a href="/guarantee" title="{{$LANGUAGE==='EN'?'Our Guarantee':'商家保证'}}"><span>{{$LANGUAGE==='EN'?'Our Guarantee':'商家保证'}}</span></a></span></li>
					<li><span class="widget widget-cms-link-inline"><a href="/privacy-policy" title="{{$LANGUAGE==='EN'?'Privacy Policy':'隐私政策'}}"><span>{{$LANGUAGE==='EN'?'Privacy Policy':'隐私政策'}}</span></a></span></li>
					<li><span class="widget widget-cms-link"><a href="/corporate" title="{{$LANGUAGE==='EN'?'Corporate Wine Service':'合作促销'}}"><span>{{$LANGUAGE==='EN'?'Corporate Wine Service':'合作促销'}}</span></a></span></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function enableList(elem){
	if($(elem).next('.block-content').hasClass('no-display')){
		$(elem).next('.block-content').removeClass('no-display');
		$(elem).find('.rightArrow').hide();
		$(elem).find('.downArrow').show();
	}
	else{
		$(elem).next('.block-content').addClass('no-display');	
		$(elem).find('.rightArrow').show();
		$(elem).find('.downArrow').hide();
	}
}
</script>