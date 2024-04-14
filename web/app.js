if( ! window.console ) console = { log: function(){} };

var DEPT_ID = {
	1:'Production',
	2:'Warehouse',
	3:'Quality',
	4:'Engineering',
	7:'Test'
};

var DEPT_AREA = {
	'Production' :[{area:'vst', label:'VST'}, {area:'vsc', label:'VSC'}, {area:'vsd', label:'VSD'}, {area:'vsd2', label:'VSD2'}, {area:'wip', label:'WIP'}],
	'Warehouse'  :[{area:'warehouse', label:'Warehouse'}],
	'Quality'    :[{area:'refrigerator', label:'Refrigerator'}],
	'Engineering':[{area:'ws', label:'Water System'}, {area:'ahu', label:'AHU System'}],
	'Test':[{area:'Test', label:'Test Area'}, {area:'Historian', label:'Historian Simulation Sample'}]
};

function bms(){
  this.IS_LOGGED = false;
	this.IS_GUEST = true;
	this.IS_ADMIN = false;
	this.DEPT = null;
  this.REPORT = {};
	this.MENU_AREA = null;
  this.AJAX_RUN = false;
	this.AJAX_LAST_REQ='';
  this.REQUEST_TREND = false;
  this.HAS_DATA_INTV = false;
  this.disableTrend = false;
  this.connector;
  this.getUrl = function (){
		var arr =window.location.pathname.split('/');
		return {script:arr.pop(),path:arr.join('/')};
	},
  this.setLoader = function(){
    if(this.REQUEST_TREND) return;
    $('.loader').show();
    $('.wrap').addClass('wait').parent().addClass('wait');
  },
  this.removeLoader = function(){
    $('.loader').hide();
    $('.wrap').removeClass('wait').parent().removeClass('wait');
  },
  this.validateDate=function(){
		var now=new Date, d = $("#dt").val(), ds= $("#ds").val(), de= $("#de").val();
		var d = (d) ? new Date(d) : null;
		var ds = (ds) ? new Date(ds) : null;
		var de = (de) ? new Date(de) : null;		console.log('now='+now +', date='+d+', datestart='+ ds+', dateEnd='+de);		//alert(ds > de);

		if((d==null||d=='1970-01-01'||d > now) && (ds==null||de==null||ds=='1970-01-01'||de=='1970-01-01'||ds > now||ds > de)){  //alert('date salah');
			if(d==null && (ds==null || de==null)){
				this.setMsg("Please Completed Field Date");
			}else if(d > now){
				this.setMsg("That date is in the future");
			}else if(ds > now){
				this.setMsg("That date start is in the future");
			}else if(ds > de){
				this.setMsg("That date start greater than date end");
			}
			return false;
		}
		return true
	},
	this.setMsg = function(msg, cls, icon,fokus){	console.log('set message-msg:'+msg);
		if(!msg) return;
		cls = cls ? cls : 'ui-state-error';
		icon = icon ? icon : 'ui-icon-alert';
		if(typeof msg=='object'){
			var err =[];
			$.each(msg, function(i,e){	console.log('msg :' + e);
				err.push("<span class='ui-icon "+icon+"'></span> <div class='left'>"+e+"</div><br>");
			}); console.log(err);
			msg = err.join(""); console.log(msg)
		}
		else{
			msg ="<span class='ui-icon "+icon+"'></span><div>"+msg+"</div>"
		}
		$('#msg-con').removeClass('ui-state-error ui-state-highlight');
		$('#msg-con').addClass(cls).fadeIn('slow').find('.msg').html(msg);
		if(fokus)$(fokus).focus();  console.log('done set msg');
	},
  this.dismisMsg = function(){  console.log('msg dismiss..');
    if(!$('#msg-con').is(':hidden')){
			$('#msg-con').fadeOut('slow').find('.msg').html('');
		}
  },
	this.synTable=function(tbl_div){
		var tbl_div = tbl_div ? tbl_div : $('#table_div');
		var tbl_header = tbl_div.prev();
		var colSizer = tbl_header.find('tr.sizer th');
		var colCount = colSizer.length;
		var tbl_data = tbl_div.find('table');
		var rowCount = tbl_data.find('tr').length;
		var size, scrollbar=0;
		console.log('Syncronize table..', tbl_div[0]);
		// $('#table_div_dialog table tbody tr:first td')
		// $('#dialog-data tr.sizer th:eq(1)')
		colSizer.each(function(i){
			if(i < colCount){
				var el = tbl_data.find('td:eq('+ i +')');
				var size = el.width();
        if(colCount == i+1) {
          scrollbar = ((rowCount > 17) ? 17 : 0);  // give 15px for scroll tab width
          size = size + scrollbar; 
        }
				// alert(size);
        // $(this).css('width',size);
				$(this).width(Number.parseInt(size));
				// console.log(i, scrollbar, 'size:', size, $(this).width());
			}
		});
	},
	this.setActiveMenu = function(){
		//$('#menu li').removeClass('active');
		//$('#controll li').removeClass('active');
		var sc = url.script; console.log('SET ACTIVE LINK url:', sc);
		if(sc){
			if($.inArray(sc, ['userlogs','userdirs', 'tagnames', 'admin']) !=-1){
				console.log('set active admin');
				$('#menu #admin').addClass('active')
			}
			else{
				console.log('set active area:'+sc, bms);
				$('#menu #'+sc).addClass('active');
			}
		}
		$("#menu-dept li a:contains("+this.MENU_AREA+")").parent().addClass('active disabled');
		//else if($.inArray(sc, ['userlogs','userdirs', 'tagnames', 'admin']) !=-1){  console.log('set active admin');}
	}
	this.changeMenu = function(){  console.log('Change menu to:'+this.MENU_AREA);
		//$('#area-list').fadeOut('fast').html('').fadeIn(1000);
		$('#area-list').html('').fadeOut('fast');
		var el = [];
		var path = this.MENU_AREA=='Test' ? '/bmc2/historian/report/' : '/bmc2/report/';
		$.each(DEPT_AREA[this.MENU_AREA], function(i,v){	//console.log(i, v);
			//var n = v.toLowerCase();
			var url = path + v.area;
			el.push('<li id="'+v.area+'"><a href="'+url+'">'+v.label+'</a></li>');
		});
		$('#area-list').promise().done(function() {
			$(this).fadeIn().html(el.join(''))
				.promise()
				.done(function(){
					bms.setActiveMenu()
				});
		});
		if(this.MENU_AREA=='Warehouse') $('#summary').fadeIn();
		else $('#summary').fadeOut();
		return $('#area-list').html();
	}
	this.createMenu = function(){  console.log('=>Create Menu:', bms.DEPT);
		this.MENU_AREA = DEPT_ID[bms.DEPT];
		return this.changeMenu();
	}
	this.registerUser = function(){
		if(!bms.IS_LOGGED){
		
		}
	}
	this.selectDate = function(date){
    if($('#report-ismultidate').val()) return;
    bms.getReport();
  }
  this.getReport = function(print){	console.log('GET REPORT..', print);
    event.preventDefault();
    print = (print=='pdf' || print=='xls') ? print : '';
    $('#report-isprint').val(print);
    $('form').submit();
  }

}
var bms = new bms(), url=bms.getUrl(), isConnect=0, mobileDis=0, tagLoaded=false, isAdmin=false;

