/**
 * sns share function 
 */

function postToWb(title)
{
    var _url = encodeURIComponent(document.location);
    var _assname = encodeURI("cdcchen");
    var _appkey = encodeURI("8fcd940fb4e54897ad5d07320468d8fd");
    var _pic = encodeURI('');
    var _t = title;
    if (_t.length > 120){
        _t= _t.substr(0,117)+'...';
    }
    _t = encodeURI(_t);

    var _u = 'http://share.v.t.qq.com/index.php?c=share&a=index&url='+_url+'&appkey='+_appkey+'&pic='+_pic+'&assname='+_assname+'&title='+_t;
    window.open( _u,'', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );
}
