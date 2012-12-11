$(function(){

    // 鼠标hover提示
    $('.tipS').tipsy({gravity: 's',fade: true, html:true});
    $('.tipN').tipsy({gravity: 'n',fade: true, html:true});

    // input 样式
    $("select, .check, .check :checkbox, input:radio, input:file").uniform();

    // sidebar二级
    $('li.sideBarDrop').click(function () {
		$(this).children().eq(1).slideToggle(200);
	});
    $(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("sideBarDrop"))
		$(".leftUser").slideUp(200);
	});

    // 全屏
    $('.doFullscreen').toggle(function() {
        var target_id = $(this).attr('caction');
        $('#'+target_id).addClass('fullscreen');
        $('#content').css('position', 'static');
        $('#sidebar').addClass('hide_important');
    }, function() {
        var target_id = $(this).attr('caction');
        $('#'+target_id).removeClass('fullscreen');
        $('#content').css('position', 'relative');
        $('#sidebar').removeClass('hide_important');
    });

    // 展开表格列表
    $('.tOptions[ckey]').click(function(){

        var ckey = $(this).attr('ckey');

        if($(this).hasClass("act")) {
            $('.tablePars').slideUp(200);
        } else {
            $('.tablePars').slideUp(200, function(){
                $('.tOptions[ckey]').each(function(){
                    var this_ckey =  $(this).attr('ckey');
                    $('#' + this_ckey).hide();
                });
                $('#' + ckey).show();
            });
            $('.tablePars').slideDown(200);
        }

        $(this).toggleClass("act");
        $('.tOptions[ckey]').not(this).removeClass('act');
    });

    // tab 选项
	$.fn.contentTabs = function(){ 
	
		$(this).find(".tab_content").hide();
		$(this).find("ul.tabs li:first").addClass("activeTab").show();
		$(this).find(".tab_content:first").show();
	
		$("ul.tabs li").click(function() {
			$(this).parent().parent().find("ul.tabs li").removeClass("activeTab");
			$(this).addClass("activeTab");
			$(this).parent().parent().find(".tab_content").hide();
			var activeTab = $(this).find("a").attr("href");
			$(activeTab).show();
			return false;
		});
	
	};
	$("div[class^='widget']").contentTabs();

    // 下拉菜单 
    $('.dropdown-toggle').dropdown();

    // 快捷导航下拉
	$('.breadLinks ul li').click(function () {
		$(this).children("ul").slideToggle(150);
	});
	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("has"))
		$('.breadLinks ul li').children("ul").slideUp(150);
	});

    // 获取title
    var title = 'UFC';
    $('#breadcrumbs').find('a').each(function(){
        title += ' - ' + $(this).text();
    });
    $('title').text(title);

    /***************************************************************************
     *
     * databtales 搜索 api
     *
     * by william
     *
     ***************************************************************************/

    /**
     * 清空搜索条件
     *
     * <code>
     *    oTable.fnFilterClear();
     * </code>
     * 
     * @param: oSettings object datatables的设置
     *
     * return void
     */
    $.fn.dataTableExt.oApi.fnFilterClear  = function ( oSettings ) {
        // 清空定义的搜索
        for ( var i=0, iLen=oSettings.aoPreSearchCols.length ; i<iLen ; i++ ) {
            oSettings.aoPreSearchCols[i].sSearch = "";
        }

        // 需清空表单值 待添加
        
        // 重载table
        oSettings.oApi._fnReDraw( oSettings );
    };

    /**
     * 设置搜索条件
     *
     * <code>
     *  oTable.fnSetFilter(1, '已支付');
     * </code>
     *
     * @param: oSettings object datatables设置
     * @param: index     integer 搜索列索引
     * @param: value     string  搜索内容
     *
     * return void
     */
    $.fn.dataTableExt.oApi.fnSetFilter = function(oSettings, index, value) {
        oSettings.aoPreSearchCols[index].sSearch = value;
    }

    /***********************************************************************/
});