function validateDate(){
		var now=new Date, d = $('#report-date').val(), de= $('#report-dateto').val();	
		d = formatDate(d);
		de = formatDate(de);
		//d = (d) ? new Date(d) : null;
		//de = (de) ? new Date(de) : null;		
		console.log('now='+now +', date='+d+', dateEnd='+de);		//alert(ds > de);

		if(d=='1970-01-01' || de=='1970-01-01'){ 
			setMsg('Invalid Date');
			return false;
		}else if(d==null || de==null){
			setMsg('Please Completed Field Date','ui-state-error1','ui-icon-alert');
			return false;
		}else if(d > now){
			setMsg('Date start is in the future', 'ui-state-error1','ui-icon-alert');
			return false;
		}else if(de > now){		
			setMsg('Date end is in the future','ui-state-error1','ui-icon-alert');
			return false;
		}else if(d > de){
			setMsg('That date start greater than date end','ui-state-error1','ui-icon-alert');
			return false;
		}
		return true
}

function formatDate(date){
	if(!date) return;
	var day, month, year;
	d = date.split('-');
	return new Date(d[2]+" "+d[1]+" "+d[0]);
}

function setMsg(msg, cls, icon,fokus){	console.log('set message-msg:'+msg);
		if(!msg) return;
		msg ='<span class=\'left '+icon+'\' style=\'margin:1.5px 5px 0px 0px\'></span> <div class=\'left\'><strong>Warning!</strong> '+msg+'.</div>'
		//$('#msg-con').removeClass('ui-state-error ui-state-highlight');
		$('#msg-con').addClass(cls).fadeIn().find('.msg').html(msg);
		if(fokus)$(fokus).focus();  console.log('done set msg');
}

