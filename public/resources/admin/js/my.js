
//列表页checkbox选择
$('.checkbox-all').change(function(){
    if($(this).is(':checked')){
        $('.checkbox-item').prop('checked',true);
    }else{
        $('.checkbox-item').prop('checked',false);
    }
});
$('.checkbox-item').change(function(){
    if($('.checkbox-item').filter(':checked').length == $('.checkbox-item').length){
        $('.checkbox-all').prop('checked',true);
    }else{
        $('.checkbox-all').prop('checked',false);
    }
});