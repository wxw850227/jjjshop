<template>

	<div class="getpoint-container">
		<div>
			<div id="cur_city">
				<div class="d-s-c map-header-box">
					<span class="pr16">{{ select_city }}</span>
					<a href="javascript:void(0);" @click="selectCityFunc" class="btn-city">
						[
						<span style="text-decoration:underline;">更换城市</span>
						]
					</a>
					<span class="pl16">当前缩放等级：{{ zoom }}</span>
					<div class="d-s-c pl16 pr">
						<div class="search-word-list">
							<ul>
								<li :class="{ curr: searchlist_index == index }" v-for="(item, index) in searchWordList"
									:key="index" @click="ChooseSeatchValue(item.title)">{{ item.title }}</li>
							</ul>
						</div>
						<input class="search-box" type="text" @keyup="keyupFunc" v-model="searchValue" />
						<span class="ml4"><el-button @click="searchFunc">搜索</el-button></span>
					</div>
				</div>

				<div id="city" v-show="is_city == true">
					<span class="close" @click="closeCityFunc"><i class="el-icon-close"></i></span>
					<div>
						<h3 class="city_class">热门城市</h3>
						<div class="city_container">
							<span class="city_name">北京</span>
							<span class="city_name">深圳</span>
							<span class="city_name">上海</span>
							<span class="city_name">香港</span>
							<span class="city_name">澳门</span>
							<span class="city_name">广州</span>
							<span class="city_name">天津</span>
							<span class="city_name">重庆</span>
							<span class="city_name">杭州</span>
							<span class="city_name">成都</span>
							<span class="city_name">武汉</span>
							<span class="city_name">青岛</span>
						</div>
						<h3 class="city_class">全国城市</h3>
						<div class="city_container">
							<div class="city_container_left">直辖市</div>
							<div class="city_container_right">
								<span class="city_name">北京</span>
								<span class="city_name">上海</span>
								<span class="city_name">天津</span>
								<span class="city_name">重庆</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">内蒙古</span></div>
							<div class="city_container_right">
								<span class="city_name">呼和浩特</span>
								<span class="city_name">包头</span>
								<span class="city_name">乌海</span>
								<span class="city_name">赤峰</span>
								<span class="city_name">通辽</span>
								<span class="city_name">鄂尔多斯</span>
								<span class="city_name">呼伦贝尔</span>
								<span class="city_name">巴彦淖尔</span>
								<span class="city_name">乌兰察布</span>
								<span class="city_name">兴安盟</span>
								<span class="city_name">锡林郭勒盟</span>
								<span class="city_name">阿拉善盟</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">山西</span></div>
							<div class="city_container_right">
								<span class="city_name">太原</span>
								<span class="city_name">大同</span>
								<span class="city_name">阳泉</span>
								<span class="city_name">长治</span>
								<span class="city_name">晋城</span>
								<span class="city_name">朔州</span>
								<span class="city_name">晋中</span>
								<span class="city_name">运城</span>
								<span class="city_name">忻州</span>
								<span class="city_name">临汾</span>
								<span class="city_name">吕梁</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">陕西</span></div>
							<div class="city_container_right">
								<span class="city_name">西安</span>
								<span class="city_name">铜川</span>
								<span class="city_name">宝鸡</span>
								<span class="city_name">咸阳</span>
								<span class="city_name">渭南</span>
								<span class="city_name">延安</span>
								<span class="city_name">汉中</span>
								<span class="city_name">榆林</span>
								<span class="city_name">安康</span>
								<span class="city_name">商洛</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">河北</span></div>
							<div class="city_container_right">
								<span class="city_name">石家庄</span>
								<span class="city_name">唐山</span>
								<span class="city_name">秦皇岛</span>
								<span class="city_name">邯郸</span>
								<span class="city_name">邢台</span>
								<span class="city_name">保定</span>
								<span class="city_name">张家口</span>
								<span class="city_name">承德</span>
								<span class="city_name">沧州</span>
								<span class="city_name">廊坊</span>
								<span class="city_name">衡水</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">辽宁</span></div>
							<div class="city_container_right">
								<span class="city_name">沈阳</span>
								<span class="city_name">大连</span>
								<span class="city_name">鞍山</span>
								<span class="city_name">抚顺</span>
								<span class="city_name">本溪</span>
								<span class="city_name">丹东</span>
								<span class="city_name">锦州</span>
								<span class="city_name">营口</span>
								<span class="city_name">阜新</span>
								<span class="city_name">辽阳</span>
								<span class="city_name">盘锦</span>
								<span class="city_name">铁岭</span>
								<span class="city_name">朝阳</span>
								<span class="city_name">葫芦岛</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">吉林</span></div>
							<div class="city_container_right">
								<span class="city_name">长春</span>
								<span class="city_name">吉林市</span>
								<span class="city_name">四平</span>
								<span class="city_name">辽源</span>
								<span class="city_name">通化</span>
								<span class="city_name">白山</span>
								<span class="city_name">松原</span>
								<span class="city_name">白城</span>
								<span class="city_name">延边</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">黑龙江</span></div>
							<div class="city_container_right">
								<span class="city_name">哈尔滨</span>
								<span class="city_name">齐齐哈尔</span>
								<span class="city_name">鸡西</span>
								<span class="city_name">鹤岗</span>
								<span class="city_name">双鸭山</span>
								<span class="city_name">大庆</span>
								<span class="city_name">伊春</span>
								<span class="city_name">牡丹江</span>
								<span class="city_name">佳木斯</span>
								<span class="city_name">七台河</span>
								<span class="city_name">黑河</span>
								<span class="city_name">绥化</span>
								<span class="city_name">大兴安岭</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">江苏</span></div>
							<div class="city_container_right">
								<span class="city_name">南京</span>
								<span class="city_name">无锡</span>
								<span class="city_name">徐州</span>
								<span class="city_name">常州</span>
								<span class="city_name">苏州</span>
								<span class="city_name">南通</span>
								<span class="city_name">连云港</span>
								<span class="city_name">淮安</span>
								<span class="city_name">盐城</span>
								<span class="city_name">扬州</span>
								<span class="city_name">镇江</span>
								<span class="city_name">泰州</span>
								<span class="city_name">宿迁</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">安徽</span></div>
							<div class="city_container_right">
								<span class="city_name">合肥</span>
								<span class="city_name">蚌埠</span>
								<span class="city_name">芜湖</span>
								<span class="city_name">淮南</span>
								<span class="city_name">马鞍山</span>
								<span class="city_name">淮北</span>
								<span class="city_name">铜陵</span>
								<span class="city_name">安庆</span>
								<span class="city_name">黄山</span>
								<span class="city_name">阜阳</span>
								<span class="city_name">宿州</span>
								<span class="city_name">滁州</span>
								<span class="city_name">六安</span>
								<span class="city_name">宣城</span>
								<span class="city_name">池州</span>
								<span class="city_name">亳州</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">山东</span></div>
							<div class="city_container_right">
								<span class="city_name">济南</span>
								<span class="city_name">青岛</span>
								<span class="city_name">淄博</span>
								<span class="city_name">枣庄</span>
								<span class="city_name">东营</span>
								<span class="city_name">潍坊</span>
								<span class="city_name">烟台</span>
								<span class="city_name">威海</span>
								<span class="city_name">济宁</span>
								<span class="city_name">泰安</span>
								<span class="city_name">日照</span>
								<span class="city_name">临沂</span>
								<span class="city_name">德州</span>
								<span class="city_name">聊城</span>
								<span class="city_name">滨州</span>
								<span class="city_name">菏泽</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">浙江</span></div>
							<div class="city_container_right">
								<span class="city_name">杭州</span>
								<span class="city_name">宁波</span>
								<span class="city_name">温州</span>
								<span class="city_name">嘉兴</span>
								<span class="city_name">绍兴</span>
								<span class="city_name">金华</span>
								<span class="city_name">衢州</span>
								<span class="city_name">舟山</span>
								<span class="city_name">台州</span>
								<span class="city_name">丽水</span>
								<span class="city_name">湖州</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">江西</span></div>
							<div class="city_container_right">
								<span class="city_name">南昌</span>
								<span class="city_name">景德镇</span>
								<span class="city_name">萍乡</span>
								<span class="city_name">九江</span>
								<span class="city_name">新余</span>
								<span class="city_name">鹰潭</span>
								<span class="city_name">赣州</span>
								<span class="city_name">吉安</span>
								<span class="city_name">宜春</span>
								<span class="city_name">抚州</span>
								<span class="city_name">上饶</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">福建</span></div>
							<div class="city_container_right">
								<span class="city_name">福州</span>
								<span class="city_name">厦门</span>
								<span class="city_name">莆田</span>
								<span class="city_name">三明</span>
								<span class="city_name">泉州</span>
								<span class="city_name">漳州</span>
								<span class="city_name">南平</span>
								<span class="city_name">龙岩</span>
								<span class="city_name">宁德</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">湖南</span></div>
							<div class="city_container_right">
								<span class="city_name">长沙</span>
								<span class="city_name">株洲</span>
								<span class="city_name">湘潭</span>
								<span class="city_name">衡阳</span>
								<span class="city_name">邵阳</span>
								<span class="city_name">岳阳</span>
								<span class="city_name">常德</span>
								<span class="city_name">张家界</span>
								<span class="city_name">益阳</span>
								<span class="city_name">郴州</span>
								<span class="city_name">永州</span>
								<span class="city_name">怀化</span>
								<span class="city_name">娄底</span>
								<span class="city_name">湘西</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">湖北</span></div>
							<div class="city_container_right">
								<span class="city_name">武汉</span>
								<span class="city_name">黄石</span>
								<span class="city_name">襄阳</span>
								<span class="city_name">十堰</span>
								<span class="city_name">宜昌</span>
								<span class="city_name">荆门</span>
								<span class="city_name">鄂州</span>
								<span class="city_name">孝感</span>
								<span class="city_name">荆州</span>
								<span class="city_name">黄冈</span>
								<span class="city_name">咸宁</span>
								<span class="city_name">随州</span>
								<span class="city_name">恩施</span>
								<span class="city_name">潜江</span>
								<span class="city_name">仙桃</span>
								<span class="city_name">天门</span>
								<span class="city_name">神农架</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">河南</span></div>
							<div class="city_container_right">
								<span class="city_name">郑州</span>
								<span class="city_name">开封</span>
								<span class="city_name">洛阳</span>
								<span class="city_name">平顶山</span>
								<span class="city_name">焦作</span>
								<span class="city_name">鹤壁</span>
								<span class="city_name">新乡</span>
								<span class="city_name">安阳</span>
								<span class="city_name">濮阳</span>
								<span class="city_name">许昌</span>
								<span class="city_name">漯河</span>
								<span class="city_name">三门峡</span>
								<span class="city_name">南阳</span>
								<span class="city_name">商丘</span>
								<span class="city_name">信阳</span>
								<span class="city_name">周口</span>
								<span class="city_name">驻马店</span>
								<span class="city_name">济源</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">海南</span></div>
							<div class="city_container_right">
								<span class="city_name">海口</span>
								<span class="city_name">三亚</span>
								<span class="city_name">三沙</span>
								<span class="city_name">儋州</span>
								<span class="city_name">五指山</span>
								<span class="city_name">文昌</span>
								<span class="city_name">琼海</span>
								<span class="city_name">万宁</span>
								<span class="city_name">东方</span>
								<span class="city_name">定安</span>
								<span class="city_name">屯昌</span>
								<span class="city_name">澄迈</span>
								<span class="city_name">临高</span>
								<span class="city_name">白沙</span>
								<span class="city_name">昌江</span>
								<span class="city_name">乐东</span>
								<span class="city_name">陵水</span>
								<span class="city_name">保亭</span>
								<span class="city_name">琼中</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">广东</span></div>
							<div class="city_container_right">
								<span class="city_name">广州</span>
								<span class="city_name">深圳</span>
								<span class="city_name">珠海</span>
								<span class="city_name">汕头</span>
								<span class="city_name">韶关</span>
								<span class="city_name">佛山</span>
								<span class="city_name">江门</span>
								<span class="city_name">湛江</span>
								<span class="city_name">茂名</span>
								<span class="city_name">肇庆</span>
								<span class="city_name">惠州</span>
								<span class="city_name">梅州</span>
								<span class="city_name">汕尾</span>
								<span class="city_name">河源</span>
								<span class="city_name">阳江</span>
								<span class="city_name">清远</span>
								<span class="city_name">东莞</span>
								<span class="city_name">中山</span>
								<span class="city_name">潮州</span>
								<span class="city_name">揭阳</span>
								<span class="city_name">云浮</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">广西</span></div>
							<div class="city_container_right">
								<span class="city_name">南宁</span>
								<span class="city_name">柳州</span>
								<span class="city_name">桂林</span>
								<span class="city_name">梧州</span>
								<span class="city_name">北海</span>
								<span class="city_name">防城港</span>
								<span class="city_name">钦州</span>
								<span class="city_name">贵港</span>
								<span class="city_name">玉林</span>
								<span class="city_name">百色</span>
								<span class="city_name">贺州</span>
								<span class="city_name">河池</span>
								<span class="city_name">来宾</span>
								<span class="city_name">崇左</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">贵州</span></div>
							<div class="city_container_right">
								<span class="city_name">贵阳</span>
								<span class="city_name">遵义</span>
								<span class="city_name">安顺</span>
								<span class="city_name">铜仁</span>
								<span class="city_name">毕节</span>
								<span class="city_name">六盘水</span>
								<span class="city_name">黔西南</span>
								<span class="city_name">黔东南</span>
								<span class="city_name">黔南</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">四川</span></div>
							<div class="city_container_right">
								<span class="city_name">成都</span>
								<span class="city_name">自贡</span>
								<span class="city_name">攀枝花</span>
								<span class="city_name">泸州</span>
								<span class="city_name">德阳</span>
								<span class="city_name">绵阳</span>
								<span class="city_name">广元</span>
								<span class="city_name">遂宁</span>
								<span class="city_name">内江</span>
								<span class="city_name">乐山</span>
								<span class="city_name">南充</span>
								<span class="city_name">宜宾</span>
								<span class="city_name">广安</span>
								<span class="city_name">达州</span>
								<span class="city_name">眉山</span>
								<span class="city_name">雅安</span>
								<span class="city_name">巴中</span>
								<span class="city_name">资阳</span>
								<span class="city_name">阿坝</span>
								<span class="city_name">甘孜</span>
								<span class="city_name">凉山</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">云南</span></div>
							<div class="city_container_right">
								<span class="city_name">昆明</span>
								<span class="city_name">保山</span>
								<span class="city_name">昭通</span>
								<span class="city_name">丽江</span>
								<span class="city_name">普洱</span>
								<span class="city_name">临沧</span>
								<span class="city_name">曲靖</span>
								<span class="city_name">玉溪</span>
								<span class="city_name">文山</span>
								<span class="city_name">西双版纳</span>
								<span class="city_name">楚雄</span>
								<span class="city_name">红河</span>
								<span class="city_name">德宏</span>
								<span class="city_name">大理</span>
								<span class="city_name">怒江</span>
								<span class="city_name">迪庆</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">甘肃</span></div>
							<div class="city_container_right">
								<span class="city_name">兰州</span>
								<span class="city_name">嘉峪关</span>
								<span class="city_name">金昌</span>
								<span class="city_name">白银</span>
								<span class="city_name">天水</span>
								<span class="city_name">酒泉</span>
								<span class="city_name">张掖</span>
								<span class="city_name">武威</span>
								<span class="city_name">定西</span>
								<span class="city_name">陇南</span>
								<span class="city_name">平凉</span>
								<span class="city_name">庆阳</span>
								<span class="city_name">临夏</span>
								<span class="city_name">甘南</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">宁夏</span></div>
							<div class="city_container_right">
								<span class="city_name">银川</span>
								<span class="city_name">石嘴山</span>
								<span class="city_name">吴忠</span>
								<span class="city_name">固原</span>
								<span class="city_name">中卫</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">青海</span></div>
							<div class="city_container_right">
								<span class="city_name">西宁</span>
								<span class="city_name">玉树</span>
								<span class="city_name">果洛</span>
								<span class="city_name">海东</span>
								<span class="city_name">海西</span>
								<span class="city_name">黄南</span>
								<span class="city_name">海北</span>
								<span class="city_name">海南</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">西藏</span></div>
							<div class="city_container_right">
								<span class="city_name">拉萨</span>
								<span class="city_name">那曲</span>
								<span class="city_name">昌都</span>
								<span class="city_name">山南</span>
								<span class="city_name">日喀则</span>
								<span class="city_name">阿里</span>
								<span class="city_name">林芝</span>
							</div>
						</div>
						<div style="clear:both"></div>
						<div class="city_container">
							<div class="city_container_left"><span class="style_color">新疆</span></div>
							<div class="city_container_right">
								<span class="city_name">乌鲁木齐</span>
								<span class="city_name">克拉玛依</span>
								<span class="city_name">吐鲁番</span>
								<span class="city_name">哈密</span>
								<span class="city_name">博尔塔拉</span>
								<span class="city_name">巴音郭楞</span>
								<span class="city_name">克孜勒苏</span>
								<span class="city_name">和田</span>
								<span class="city_name">阿克苏</span>
								<span class="city_name">喀什</span>
								<span class="city_name">塔城</span>
								<span class="city_name">伊犁</span>
								<span class="city_name">昌吉</span>
								<span class="city_name">阿勒泰</span>
								<span class="city_name">石河子</span>
								<span class="city_name">阿拉尔</span>
								<span class="city_name">图木舒克</span>
								<span class="city_name">五家渠</span>
								<span class="city_name">北屯</span>
								<span class="city_name">铁门关</span>
								<span class="city_name">双河</span>
								<span class="city_name">可克达拉</span>
								<span class="city_name">昆玉</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="pr map-container">
			<div id="mapContainer"></div>
			<!--搜索出来的地方-->
			<div :class="searchList.length > 0 ? 'map-city-list open' : 'map-city-list'">
				<div :class="select_address == index ? 'd-s-s item active' : 'd-s-s item'"
					v-for="(item, index) in searchList" :key="index" @click="choseItem(item, index)">
					<span class="index-box">{{ index + 1 }}</span>
					<div class="flex-1">
						<p class="title">{{ item.title }}</p>
						<p class="address">{{ item.address }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	import marker10 from '@/assets/img/marker10.png';
	import {
		TMap
	} from '@/utils/txmap.js';
	import {
		getJson
	} from '@/utils/getJson.js';
	export default {
		data() {
			return {
				/*背景图片*/
				marker10_url: marker10,
				/*地图缩放层级*/
				zoom: 10,
				/*地图对象*/
				map: null,
				/*全局QQ*/
				qq: null,
				/*搜索结果*/
				searchList: [],
				/*选择的地址*/
				select_address: null,
				/*当前城市*/
				select_city: null,
				/*城市列表是否显示*/
				is_city: false,
				/*关键字*/
				searchValue: '',
				/*搜索字母*/
				searchWord: '',
				/*键盘事件搜索的数据*/
				searchWordList: [],
				/*选择搜索出来的数据列表*/
				searchlist_index: null,
				/*图标临时对象*/
				marker_temp: null
			};
		},
		props: ['form', 'tx_key'],
		watch: {
			/* 'form.is_search_map': {
			  handler(newName, oldName) {
			    if (newName != oldName) {
			      this.searchFunc();
			    }
			  },
			  immediate: true
			  deep: true
			} */
		},
		mounted() {
			this.start();
		},
		methods: {
			/*初始化*/
			start() {
				const self = this;
				TMap().then(qq => {
					self.map = new qq.maps.Map(document.getElementById('mapContainer'), {
						// 地图的中心地理坐标。
						center: new qq.maps.LatLng(39.916527, 116.397128),
						draggableCursor: 'crosshair',
						draggingCursor: 'crosshair',
						zoom: 8
					});

					//获取当前的城市
					let cityservice = new qq.maps.CityService({
						complete: function(result) {
							self.select_city = result.detail.name;
							self.map.panTo(new qq.maps.LatLng(result.detail.latLng.lat, result.detail
								.latLng.lng));
							self.map.zoomTo(13);
						}
					});
					cityservice.searchLocalCity();

					//绑定单击地图事件添加参数
					qq.maps.event.addListener(self.map, 'click', function(event) {
						let lat = event.latLng.getLat(),
							lng = event.latLng.getLng();
						let url = encodeURI("https://apis.map.qq.com/ws/geocoder/v1/?location=" + lat +
							"," + lng +
							"&key=" + self.tx_key + "&output=jsonp&&callback=?");
						getJson(url, 'QQmap', function(res) {
							if (res.status == 0) {
								self.$emit('chose', res.result);
							}
						});
					});

					//获取地图缩放层级
					qq.maps.event.addListener(self.map, 'zoom_changed', function() {
						self.zoom = self.map.getZoom();
					});

					self.qq = qq;

					//选择城市
					let cityList = document.getElementById('city');
					cityList.onclick = function(ev) {
						var ev = ev || event;
						if (ev.target.className == 'city_name') {
							var name = ev.target.innerText;
							self.select_city = name;
							self.is_city = false;
						}
					};
				});
			},

			/*监听键盘事件*/
			keyupFunc(e) {
				/*如果已经有搜索内容，按下向下的键盘*/
				if (e.key == 'ArrowDown') {
					if (this.searchWordList.length > 0) {
						if (this.searchlist_index == null) {
							this.searchlist_index = 0;
						} else {
							if (this.searchlist_index >= this.searchWordList.length) {
								this.searchlist_index = 0;
							} else {
								this.searchlist_index++;
							}
						}
					}
					return;
				}

				/*如果已经有搜索内容，按下向上的键盘*/
				if (e.key == 'ArrowUp') {
					if (this.searchWordList.length > 0) {
						if (this.searchlist_index == null) {
							this.searchlist_index = this.searchWordList.length - 1;
						} else {
							if (this.searchlist_index <= 0) {
								this.searchlist_index = this.searchWordList.length - 1;
							} else {
								this.searchlist_index--;
							}
						}
					}
					return;
				}

				/*如果搜索有数据，选中也有值，则直接赋值*/
				if (e.key == 'Enter' && this.searchWordList.length > 0 && this.searchlist_index != null) {
					this.searchValue = this.searchWordList[this.searchlist_index].title;
					this.searchWordList = [];
					this.searchlist_index = null;
					return;
				}

				/*判断键盘敲的是字母*/
				if (e.key.length == 1) {
					if (this.searchValue.length < 1) {
						this.searchWord += e.key;
						this.getSearchWord(this.searchWord);
					} else {
						this.getSearchWord(this.searchValue);
					}
				} else {
					if (e.key == 'Backspace') {
						if (this.searchValue.length < 1) {
							this.searchWord = this.searchWord.substr(0, this.searchWord.length - 1);
							this.getSearchWord(this.searchWord);
						} else {
							this.getSearchWord(this.searchValue);
						}
					} else if (e.key == 'Process' || e.key == 'Enter') {
						if (this.searchValue.length > 0) {
							this.searchWord == '';
						}
						this.getSearchWord(this.searchValue);
					}
				}
			},

			/*搜索关键字变化时，触发获取类似列表*/
			getSearchWord(Value) {
				let self = this;
				if (Value == '') {
					return;
				}
				let url = encodeURI(
					"https://apis.map.qq.com/ws/place/v1/suggestion/?keyword=" + Value + "&region=" + this
					.select_city + "&key=" + self.tx_key + "&output=jsonp&&callback=?"
				);

				getJson(url, 'QQmap', function(res) {
					if (res.status == 0) {
						self.searchWordList = res.data;
					}
				});
			},

			/*选择搜索出来的关键字*/
			ChooseSeatchValue(name) {
				this.searchValue = name;
				this.searchWordList = [];
				this.searchlist_index = null;
				this.searchFunc();
			},

			/*搜索关键字*/
			searchFunc() {
				let self = this;

				self.searchWordList = [];
				self.searchlist_index = null;

				if (self.is_city) {
					self.is_city = false;
				}
				if (self.select_city == null) {
					self.$message.error('请选择城市!');
					return;
				}
				if (self.searchValue == '') {
					self.$message.error('请填写搜索的内容!');
					return;
				}

				let value = self.searchValue;
				let query_city = self.select_city;

				let url = encodeURI(
					"https://apis.map.qq.com/ws/place/v1/search?keyword=" +
					value +
					"&boundary=region(" +
					query_city +
					",0)&page_size=9&page_index=1&key=" + self.tx_key + "&output=jsonp&&callback=?"
				);

				/*通过关键字搜索*/
				getJson(url, 'QQmap', function(res) {
					self.searchList = res.data;

					for (let n = 0; n < res.data.length; n++) {
						let ele = res.data[n];
						let latlng = new self.qq.maps.LatLng(ele.location.lat, ele.location.lng);

						let left = n * 27;
						let marker = new self.qq.maps.Marker({
							map: self.map,
							position: latlng,
							zIndex: 10
						});
						marker.index = n;
						marker.isClicked = false;
						self.markerPoint(marker, true);
						/*给图标加事件*/
						self.qq.maps.event.addDomListener(marker, "click", function(e) {
							//console.log('点击地图',e.target.index);
							self.choseItem(self.searchList[e.target.index], e.target.index);
						});

					}
				});
			},

			/*在地图标点*/
			markerPoint(marker, flag) {
				let self = this;
				//创建并初始化MultiMarker
				var left = marker.index * 27;
				if (flag == true) {
					var anchor = new self.qq.maps.Point(10, 30),
						origin = new self.qq.maps.Point(left, 0),
						size = new self.qq.maps.Size(27, 33),
						icon = new self.qq.maps.MarkerImage(self.marker10_url, size, origin, anchor);
					marker.setIcon(icon);
				} else {
					var anchor = new self.qq.maps.Point(10, 30),
						origin = new self.qq.maps.Point(left, 35),
						size = new self.qq.maps.Size(27, 33),
						icon = new self.qq.maps.MarkerImage(self.marker10_url, size, origin, anchor);
					marker.setIcon(icon);
				}
			},

			/*选择地点*/
			choseItem(item, _index) {
				let self = this;
				self.select_address = _index;
				self.map.panTo(new qq.maps.LatLng(item.location.lat, item.location.lng));
				self.map.zoomTo(13);
				let latlng = new self.qq.maps.LatLng(item.location.lat, item.location.lng);
				let marker = new self.qq.maps.Marker({
					map: self.map,
					position: latlng,
					zIndex: 10
				});
				marker.index = _index;
				marker.isClicked = false;
				if (self.marker_temp != null) {
					self.markerPoint(self.marker_temp, true);
				}
				self.markerPoint(marker, false);
				self.marker_temp = marker;
				self.qq.maps.event.addDomListener(marker, "click", function() {
					self.choseItem(item, _index);
				});

				self.$emit('chose', item);
			},

			/*选择城市*/
			selectCityFunc() {
				this.is_city = true;
			},

			/*关闭选择*/
			closeCityFunc() {
				this.is_city = false;
			},

			/*搜索*/
			searchMap() {},

			/*获取坐标*/
			getMap(e) {}
		}
	};
</script>

<style scoped="">
	.map-container {
		border: 1px solid #dddddd;
		overflow: hidden;
	}

	#mapContainer {
		min-width: 500px;
		min-height: 500px;
		cursor: crosshair;
	}

	#cur_city {
		position: relative;
	}

	#cur_city .map-header-box {
		padding: 4px 10px;
		background: #409eff;
		color: #ffffff;
	}

	#cur_city #city {
		position: absolute;
		width: 400px;
		height: 500px;
		left: 0;
		z-index: 10;
		overflow-y: auto;
		border: 1px solid #409eff;
		background: #ffffff;
	}

	#cur_city .map-header-box .btn-city {
		color: #ffffff;
	}

	#cur_city .close {
		position: absolute;
		width: 30px;
		height: 30px;
		padding: 0;
		line-height: 30px;
		text-align: center;
		right: 10px;
		top: 10px;
		border-radius: 50%;
		border: 1px solid #cccccc;
		cursor: pointer;
	}

	#cur_city .city_class,
	#cur_city .city_container {
		padding: 0 20px;
	}

	.city_container_left {
		font-weight: bold;
	}

	#cur_city .city_name {
		margin-right: 10px;
		cursor: pointer;
	}

	#cur_city .city_container_right .city_name:hover {
		color: #409eff;
	}

	.map-city-list {
		position: absolute;
		width: 200px;
		top: 0;
		left: -200px;
		bottom: 0;
		background: rgba(255, 255, 255, 0.8);
		box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.2);
		overflow: auto;
	}

	.map-city-list.open {
		left: 0;
	}

	.map-city-list .item {
		padding: 10px;
		border-bottom: 1px solid #dddddd;
		cursor: pointer;
	}

	.map-city-list .item .index-box {
		width: 20px;
		height: 20px;
		margin-right: 4px;
		margin-top: 2px;
		text-align: center;
		line-height: 20px;
		border-radius: 50%;
		background: #ffa500;
		color: #ffffff;
	}

	.map-city-list .item.active {
		background: rgba(64, 157, 255, 0.1);
	}

	.map-city-list .title {
		line-height: 24px;
		font-size: 14px;
		color: #000000;
	}

	.map-city-list .address {
		line-height: 20px;
		font-size: 12px;
		color: #888888;
	}

	.getpoint-container .search-box {
		padding: 0 8px;
		height: 32px;
		line-height: 32px;
		border-radius: 4px;
		border: 1px solid #dcdfe6;
	}

	.getpoint-container .search-word-list {
		position: absolute;
		background: #ffffff;
		top: 36px;
		z-index: 99;
		border: 1px solid #409eff;
		border-radius: 0 0 6px 6px;
	}

	.getpoint-container .search-word-list li {
		padding: 0 16px;
		color: #333333;
		cursor: pointer;
	}

	.getpoint-container .search-word-list li:hover,
	.getpoint-container .search-word-list li.curr {
		background: #d9eafc;
	}
</style>