function registerScroll(el) {
  var time_marker = $('#dialog-data').find('.time_marker');
  var max_top = 1;
  var max_bottom = el.height() + el[0].offsetTop;

  // row-top: 30, row-bottom: 378
  // $('#dialog-data').position()  {top: 34.140625, left: 3.078125} height: 402.703
  // tbl_div {top: 41.703125, left: 3}, height: 370
  // 41-30 = 11, 370-378 = 8 padding
  console.log('registerScroll:', el[0].scrollHeight, el[0]);
  //var scroll_tm;
  el.scroll(function (e) {
    //clearTimeout(scroll_tm);
    //scroll_tm = setTimeout(function(){
    var pos_y = Math.round(time_marker.position().top);

      //var w_bottom = Math.round($(document).height() - this.innerHeight);
      // console.log(`pos_y:${pos_y} max_bottom:${max_bottom} time_marker:`,time_marker.position(), (pos_y > max_top), (pos_y < max_bottom));
      if(pos_y > max_top && pos_y < max_bottom){
        if(bms.connector.end != time_marker[0])
					bms.connector.setOptions({ end:time_marker[0] });
        bms.connector.position();
      } else {
        //bms.connector.setOptions({ end:el[0] });
      }
    //}, 300);
  });
}

function getTrendData(){
  if(bms.AJAX_RUN || bms.disableTrend) return;
  console.log('getTrendData AJAX_RUN:', bms.AJAX_RUN);
  bms.REQUEST_TREND = true;
  $.get(yii.getBaseCurrentUrl()+"/bmc2/api/trend/historian", function( data ) {
    bms.REQUEST_TREND = false;
    if(data.error){
      if(bms.HAS_DATA_INTV){
        $("tr.sizer th").each(function(i, el){ console.log(i,el);
          $(el).removeClass().text('N/A');
        });
        bms.synTable();
      }
      bms.HAS_DATA_INTV = false;
      return;
    }

    $.each(data, function(i, val){ //console.log(i, val);
      $("tr.sizer th:eq("+i+")").text(val.data).removeClass().addClass(val.class);
    });
    if(!bms.HAS_DATA_INTV) bms.synTable();  // when has error occur and back to normal
    bms.HAS_DATA_INTV = true;
    if(bms.DATA_INTV_ISOPEN) return;
    if(bms.HAS_DATA_INTV){  console.log('SHOW DATA INTV');
      $('#table_div').css('margin-top', -1);
      bms.DATA_INTV_ISOPEN = true;
    }
  });
}

function getTrendDataIntv() { console.log('getTrendDataIntv');
  setInterval(function(){
    getTrendData();
  }, 10000);
}

function registerRowClick() {  console.log(' > registerRowClick');
  $('table.report tbody tr').on('click', function(e){
    $(this).parent().find('.time_marker').removeClass('time_marker');
		$(this).addClass('time_marker');
    var el = $(this).find('td:first')
    getRowDetail(el);
  });
}

function createConnectorLine(el1, el2){   console.log('Make connector:', el1, el2);
  // bms.connector.start, bms.connector.end
  bms.connector = new LeaderLine(
    el1,
    el2,
    //$('.ui-dialog-titlebar')[0],
    //LeaderLine.pointAnchor($('.ui-dialog-titlebar')[0], {x:0, y:'200%'}),
    {
      //color:'green',
      startPlug: 'square',
      startSocket: 'left',
      startSocketGravity: 11,
      //endSocketGravity: [-10, -20],
      path: 'grid',
      size: 3,
      dash: true
    });
}

