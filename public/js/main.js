var init_params = null;
var get_params = function() {
    var search = decodeURI(window.location.search), all_params = {};
    if(search.indexOf("?") >= 0) {
        search = search.substr(1);
        var params = search.split("&");
        for(var index = 0; index < params.length; index ++) {
            var str = params[index].split('=');
            all_params[str[0]] = str[1];
        }
    }

    return all_params;
}

var redirect = function(param) {
    var url = window.location.href, all_params = get_params();

    for(var k in param) {
        all_params[param[k]['key']] = param[k]['value'];
    }

    var param_str = "", all = [];
    for(var p in all_params) {
        if(all_params[p]) {
            all.push(p + "=" + all_params[p]);
        }
    }

    url = location.protocol + '//' + location.host + '?' + all.join('&');
    window.location.href = url;
}

function init() {
    var params = get_params(), init_option = ['area', 'price', 'order', 'huxing'];
    for(var k in init_option) {
        var name = init_option[k];
        if(params[name]) {
            var val = params[name].split(',');
            $('.selectpicker[data-name="'+name+'"]').selectpicker('val', val);
        }
    }

    init_params = params;
}

$(function(){
    $("button.btn.toggle").click(function(){
       $(this).toggleClass('active');
    });
    $(".dropdown-menu").on('click', function (e){
        e.stopPropagation();
    });
    $('.selectpicker').on('hidden.bs.select', function (e) {
        var value = $(this).val(), name = $(this).data('name');
        if(value){
            if(typeof value == 'object') {
                value = value.join(',');
            }

            if(!init_params[name] || init_params[name] != value) {
                redirect([{key: name, value: value}]);
            }
        }
    });

    $('#sure').click(function(){
        var subway = $('.btn-subway').hasClass('active'),
            direct = [], flood = [], elevator = $('.btn-elevator').hasClass('active');
        $('.btn-direct.active').each(function(){
            direct.push($(this).data('val'));
        });

        $('.btn-flood.active').each(function(){
            flood.push($(this).data('val'));
        });

        var params = [
            {key: 'subway', value: subway},
            {key: 'direct', value: direct.join(',')},
            {key: 'flood', value: flood.join(',')},
            {key: 'elevator', value: elevator},
        ];

        redirect(params);
    });

    $('#year').click(function(){
        var from = $('#from').val(), to = $('#to').val();
        if(!from && ! to) {
            alert("必须填一项");
            return;
        }

        var params = [
            {key: 'from', value: from},
            {key: 'to', value: to},
        ];

        redirect(params);
    });

    init();
});