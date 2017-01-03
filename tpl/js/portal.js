// 포탈삭제
function doDeletePortals() {
    var fo_obj = document.getElementById("fo_list");
    var titles = new Array();

    if(!fo_obj.cart) return;
    if(typeof(fo_obj.cart.length)=='undefined') {
        if(fo_obj.cart.checked) titles[titles.length] = fo_obj.cart.value;
    } else {
        var length = fo_obj.cart.length;
        for(var i=0;i<length;i++) {
            if(fo_obj.cart[i].checked) titles[titles.length] = fo_obj.cart[i].value;
        }
    }

    if(titles.length<1) return;
    if(!confirm(delete_message)) return;

    var params = new Array();
    params['titles'] = titles.join('|');
    exec_xml('portalpoint','procPortalpointAdminDeletePortal', params, completeDeletes);
}
// 로그삭제
function doDeleteLog() {
    var fo_obj = document.getElementById("fo_list");
    var log_srls = new Array();

    if(!fo_obj.cart) return;
    if(typeof(fo_obj.cart.length)=='undefined') {
        if(fo_obj.cart.checked) log_srls[log_srls.length] = fo_obj.cart.value;
    } else {
        var length = fo_obj.cart.length;
        for(var i=0;i<length;i++) {
            if(fo_obj.cart[i].checked) log_srls[log_srls.length] = fo_obj.cart[i].value;
        }
    }

    if(log_srls.length<1) return;
    if(!confirm(delete_message)) return;

    var params = new Array();
    params['log_srls'] = log_srls.join(',');
    exec_xml('portalpoint','procPortalpointAdminLogDelete', params, completeDeletes);
}
// 로그 관리 -> 검색
function FormSubmit(key,val) {
    document.getElementById(key).value = val;
    document.getElementById('adminSearch').submit();
}

/* 일괄 삭제 후 */
function completeDeletes(ret_obj) {
    alert(ret_obj['message']);
    location.reload();
}

function completeInsertPortal(ret_obj) {
    var error = ret_obj['error'];
    var message = ret_obj['message'];

    alert(message);

    var url = current_url.setQuery('target','');
    location.href = url;
}