function getHistorian(param, cb){
	var url = yii.getBaseCurrentUrl()+'/bmc2/historian/report-detail'; //'http://localhost/bmc2/api/historian',
  console.log('> getHistorian:', param, url);
	bms.AJAX_LAST_REQ = url;
  $.ajax({type:'POST', url:url, data:param})
  .done(function(data){
    //console.log(data);
    $('#dialog-data').html(data);
    if(cb) cb();
  });
}

function renderDialog(el, time, timeStart, timeEnd){
	// var div_el; //= $('#table_div_dialog');
    var dialog_el = $('#dialog-data');
    
    if(!dialog_el.dialog('isOpen')){
			// var div_el = $('#table_div_dialog');
			dialog_el.dialog('open');
			// registerScroll(div_el);
			$('.ui-dialog #form-his').remove();
		
			var txt ='<form id="form-his"><span><b>time-start:</b> '+ timeStart + '</span>&nbsp;'
				+' <span><b>time-end:</b> '+ timeEnd + '</span>&nbsp;' 
				+' <span><b>interval: </b><select id="interval" name="intv"><option value="1m">1min</option><option value="5m">5min</option><option value="10m">10min</option></select></span>'
				+' <span><b>sampling-mode: </b><select id="samplingMode" name="smode"><option value="Calculated">Calculated</option><option value="currentvalue">currentvalue</option>'
				+'<option value="trend">Trend</option><option value="interpolated">interpolated</option><option value="Lab">Lab</option></select></span>'
				+'</form>';
			$('.ui-dialog-titlebar').after(txt);
			$('.ui-dialog-title').append('<span class="loader">&nbsp;&nbsp;</span>');
			
			$('#form-his select').on('change', function(){
				var intv = $('#interval').val();
				var smode = $('#samplingMode').val();
				if(smode=='trend' && intv=='1m'){
					$('#interval').val('5m');
					intv = '5m';
				}
				getHistorian({'Historian[time_marker]':time, 'Historian[areaName]':bms.REPORT.AREA, 'Historian[startTime]':timeStart, 'Historian[endTime]':timeEnd, 'Historian[interval]':intv, 'Historian[samplingMode]':smode},
				function(){renderDialog(el, time, timeStart, timeEnd);}
				);
			});
		}else{
			// time_marker = $('#dialog-data .time_marker');
			// createConnectorLine(el[0], time_marker[0]);
			// bms.connector.end = time_marker[0];
			// var div_el = $('#table_div_dialog');
			// var pos = (div_el[0].scrollHeight - div_el.offset().top - 100) / 2;
			// div_el.scrollTop(pos);
		}
		var div_el = $('#table_div_dialog');
		registerScroll(div_el);
		var time_marker = $('#dialog-data .time_marker');
		if(!time_marker.length)
			time_marker = $('.ui-dialog');
		if(bms.connector) bms.connector.remove();
		createConnectorLine(el[0], time_marker[0]);
		var pos = (div_el[0].scrollHeight - div_el.offset().top - 100) / 2;
		div_el.scrollTop(pos);
		bms.connector.position();
}

function getRowDetail(el){
  // http://localhost/bmc2/report/vst/01-01-2022
  var tr = el.parent();
  var time = tr.attr('data-time');
  var timeStart = tr.prev().attr('data-time');
  var timeEnd = tr.next().attr('data-time');

  if(!timeStart){
    timeStart = getTimeModif(time, -30);
  }
  if(!timeEnd){
    timeEnd = getTimeModif(time, +30);
  }

  console.log(' > getRowDetail', el[0], `\ntime:"${time}" time-start:"${timeStart}" time-end:"${timeEnd}"`);
  getHistorian(
		{'Historian[time_marker]':time, 'Historian[areaName]':bms.REPORT.AREA, 'Historian[startTime]':timeStart, 'Historian[endTime]':timeEnd, 'Historian[interval]':'1m'}, 
		function(){renderDialog(el, time, timeStart, timeEnd);}
	);
}


