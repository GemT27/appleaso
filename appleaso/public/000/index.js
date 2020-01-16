//模式
var Mode = false;//模式  false 竖屏
var rpGame = false;//是否重玩游戏
var NowComplete = false;// 当前是否加载完成
function resetGameMode(kuanpin)
{
	console.log("reset success",kuanpin);
	
	S = exportRoot;
	if(kuanpin)//宽屏模式
	{
		
		//console.log(S);
		
		S.tu_1.instance_1.visible = false;
		S.tu_2.instance.visible = true;
		S.tu_3.instance.visible =false;
		//S.图层_1.instance_1.visible = false;
		//S.Bitmap2k.scaleY = 2;
		//S.Bitmap2k.scaleY = 2;
		
	}
	else	{//竖屏模式
		S.tu_1.instance_1.visible = true;
		S.tu_2.instance.visible = false;
		S.tu_3.instance.visible =true;
	}
	if(kuanpin == Mode)return;
	else Mode = kuanpin;
	rpGame = true;
	
}

function myinit(lib) {

	//var lib=exportRoot;
	//修改参数请移步Tools类以及下面的参数
	//当前 以及下一个 球
	var MyCur;
	var MyNext;
	//最高允许多少行
	var Num_HS_ZONG = 17;
	//球初始行数
	var Num_QiuHS_CS = 8;
	//每行个数
	var Num_MeiHangGeShu = 11;

	var NowNum = 0;

	//球的高度和宽度
	var Ball_Height = 84;
	var Ball_Width = 84;
	var scaleBall = 1;
	var hitWidth = 84;//球体碰撞范围
	//两行球相差间隔（一般为一半）
	var Ball_SmallWidth = 45;
	//上下左右两行球的间隔
	var Ball_JIANGE = 6;
	//起始坐标速度
	var QishizuobiaoX = 65;
	var QishizuobiaoY = 60;
	//球发射的速度
	var JIAODU_X = 80;
	var JIAODU_Y = 80;
	//上下判断的间隔（发射球后的间距判断，数字越小 约难平行）
	var SHANGXIA_ = 3;

	var Chengji = 0;
	//球底部边界
	var qiudi =5;
	//球左侧边界
	var qiuzuo =70;
	//球右侧边界
	var qiuyou = 1020;
	
	//发射球之后的XY速度
	var YD_X;
	var YD_Y;

	var Fire_Ball = false;
	var IsFireOk = true;

	var Now_Big = new Array();
	var Now_Small = new Array();
	//主数组
	var ArrMain = new Array();
	S = exportRoot;
	console.log(exportRoot)
	S.stop();

	var GameOver_ = false;
	var end = null;

	var nowsound = 1;

	//canvas.width = 500;

	NowComplete = true;

	/**
	 * 获取数据
	 * @param {Object} c_name 要获取到数据名
	 */
	function getCookie(c_name) {
		console.log(document.cookie.length);
		if(document.cookie.length > 0) {
			c_start = document.cookie.indexOf(c_name + "=")
			if(c_start != -1) {
				c_start = c_start + c_name.length + 1
				c_end = document.cookie.indexOf(";", c_start)
				if(c_end == -1) c_end = document.cookie.length
				return unescape(document.cookie.substring(c_start, c_end))
			}
		}
		return 0
	}

	function getSoundCookie(c_name) {
		console.log(document.cookie.length);
		if(document.cookie.length > 0) {
			c_start = document.cookie.indexOf(c_name + "=")
			if(c_start != -1) {
				c_start = c_start + c_name.length + 1
				c_end = document.cookie.indexOf(";", c_start)
				if(c_end == -1) c_end = document.cookie.length
				return unescape(document.cookie.substring(c_start, c_end))
			}
		}
		return 1
	}



	/**
	 * 设置数据到cookie  主要是用作于Top10
	 * @param {Object} c_name  保存到数据名
	 * @param {Object} value   保存到数据值
	 * @param {Object} expiredays  保存数据到天数
	 */
	function setCookie(c_name, value, expiredays) {
		var exdate = new Date()
		exdate.setDate(exdate.getDate() + expiredays)
		document.cookie = c_name + "=" + escape(value) +
			((expiredays == null) ? "" : ";expires=" + exdate.toGMTString())
			
		console.log(document.cookie)
	}

	//将游戏布局弄完整
	for(var i = 0; i < Num_HS_ZONG; i++) {
		var sm = new Array();
		for(var j = 0; j < Num_MeiHangGeShu; j++) {
			sm.push(null);
		}
		ArrMain.push(sm);
	}
	replayGame()
	
	nowsound = parseFloat(getSoundCookie("sound"));
	createjs.Sound.volume  = nowsound;
	
	
	//用户点击舞台之后，判断方向，当用户弹起鼠标的时候发射颜色球，按下开始调整方向  松开发射
	S.AllClick.addEventListener("mousedown", function() {
		console.log("FireReady");
		//null;
	});
	S.AllClick.addEventListener("pressup", function() {
		console.log("FireStart");
		S.JT.rotation = BackJTLocation();

		if(!IsFireOk || GameOver_)
			return;
		//发射音效
		//playSound("_sbang");
		playSound("_sstart");
		//给多少个角度  其实应该为90的 但实测数据发现85更为准确
		var MAA = 90;
		//箭头的坐标大于0和小于0的发射是不通的 所以这里做出分别判断
		YD_X = Math.cos(3.1415 / 180 * (S.JT.rotation - 90)) * JIAODU_X;
		YD_Y = -Math.sin(3.1415 / 180 * (S.JT.rotation - 90)) * JIAODU_Y;

		//发射球状态，等待球发射完毕
		Fire_Ball = true;
		IsFireOk = false;
	});
	S.rsbtn.addEventListener("click", function() {
		replayGame();
	});
	S.url.addEventListener("click", function() {
		window.open("http://baidu.com"); //跳转网址
	});
	


	S.Top_10.addEventListener("click", function() //Top10功能按钮
		{
			_top.visible = true;
			_top.write();
		});
	S.setup.addEventListener("click", function() //原返回主界面按钮
		{
			setup.visible = true;
			nowsound = parseFloat(getSoundCookie("sound"));
			console.log(nowsound);
			setup.tbV._bb.x = nowsound *300;
		});
	var nowMoveLine = false;
	
	var setup = new lib._setup();
	S.addChild(setup);
	S.setChildIndex(setup, S.numChildren - 1);
	setup.visible = false;
	setup.x = 250;
	setup.y = 500;
	setup.setsound = 100;
	//setup.instance_1.visible = false;
	setup.ok.addEventListener("click",function()
	{
		//设置声音大小
		//createjs.SoundJS.volume = setup.tbV._bb.x/100;
		console.log("SoundVolume:"+createjs.SoundJS.volume)
		setup.visible =false;
	});
	//tbV
	
	var setsounder = false;
	
	setup.tbV._bb.addEventListener("mousedown", function() {
		console.log("SoundReady");
		oldMousex = stage.mouseX;
		setsounder = true;
		//null;
	});
	setup.tbV._bb.addEventListener("pressup", function() {
		console.log("SoundOver");
		//null;
		setsounder = false;
		nowsound =setup.tbV._bb.x/300;
		console.log(nowsound)
		//nowsound = getSoundCookie("sound");
		//setup.tbV._bb.x = nowsound *300;
		createjs.Sound.volume  = nowsound
		setCookie("sound",nowsound)
	});
	//游戏结束
	end = new lib._go();
	S.addChild(end);
	S.setChildIndex(end, S.numChildren - 1);
	end.x = 250;
	end.y = 500;
	end.onComplete = function() {
		var CCJ = mychengji;
		var n1 = parseInt(CCJ / 1000000);
		CCJ -= n1 * 1000000;
		var n2 = parseInt(CCJ / 100000);
		CCJ -= n2 * 100000;
		var n3 = parseInt(CCJ / 10000);
		CCJ -= n3 * 10000;
		var n4 = parseInt(CCJ / 1000);
		CCJ -= n4 * 1000;
		var n5 = parseInt(CCJ / 100);
		CCJ -= n5 * 100;
		var n6 = parseInt(CCJ / 10);
		CCJ -= n6 * 10;
		console.log("chushihua");
		end.sc1.d1.gotoAndStop(n6);
		end.sc1.d2.gotoAndStop(n5);
		end.sc1.d3.gotoAndStop(n4);
		end.sc1.d4.gotoAndStop(n3);
		end.sc1.d5.gotoAndStop(n2);
		end.sc1.d6.gotoAndStop(n1);
		end.sc1.d0.gotoAndStop(0);

		
	}
	//end.onComplete();
	end.ok.addEventListener("click", function() {
		//end.onComplete();
		replayGame();
	})

	end.visible = false;

	var _top = new lib._top();
	S.addChild(_top);
	S.setChildIndex(_top, S.numChildren - 1);
	_top.visible = false;
	_top.top1 =0;
	_top.top2 =0;
	_top.top3 =0;
	_top.top4 =0;
	_top.top5 =0;
	_top.top6 =0;
	_top.top7 =0;
	_top.top8 =0;
	_top.top9 =0;
	_top.top10 =0;
	_top.read = function(){
		//paserInt()
		
		this	.top1 = parseInt(getCookie("top1"));
		this	.top2 = parseInt(getCookie("top2"));
		this	.top3 = parseInt(getCookie("top3"));
		this	.top4 = parseInt(getCookie("top4"));
		this	.top5 = parseInt(getCookie("top5"));
		this	.top6 = parseInt(getCookie("top6"));
		this	.top7 = parseInt(getCookie("top7"));
		this	.top8 = parseInt(getCookie("top8"));
		this	.top9 = parseInt(getCookie("top9"));
		this	.top10 = parseInt(getCookie("top10"));
		console.log(this.top1);
	}
	_top.save = function(chengji){
		//保存游戏记录
		this	.read();
		//return;
		console.log(chengji,1);
		var zsjl;
		if(this	.top1 < chengji)
		{
			zsjl = this.top1;
			this.top1 = chengji;
			chengji = zsjl;
			console.log(this.top1,2);
		}
		if(this	.top2 < chengji)
		{
			zsjl = this.top2;
			this.top2 = chengji;
			chengji = zsjl;
		}
		if(this	.top3 < chengji)
		{
			zsjl = this.top3;
			this.top3 = chengji;
			chengji = zsjl;
		}
		if(this	.top4 < chengji)
		{
			zsjl =this. top4;
			this.top4 = chengji;
			chengji = zsjl;
		}
		if(this	.top5 < chengji)
		{
			zsjl = this.top5;
			this.top5 = chengji;
			chengji = zsjl;
		}
		if(this	.top6 < chengji)
		{
			zsjl = this.top6;
			this.top6 = chengji;
			chengji = zsjl;
		}
		if(this	.top7 < chengji)
		{
			zsjl = this.top7;
			this.top7 = chengji;
			chengji = zsjl;
		}
		if(this	.top8 < chengji)
		{
			zsjl = this.top8;
			this.top8 = chengji;
			chengji = zsjl;
		}
		if(this	.top9 < chengji)
		{
			zsjl = this.top9;
			this.top9 = chengji;
			chengji = zsjl;
		}
		if(this	.top10 < chengji)
		{
			zsjl = this.top10;
			this.top10 = chengji;
			chengji = zsjl;
		}
		
		setCookie("top1",this.top1,365);
		setCookie("top2",this.top2,365);
		setCookie("top3",this.top3,365);
		setCookie("top4",this.top4,365);
		setCookie("top5",this.top5,365);
		setCookie("top6",this.top6,365);
		setCookie("top7",this.top7,365);
		setCookie("top8",this.top8,365);
		setCookie("top9",this.top9,365);
		setCookie("top10",this.top10,365);
		
		console.log(this.top1,3);
		this	.read();
		console.log(this.top1,4);
	}
	_top.x = 220;
	_top.y = 250;
	_top.write = function(){
		this.read();
		//this._H.gotoAndStop(0);
		console.log("gaibian");
		this.e1.txt.text = this.top1;
		this.e2.txt.text = this.top2;
		this.e3.txt.text = this.top3;
		this.e4.txt.text = this.top4;
		this.e5.txt.text = this.top5;
		this.e6.txt.text = this.top6;
		this.e7.txt.text = this.top7;
		this.e8.txt.text = this.top8;
		this.e9.txt.text = this.top9;
		this.e10.txt.text = this.top10;
	}
	//显示
	_top.play_.addEventListener("click",function()
	{
		console.log("byebye");
		_top	.visible  = false;
	});
	
	
	

	function replayGame() {
		console.log("ReplaySuccess");
		for(var j = 0; j < ArrMain.length; j++) {
			for(var k = 0; k < Num_MeiHangGeShu; k++) {
				//清空数组
				if(ArrMain[j][k] != null) //有东西
				{
					S.removeChild(ArrMain[j][k]);
					ArrMain[j][k] = null;
				}
			}
		}
		S.removeChild(MyNext);
		S.removeChild(MyCur);
		//所有数据清空后
		Fire_Ball = false;
		IsFireOk = true;

		Now_Big = new Array();
		Now_Small = new Array();

		GameOver_ = false;
		Chengji = 0;
		mychengji =0;
		
		if(end != null) end.visible = false;

		MaxErr = 5;
		NowErr = 5;
		if(NowErr == 5) createjs.Tween.get(S.bb1).to({
			scaleX: scaleBall,
			scaleY: scaleBall
		}, 300)
		if(NowErr >= 4) createjs.Tween.get(S.bb2).to({
			scaleX: scaleBall,
			scaleY: scaleBall
		}, 300)
		if(NowErr >= 3) createjs.Tween.get(S.bb3).to({
			scaleX: scaleBall,
			scaleY: scaleBall
		}, 300)
		if(NowErr >= 2) createjs.Tween.get(S.bb4).to({
			scaleX: scaleBall,
			scaleY: scaleBall
		}, 300)
		if(NowErr >= 1) createjs.Tween.get(S.bb5).to({
			scaleX: scaleBall,
			scaleY: scaleBall
		}, 300)

		S.bb1.gotoAndStop(0);
		S.bb2.gotoAndStop(0);
		S.bb3.gotoAndStop(0);
		S.bb4.gotoAndStop(0);
		S.bb5.gotoAndStop(0);

		setTimeout(function(){
			console.log("cs");
			S.d0.gotoAndStop(0);
			S.d1.gotoAndStop(0);
			S.d2.gotoAndStop(0);
			S.d3.gotoAndStop(0);
			S.d4.gotoAndStop(0);
			S.d5.gotoAndStop(0);
			S.d6.gotoAndStop(0);
		},10)


		
		for(var i = 0; i < Num_QiuHS_CS; i++)
		//for (var i = 0; i < 2; i++)
		{
			AddRow(i);
			SetRow(i);
		}
		MyCur = AddA(true);
		MyNext = AddA(false);
		//ArrMain[0][0].gotoAndPlay(10);
	}
	//var Arr = new Array(lib.hongse(), lib.Huangse(), lib.QianLan(), lib.ShenLan(), lib.Zise(),lib.Lvse());
	//增加一行
	function AddRow(tt) {
		for(var i = 0; i < Num_MeiHangGeShu; i++) {
			var th = Math.random() * 6;
			//测试用 全部都是一个颜色
			//th = 1;
			th = parseInt(th)
			var zj;
			if(th == 0) zj = new lib.Hongse();
			if(th == 1) zj = new lib.Huangse();
			if(th == 2) zj = new lib.QianLan();
			if(th == 3) zj = new lib.ShenLan();
			if(th == 4) zj = new lib.Zise();
			if(th == 5) zj = new lib.Lvse();
			zj.gotoAndStop(0);
			zj.scaleX = scaleBall;
			zj.scaleY = scaleBall;
			zj.Hang = tt;
			zj.Lie = i;
			S.addChild(zj);
			ArrMain[tt][i] = zj;
			setfunction(zj, th);
		}
	}

	function setfunction(zj, th) {
		//设定颜色
		if(th == 0) zj.Color_ = "Hongse";
		if(th == 1) zj.Color_ = "Huangse";
		if(th == 2) zj.Color_ = "QianLan";
		if(th == 3) zj.Color_ = "ShenLan";;
		if(th == 4) zj.Color_ = "Zise";
		if(th == 5) zj.Color_ = "Lvse";
		zj.Used = false;
		zj.locatX = 0;
		zj.locatY = 0;
		zj.MyboomTimer = 0;

		zj.Find = function(Find_Color) //找寻
		{
			if(this.Used) return;
			if(Find_Color == "") //查询孤岛
			{
				this.Used = true;
			} else if(Find_Color == this.Color_) //如果颜色相同
			{
				this.Used = true;
				NowNum++;
				this.MyboomTimer = NowNum;
			}

			if(this.Used) {
				if((!nowMoveLine && this.Hang % 2 == 0) || (nowMoveLine && this.Hang % 2 == 1)) //bu倾斜
				{
					//console.log(ArrMain[this.Hang - 1][this.Lie],this.Hang,this.Lie,"seeeee");
					try {
						ArrMain[this.Hang - 1][this.Lie - 1].Find(Find_Color);
					} catch(err) {};
					try {
						ArrMain[this.Hang - 1][this.Lie].Find(Find_Color);
					} catch(err) {};
					try {
						ArrMain[this.Hang + 1][this.Lie - 1].Find(Find_Color);
					} catch(err) {};
					try {
						ArrMain[this.Hang + 1][this.Lie].Find(Find_Color);
					} catch(err) {};
					try {
						ArrMain[this.Hang][this.Lie - 1].Find(Find_Color);
					} catch(err) {};
					try {
						ArrMain[this.Hang][this.Lie + 1].Find(Find_Color);
					} catch(err) {};
				} else {
					try {
						ArrMain[this.Hang - 1][this.Lie].Find(Find_Color);
					} catch(err) {};
					try {
						ArrMain[this.Hang - 1][this.Lie + 1].Find(Find_Color);
					} catch(err) {};
					try {
						ArrMain[this.Hang + 1][this.Lie].Find(Find_Color);
					} catch(err) {};
					try {
						ArrMain[this.Hang + 1][this.Lie + 1].Find(Find_Color);
					} catch(err) {};
					try {
						ArrMain[this.Hang][this.Lie - 1].Find(Find_Color);
					} catch(err) {};
					try {
						ArrMain[this.Hang][this.Lie + 1].Find(Find_Color);
					} catch(err) {};
				}
			}
		}
		zj.die = function() {
			createjs.Tween.get(this)
				.wait(90 + this.MyboomTimer * 80).to({
					alpha: 1
				}, 80)
				.call(function() {
					playSound("_sbang");
					this.gotoAndPlay(1);
				});

		}
	}

	//增加下一个球
	function AddA(sf) {
		var th = Math.random() * 6;
		//th = 1;
		th = parseInt(th)
		var zj;
		if(th == 0) zj = new lib.Hongse();
		if(th == 1) zj = new lib.Huangse();
		if(th == 2) zj = new lib.QianLan();
		if(th == 3) zj = new lib.ShenLan();
		if(th == 4) zj = new lib.Zise();
		if(th == 5) zj = new lib.Lvse();

		if(sf) {
			zj.x = S._cur.x;
			zj.y = S._cur.y;

		} else {
			zj.x = S._next.x;
			zj.y = S._next.y;
		}
		zj.scaleX = 0;
		zj.scaleY = 0;

		createjs.Tween.get(zj)
			.to({
				scaleX: scaleBall,
				scaleY: scaleBall
			}, 500);

		zj.gotoAndStop(0);
		S.addChild(zj);
		S.setChildIndex(S.AllClick, S.numChildren - 1);
		if(end != null) S.setChildIndex(end, S.numChildren - 1);
		if(_top != null)S.setChildIndex(_top, S.numChildren - 1);
		if(setup != null)S.setChildIndex(setup, S.numChildren - 1);
		setfunction(zj, th);
		return zj;
	}

	//放置一行
	function SetRow(tt) {
		if((!nowMoveLine && tt % 2 == 0) || (nowMoveLine && tt % 2 == 1)) {
			for(var i = 0; i < Num_MeiHangGeShu; i++) {
				if(ArrMain[tt][i] != null) {
					ArrMain[tt][i].width = Ball_Width;
					ArrMain[tt][i].height = Ball_Height;
					ArrMain[tt][i].x = QishizuobiaoX + i * Ball_Width + i * Ball_JIANGE;
					ArrMain[tt][i].y = QishizuobiaoY + tt * Ball_Height + Ball_JIANGE;

					ArrMain[tt][i].Hang = tt;
					ArrMain[tt][i].Lie = i;
				}

			}
		} else {
			for(var j = 0; j < Num_MeiHangGeShu; j++) {
				if(ArrMain[tt][j] != null) {
					ArrMain[tt][j].width = Ball_Width;
					ArrMain[tt][j].height = Ball_Height;
					ArrMain[tt][j].x = QishizuobiaoX + j * Ball_Width + Ball_SmallWidth + j * Ball_JIANGE;
					ArrMain[tt][j].y = QishizuobiaoY + tt * Ball_Height + Ball_JIANGE;

					ArrMain[tt][j].Hang = tt;
					ArrMain[tt][j].Lie = j;
				}
			}
		}

		if(tt == 0) {
			Now_Big = new Array();
			for(var k = 0; k < Num_MeiHangGeShu; k++) {
				Now_Big.push(ArrMain[tt][k].x);
				Now_Small.push(ArrMain[tt][k].y);

			}

		}

	}

	//Ticker是一种计时类(触发一种动画类型)，相对于tween
	//createjs.Ticker.setFPS(30);
	createjs.Ticker.addEventListener("tick", stageBreakHandler);
	var ev;
	var oldMousex;
	function stageBreakHandler(event) {
		//箭头修正，箭头的角度调整 跟随鼠标
		S.JT.rotation = BackJTLocation();
		if(setsounder){
			//setsound
			//setup.tbV._bb.x =  stage.mouseX *stage.scaleX;
			
			
			setup.tbV._bb.x -=  oldMousex - stage.mouseX;
			oldMousex = stage.mouseX;
			if(setup.tbV._bb.x<0)setup.tbV._bb.x=0;
			if(setup.tbV._bb.x>300)setup.tbV._bb.x = 300;
			console.log(setup.tbV._bb.x)
		}
		
		if(rpGame && NowComplete){
			pingmushipei();
			replayGame();
			rpGame = false;
		}
		
		if(Fire_Ball) //表示有球在移动
		{
			BallMove(); //球移动函数
		}
		//ev = event||window.event;   
		//BackJTLocation();
		stage.update();
	}

	

	function BallMove() {
		//球移动

		MyCur.x += YD_X;
		if(MyCur.y > qiudi) {
			MyCur.y -= YD_Y;
		}

		if(MyCur.x < qiuzuo)
			YD_X *= -1;
		else if(MyCur.x > qiuyou)
			YD_X *= -1;

		if(MyCur.y < qiudi) {
			for(var n = 0; n < Num_MeiHangGeShu; n++) {
				var MV = 20;
				var MP = -40;
				if(MyCur.x - Now_Big[n] < MV && MyCur.x - Now_Big[n] > MP) {
					var tx = n;
					if(ArrMain[0][n] == null) {
						ArrMain[0][n] = MyCur;
					} else {
						ArrMain[0][n + 1] = MyCur;
						tx += 1;
					}

					MyCur.Lie = tx;
					MyCur.Hang = 0;

					//球的函数，用作与微调
					console.log(Now_Big[tx]);
					MyCur.x = Now_Big[tx];
					MyCur.y = Now_Small[tx];
					//MyCur.firelocation(Now_Big[tx], Now_Small[tx]);
					//给发射的球固定的坐标 这个球就算是在数组里面安家了

					MyCur.Find(MyCur.Color_);
					//console.log(ArrMain[8][7])
					console.log(NowNum); //球
					if(NowNum < 3) {

						playSound("_sconnect");
						IsFireOk = true;
						NowNum = 0;
						setFiest();
						if(LookGameOver()) return;
						//没有消除
						noremove()
					} else {
						//有消除
						for(var i = 0; i < Num_HS_ZONG; i++) {
							for(var j = 0; j < Num_MeiHangGeShu; j++) {
								//遍历消除
								//console.log(rrMain[i][j].Used);
								if(ArrMain[i][j] != null && ArrMain[i][j].Used) {
									setchengji(arrchengji[ArrMain[i][j].MyboomTimer]);
									ArrMain[i][j].die();
									ArrMain[i][j] = null;
								}
							}
						}
						console.log("gudao" + NowNum); //球
						//删除孤岛
						createjs.Tween.get(this)
							//.to({alpha: 1}, 80)
							.wait(100 + Number * 80).to({
								alpha: 1
							}, 80)
							.call(function() {
								console.log("removelonly");
								removeLonly();
							});

					}

					//SetRow(AAE);
					//new TWEEN.Tween(MyCur.Position).to({x:intX,y:intY},500).repeat(Infinity).start();TWEEN.update();

					/*var tween = createjs.Tween.get(MyCur.Position)
    							.to({x:300},500))
    							.wait(500).to({alpha:0,visible:false},1000)
    							.call(onComplete);
							*/
					createjs.Tween.get(MyNext)
						.to({
							scaleX: 0,
							scaleY: 0
						}, 100)
						.call(function() {
							MyCur = MyNext;
							MyCur.x = S._cur.x;
							MyCur.y = S._cur.y;
							MyNext = AddA(false);
							createjs.Tween.get(MyCur)
								.to({
									scaleX: scaleBall,
									scaleY: scaleBall
								}, 100);
						});
					Fire_Ball = false;
					//console.log(ArrMain[8][7])

					return;
				}
			}

			return;
		}

		for(var j = Num_HS_ZONG - 1; j >= 0; j--) {
			for(var i = 0; i < Num_MeiHangGeShu; i++) {
				//防止空位置也做判断检测 导致出错
				if(ArrMain[j][i] != null) {
					//如果检测到碰撞了
					//if (MyCur.hitTestObject(ArrMain[j][i]))
					//判断碰撞方位，之后判断坐标
					var cx = MyCur.x;
					var cy = MyCur.y;
					var cw = MyCur.width;
					var ch = MyCur.height;
					var hx = ArrMain[j][i].x;
					var hy = ArrMain[j][i].y;
					var hw = ArrMain[j][i].width;
					var hh = ArrMain[j][i].height;
					//var pzx = Math.sqrt(Math.pow(cx - hx,2)+Math.pow(cy - hy,2)) <= cw/2+hw/2;
					//console.log("hit Ready");
					//console.log(cx,cy,hx,hy,cw,hw);
					//Fire_Ball = false;
					//if(i ==6&& j==7)console.log(cx,cy,hx,hy,hw);
					if(Math.sqrt(Math.pow(cx - hx, 2) + Math.pow(cy - hy, 2)) <= hitWidth) {
						//console.log("hit success");
						//if(i ==7&& j==8)console.log(ArrMain[j][i]);
						//检测记录，用作与发射球在那个方位 便于记录
						var AAB = i + 1;
						var AAE = j;
						//这些是数据详细校正
						/*具体是  首先判断两个球之间X轴的差距 如果大于0
						 * 则在右 反之
						 * 之后判断Y轴 如果大于（起始可以设置）则在斜角 否则就在（左右）
						 * */
						//trace(cy - hy);

						if(cx - hx > 0) //当前球和被碰撞的球 如果大于0 在右边
						{

							if(cy - hy > SHANGXIA_ || i + 1 == Num_MeiHangGeShu) //Y轴检测，看在那一行
							{ //在上一行
								if((!nowMoveLine && j % 2 == 0) || (nowMoveLine && j % 2 == 1)) //看看那一行是否是斜着的
								{
									if(ArrMain[j + 1][i] != null) {
										if(ArrMain[j + 1][i + 1] != null) //出现故障应急方案
										{
											ArrMain[j + 1][i + 1] = MyCur;
											AAB = i + 1;
											AAE = j + 1
										} else if(ArrMain[j + 1][i - 1] != null) {
											ArrMain[j + 1][i - 1] = MyCur;
											AAB = i - 1;
											AAE = j + 1
										}

									} else {
										ArrMain[j + 1][i] = MyCur;
										AAB = i;
										AAE = j + 1
									}
								} else {
									if(i + 1 == Num_MeiHangGeShu) //如果再加超过那行的话
									{
										ArrMain[j + 1][i] = MyCur;
										AAB = i;
										AAE = j + 1;
									} else {
										ArrMain[j + 1][i + 1] = MyCur;
										AAB = i + 1;
										AAE = j + 1;
									}

								}
							} else {
								//-----------------------
								if(ArrMain[j][i + 1] == null) {
									ArrMain[j][i + 1] = MyCur;
								} else {
									if((!nowMoveLine && j % 2 == 0) || (nowMoveLine && j % 2 == 1)) {
										ArrMain[j + 1][i] = MyCur;
										AAB = i;
										AAE = j + 1
									} else {
										ArrMain[j + 1][i + 1] = MyCur;
										AAB = i + 1;
										AAE = j + 1
										if(i + 1 == Num_MeiHangGeShu) {
											ArrMain[j + 1][i] = MyCur;
											AAB = i;
											AAE = j + 1
										}
									}
								}

							}
						} else {
							if(cy - hy > SHANGXIA_ || i - 1 < 0 || i == Num_MeiHangGeShu) {

								if((!nowMoveLine && j % 2 == 0) || (nowMoveLine && j % 2 == 1)) {
									ArrMain[j + 1][i - 1] = MyCur;
									AAB = i - 1;
									AAE = j + 1;
									if(i - 1 < 0) {
										ArrMain[j + 1][i] = MyCur;
										AAB = i;
										AAE = j + 1
									}
								} else {
									ArrMain[j + 1][i] = MyCur;
									AAB = i;
									AAE = j + 1;
								}
							} else {
								//--------------------
								if(ArrMain[j][i - 1] == null) {
									ArrMain[j][i - 1] = MyCur;
									AAB = i - 1
								} else {
									if((!nowMoveLine && j % 2 == 0) || (nowMoveLine && j % 2 == 1)) {
										ArrMain[j + 1][i - 1] = MyCur;
										AAB = i - 1;
										AAE = j + 1;
										if(i - 1 < 0) {
											ArrMain[j + 1][i] = MyCur;
											AAB = i;
											AAE = j + 1;
										}
									} else {
										ArrMain[j + 1][i] = MyCur;
										AAB = i;
										AAE = j + 1;
									}
								}
							}
						}
						//好了之后就可以做微调校正
						var intX;
						var intY;
						if((!nowMoveLine && AAE % 2 == 0) || (nowMoveLine && AAE % 2 == 1)) {
							intX = QishizuobiaoX + AAB * Ball_Width + AAB * Ball_JIANGE;
							intY = QishizuobiaoY + AAE * Ball_Height + Ball_JIANGE;
						} else {
							intX = QishizuobiaoX + AAB * Ball_Width + Ball_SmallWidth + AAB * Ball_JIANGE;
							intY = QishizuobiaoY + AAE * Ball_Height + Ball_JIANGE;
						}
						//给发射的球固定的坐标 这个球就算是在数组里面安家了
						MyCur.Lie = AAB;
						MyCur.Hang = AAE;
						ArrMain[AAE][AAB] = MyCur;
						MyCur.Find(MyCur.Color_);
						//console.log(ArrMain[8][7])
						console.log(NowNum); //球
						if(NowNum < 3) {
							//没有消除
							playSound("_sconnect");

							createjs.Tween.get(this)
								//.to({alpha: 1}, 80)
								.to({
									alpha: 1
								}, 90)
								.call(function() {
									//console.log("removelonly");
									//removeLonly();
									IsFireOk = true;

									NowNum = 0;
									setFiest();
									LookGameOver();
								});

						} else {
							//有消除
							for(var i = 0; i < Num_HS_ZONG; i++) {
								for(var j = 0; j < Num_MeiHangGeShu; j++) {
									//遍历消除
									//console.log(rrMain[i][j].Used);
									if(ArrMain[i][j] != null && ArrMain[i][j].Used) {
										setchengji(arrchengji[ArrMain[i][j].MyboomTimer]);
										ArrMain[i][j].die();
										ArrMain[i][j] = null;
									}
								}
							}
							console.log("gudao" + NowNum); //球
							//删除孤岛
							createjs.Tween.get(this)
								//.to({alpha: 1}, 80)
								.wait(100 + Number * 80).to({
									alpha: 1
								}, 80)
								.call(function() {
									console.log("removelonly");
									removeLonly();
								});

						}

						//SetRow(AAE);
						//new TWEEN.Tween(MyCur.Position).to({x:intX,y:intY},500).repeat(Infinity).start();TWEEN.update();
						createjs.Tween.get(MyCur)
							.to({
								x: intX,
								y: intY
							}, 80)
							.call(function() {
								if(NowNum < 3 && !LookGameOver()) noremove();
							});
						/*var tween = createjs.Tween.get(MyCur.Position)
    							.to({x:300},500))
    							.wait(500).to({alpha:0,visible:false},1000)
    							.call(onComplete);
							*/
						createjs.Tween.get(MyNext)
							.to({
								scaleX: 0,
								scaleY: 0
							}, 100)
							.call(function() {
								MyCur = MyNext;
								MyCur.x = S._cur.x;
								MyCur.y = S._cur.y;
								MyNext = AddA(false);
								createjs.Tween.get(MyCur)
									.to({
										scaleX: scaleBall,
										scaleY: scaleBall
									}, 100);
							});
						Fire_Ball = false;
						//console.log(ArrMain[8][7])
						return;

					}
				}
			}
		}
		//trace("GO");
		//e.updateAfterEvent();	

	}
	var arrchengji = new Array(0, 0, 0, 30, 20, 20, 20, 30, 30, 30, 40, 40, 40, 50, 50, 50);
	var mychengji = 0;

	function setchengji(jiafen) {
		mychengji += jiafen;
		var CCJ = mychengji;
		var n1 = parseInt(CCJ / 1000000);
		CCJ -= n1 * 1000000;
		var n2 = parseInt(CCJ / 100000);
		CCJ -= n2 * 100000;
		var n3 = parseInt(CCJ / 10000);
		CCJ -= n3 * 10000;
		var n4 = parseInt(CCJ / 1000);
		CCJ -= n4 * 1000;
		var n5 = parseInt(CCJ / 100);
		CCJ -= n5 * 100;
		var n6 = parseInt(CCJ / 10);
		CCJ -= n6 * 10;
		S.d1.gotoAndStop(n6);
		S.d2.gotoAndStop(n5);
		S.d3.gotoAndStop(n4);
		S.d4.gotoAndStop(n3);
		S.d5.gotoAndStop(n2);
		S.d6.gotoAndStop(n1);
	}

	function LookGameOver() {
		if(!GameOver_) {
			for(var i = 0; i < Num_HS_ZONG; i++) {
				if(ArrMain[Num_HS_ZONG - 1][i] != null) //GameOver
				{

					console.log("GameOver");
					GameOver_ = true;
					IsFireOk = false;

					end.visible = true;
					_top.save(mychengji);//保存记录
					//end.visible = true;
					//游戏结束动窗口调用
					end.onComplete();

					playSound("_sgo");
					return true;
				}
			}
			return false;
		}
		return false;
	}

	var MaxErr = 5;
	var NowErr = 5;

	function noremove() {
		if(NowErr == 1) createjs.Tween.get(S.bb1).to({
			scaleX: 0,
			scaleY: 0
		}, 300)
		if(NowErr == 2) createjs.Tween.get(S.bb2).to({
			scaleX: 0,
			scaleY: 0
		}, 300)
		if(NowErr == 3) createjs.Tween.get(S.bb3).to({
			scaleX: 0,
			scaleY: 0
		}, 300)
		if(NowErr == 4) createjs.Tween.get(S.bb4).to({
			scaleX: 0,
			scaleY: 0
		}, 300)
		if(NowErr == 5) createjs.Tween.get(S.bb5).to({
			scaleX: 0,
			scaleY: 0
		}, 300)
		if(NowErr == 0) {
			MaxErr--;
			if(MaxErr == 0) MaxErr = 5;
			NowErr = MaxErr;
			if(NowErr >= 1) createjs.Tween.get(S.bb1).to({
				scaleX: scaleBall,
				scaleY: scaleBall
			}, 300)
			if(NowErr >= 2) createjs.Tween.get(S.bb2).to({
				scaleX: scaleBall,
				scaleY: scaleBall
			}, 300)
			if(NowErr >= 3) createjs.Tween.get(S.bb3).to({
				scaleX: scaleBall,
				scaleY: scaleBall
			}, 300)
			if(NowErr >= 4) createjs.Tween.get(S.bb4).to({
				scaleX: scaleBall,
				scaleY: scaleBall
			}, 300)
			if(NowErr >= 5) createjs.Tween.get(S.bb5).to({
				scaleX: scaleBall,
				scaleY: scaleBall
			}, 300)
			//增加一行
			ArrMain.unshift(new Array());
			nowMoveLine = !nowMoveLine;
			AddRow(0);
			for(var i = 0; i < Num_HS_ZONG; i++) {
				SetRow(i);
			}
			LookGameOver();
		} else
			NowErr--;
	}

	function removeLonly() {
		//孤岛
		for(var k = 0; k < Num_MeiHangGeShu; k++) {
			if(ArrMain[0][k] != null) ArrMain[0][k].Find("");
		}
		var RemoveNum = NowNum;
		//有消除
		for(var i = 0; i < Num_HS_ZONG; i++) {
			for(var j = 0; j < Num_MeiHangGeShu; j++) {
				//遍历消除
				//console.log(rrMain[i][j].Used);
				if(ArrMain[i][j] != null && !ArrMain[i][j].Used) {
					RemoveNum++;
					ArrMain[i][j].MyboomTimer = RemoveNum;
					setchengji(100);
					ArrMain[i][j].die();
					ArrMain[i][j] = null;
				}
			}
		}
		//删除孤岛
		createjs.Tween.get(this)
			.wait(110 + RemoveNum * 80).to({
				alpha: 1
			}, 80)
			.call(function() {
				console.log("reset");
				setFiest();
				IsFireOk = true;
				LookGameOver();
				NowNum = 0;
			});
	}

	function setFiest() {
		for(var i = 0; i < Num_HS_ZONG; i++) {
			for(var j = 0; j < Num_MeiHangGeShu; j++) {
				//遍历消除
				//console.log(rrMain[i][j].Used);
				if(ArrMain[i][j] != null) {
					ArrMain[i][j].Used = false;
				}
			}
		}
	}

	//反回箭头与鼠标之间的角度
	function BackJTLocation() {
		//console.log(ev.offsetX, ev.offsetY);  
		//console.log(ev.clientX, ev.clientY);  

		

		//箭头方向定位 没啥好说的 
		var dx = stage.mouseX - S.JT.x * stage.scaleX;
		var dy = stage.mouseY - S.JT.y * stage.scaleY;
		var location_ = Math.atan2(dy, dx) * 180 / Math.PI + 90;
		//坐标限额 防止超界
		//console.log(location_)
		if(location_ < -60 || location_ > 180)
			location_ = -60;
		else if(location_ > 60)
			location_ = 60;
		return location_;
	}

	function pingmushipei()
	{
		if(Mode)//宽屏
		{
			Num_MeiHangGeShu = 16;
			//Num_QiuHS_CS =1;
			QishizuobiaoX = 125;
			QishizuobiaoY = 115;
			Ball_JIANGE = 13;
			qiudi = 100;
			qiuzuo = 110;
			qiuyou = 1640;
			//S.AllClick.alpha =1;
			S.AllClick.scaleX = 3.5;
			S.AllClick.x	 = 50
			
			S.rsbtn.x = 1819;
			S.rsbtn.y = 234;
			S.setup.x = 1980;
			S.setup.y = 360;
			S.Top_10.x = 2117;
			S.Top_10.y = 187;
			S.url.x = 2167
			S.url.y = 550;
			
			var dy = 700;
			S.d0.y = S.d1.y =S.d2.y =S.d3.y =S.d4.y =S.d5.y =S.d6.y =dy;
			var dx_meige = -59;
			var dx = 2205
			S.d0.x = dx;
			S.d1.x = S.d0.x + dx_meige;
			S.d2.x = S.d1.x + dx_meige;
			S.d3.x = S.d2.x + dx_meige;
			S.d4.x = S.d3.x + dx_meige;
			S.d5.x = S.d4.x + dx_meige;
			S.d6.x = S.d5.x + dx_meige;
			
			S.JT.x = 855;
			S.JT.y = 1600;
			S._cur.x = 855;
			S._cur.y=1600
			S.bb3.y = S.bb4.y=S.bb5.y = S.bb1.y = S.bb2.y= S._next.y = S._cur.y
			var bx_meige = 121;
			S.bb3.x = S.bb2.x + bx_meige;
			S.bb4.x = S.bb3.x + bx_meige;
			S.bb5.x = S.bb4.x + bx_meige;
			
			_top.x	=600;
			setup.x	 =600;
			end.x	=600;
			
		}
		else{
			Num_MeiHangGeShu = 11;
			//Num_QiuHS_CS =1;
			QishizuobiaoX = 65;
			QishizuobiaoY = 60;
			Ball_JIANGE = 6;
			qiudi = 5;
			qiuzuo = 70;
			qiuyou = 1020;
			//S.AllClick.alpha =1;
			S.AllClick.scaleX = 3;
			S.AllClick.x	 = 0
			
			S.rsbtn.x = 167;
			S.rsbtn.y = 1799;
			S.setup.x = 416;
			S.setup.y = 1799;
			S.Top_10.x = 665;
			S.Top_10.y = 1799;
			S.url.x = 914
			S.url.y = 1799;
			
			var dy = 1581;
			S.d0.y = S.d1.y =S.d2.y =S.d3.y =S.d4.y =S.d5.y =S.d6.y =dy;
			var dx_meige = -59;
			var dx = 1010
			S.d0.x = dx;
			S.d1.x = S.d0.x + dx_meige;
			S.d2.x = S.d1.x + dx_meige;
			S.d3.x = S.d2.x + dx_meige;
			S.d4.x = S.d3.x + dx_meige;
			S.d5.x = S.d4.x + dx_meige;
			S.d6.x = S.d5.x + dx_meige;
			
			S.JT.x = 541;
			S.JT.y = 1535;
			S._cur.x = 541;
			S._cur.y=1535
			
			S.bb1.y = S.bb2.y= S._next.y = 1532;
			S.bb3.y = S.bb4.y=S.bb5.y = 1639;
			var bx_meige = 0;
			S.bb3.x = S._next.x + bx_meige;
			S.bb4.x = S.bb1.x + bx_meige;
			S.bb5.x = S.bb2.x + bx_meige;
			
			_top.x	=220;
			setup.x	 =250;
			end.x	=250;
		}
	}
};