function getRowDetail_old(el){
  // http://localhost/bmc2/report/vst/01-01-2022
  var tr = el.parent();
  var time = tr.attr('data-time');
  var timeStart = tr.prev().attr('data-time');
  var timeEnd = tr.next().attr('data-time');

  if(!timeStart){
    timeStart = getTimeModif(time, -30);
  }
  if(!timeEnd){
    timeEnd = getTimeModif(time, +30);
  }

  console.log(' > getRowDetail', el[0], `\ntime:"${time}" time-start:"${timeStart}" time-end:"${timeEnd}"`);
  getHistorian({'Historian[time_marker]':time, 'Historian[areaName]':bms.REPORT.AREA, 'Historian[startTime]':timeStart, 'Historian[endTime]':timeEnd, 'Historian[interval]':'1m'}, 
	function(){
		var div_el = $('#table_div_dialog');
    var dialog_el = $('#dialog-data');
    var time_marker = $('#dialog-data .time_marker');
    if(!dialog_el.dialog('isOpen')){
			dialog_el.dialog('open');
			registerScroll(div_el);
			$('.ui-dialog #form-his').remove();
		
			var txt ='<form id="form-his"><span><b>time-start:</b> '+ timeStart + '</span>&nbsp;'
				+' <span><b>time-end:</b> '+ timeEnd + '</span>&nbsp;' 
				+' <span><b>interval: </b><select id="interval" name="intv"><option>1min</option><option value="5m">5min</option><option value="10m">10min</option></select></span>'
				+' <span><b>sampling-mode: </b><select id="samplingMode" name="smode"><option value="Calculated">Calculated</option><option value="currentvalue">currentvalue</option>'
				+'<option value="Trend">Trend</option><option value="interpolated">interpolated</option><option value="Lab">Lab</option></select></span>'
				+'</form>';

			// $('.ui-dialog-titlebar').append('<div class="detail">'+txt+'</div>');
			// $('.ui-dialog-title').append('<span class="detail" >'+txt+'</span>');
			$('.ui-dialog-titlebar').after(txt);
			// $('#interval').val('1m');
			// $('#samplingMode').val('1m')
			// if(bms.connector)
				// console.log('CONECTOR: start', bms.connector.start.offsetTop, 
				// 'end:', bms.connector.end.offsetTop,
				// 'marker:', $('#table_div_dialog .time_marker')[0].offsetTop);
			// bms.synTable($('#table_div_dialog'));
			// createConnectorLine(el[0], dialog[0]);
			createConnectorLine(el[0], time_marker[0]);
			
			var pos = (div_el[0].scrollHeight - div_el.offset().top - 100) / 2;
			// var pos = div_el[0].offsetHeight / 2;
			div_el.scrollTop(pos);
			$('#form-his select').on('change', function(){
				getHistorian({'Historian[time_marker]':time, 'Historian[areaName]':bms.REPORT.AREA, 'Historian[startTime]':timeStart, 'Historian[endTime]':timeEnd, 'Historian[interval]':$('#interval').val(), 'Historian[samplingMode]':$('#samplingMode').val()},
				function(){
					// createConnectorLine(bms.connector.start, bms.connector.end);
					getRowDetail(el);
				});
			});
		}else{
			time_marker = $('#dialog-data .time_marker');
			createConnectorLine(el[0], time_marker[0]);
			var pos = (div_el[0].scrollHeight - div_el.offset().top - 100) / 2;
			// var pos = div_el[0].offsetHeight / 2;
			div_el.scrollTop(pos);
		}
  });
}
// modify time for after +30 / before -30 min
function getTimeModif(date, modif){ console.log('getTimeModif:', date, modif, (modif == +30));
  var date = new Date(date);
  date.setMinutes(date.getMinutes() + modif);
  if(modif == +30){
    if(date > new Date()) date = new Date();
  }
  return formatDate(date);
}

// Get Format time from unix timestamp
function formatDate(date) {   console.log('formatDate:', date, date.getHours());
  if(! date instanceof Date){
    date = new Date(date);
  }
  var month_str = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  var month_num = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
  var year = date.getFullYear();
  var monthstr = month_str[date.getMonth()];
  var monthNum = month_num[date.getMonth()];
  var dt = date.getDate();
  var hour = date.getHours();
  var min = date.getMinutes();
  var sec = date.getSeconds();
  //var d = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec;
  return year +'-'+monthNum+'-'+dt+' '+hour+':'+min;
}

$(document).ready(function(){	console.log('app.js READY FN...', "SCRIPT:"+bms.getUrl().script);
	$(document)
  .on('pjax:start', function (e, xhr, opt) {
    console.log('PJAX START..', e, xhr, opt);
  })
  .on('ajaxStart', function (e) {
    console.log('AJAX-START..', bms.AJAX_LAST_REQ);
    bms.setLoader();
    bms.AJAX_RUN = true;
  })
	.on('pjax:complete', function(el,res,stat) {
		console.log('PJAX COMPLETE..', res.getResponseHeader('x-pjax-url'), res.getAllResponseHeaders(), res.state());
		var xurl = res.getResponseHeader('x-pjax-url'); 
		if(xurl==yii.getBaseCurrentUrl()+'/bmc2/user/login'){
			bms.setMsg('Your session has exprired');
			return window.location = xurl;
		}
		if(url.path.match('bmc2/report') || url.path.match("/bmc2/summary")){
      bms.synTable();
      registerRowClick();

      // ADD FOR TREND COLUMN ON REPORT
      if(bms.MENU_AREA=='Test'){
        // getTrendData();
        // getTrendDataIntv();
      }else {
        //$('#table_div').css('margin-top', -Math.ceil($('table.report tr.sizer').height()));
      }
    }
	})
  .on('ajaxStop', function (e) {
    console.log('AJAX-STOP..', bms.AJAX_LAST_REQ);
		if(bms.AJAX_LAST_REQ.match('historian/report-detail')){
			bms.synTable($('#table_div_dialog'));
		}			
    bms.removeLoader()
    bms.AJAX_RUN = false;
		bms.AJAX_LAST_REQ = '';
  })
  .on("ajaxError", function(a, b, c){ console.log('ajaxError...', a, b, c);
		//$("#loading").hide();
	});
	
	console.log('isGuest:'+$('#isGuest').val(), 'isAdmin:'+$('#isAdmin').val(), 'xdept:'+$('#xdept').val());
	if($('#isGuest').val()){ 
		bms.IS_LOGGED = 0;
		bms.IS_GUEST = 1;
		bms.IS_ADMIN = 0;
	}
	else{
		if(!bms.IS_LOGGED){
			bms.IS_LOGGED = 1;
			bms.IS_GUEST = 0;
			bms.IS_ADMIN = $('#isAdmin').val();
			bms.DEPT = $('#xdept').val();
		}
	}
	
	if(bms.IS_LOGGED){
		console.log('LOGGED SET FIRST MENU AREA..', bms.MENU_AREA, bms);
		if(!bms.MENU_AREA) bms.createMenu();
		//if(bms.)
		//bms.setActiveMenu();
		
		// var promise = new Promise(function(resolve, reject) {  console.log('Promise..');
			// resolve(bms.createMenu());
		// });

		// promise.then(function(value) {  console.log('Promise value:', value);
			// bms.setActiveMenu();
		// });
	}
	else console.log('NOT LOGGED!');
	
	$("#menu-dept ul li a").on('click', function(){//console.log($(this).text());
		//$('#menu li').removeClass('active');
		$("#menu-dept ul li").removeClass('active disabled');
		$(this).parent('li').addClass('active disabled');
		bms.MENU_AREA = $(this).text();
		console.log(bms.MENU_AREA, $("#menu-dept > a").text(), (bms.MENU_AREA==$("#menu-dept > a").text().trim()));
		if(bms.MENU_AREA != $("#menu-dept > a").text().trim()){
			$("#menu-dept > a").html( bms.MENU_AREA + ' <span class="caret"></span>');
			bms.changeMenu();
		}
	});
});

var rt;
// window.onresize = function(e){ //console.log(e.type);
$(window).on('resize', function(e){ //console.log(e.type);
  clearTimeout(rt);
  rt = setTimeout(function(){
    console.log('Resize..');
    if(url.path.match('bmc2/report') || url.path.match("/bmc2/summary")){
      bms.synTable();
    }
  }, 1000);
})
