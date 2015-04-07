var placeholder='/st/img/placeholder.jpg';

$(document).ready(function(){
    placeholder=  $('img.element_form')[0].src;
    
 






var getAjax=function(requestData){
     $.ajax({ // для краткости - jQuery
             type:'POST',
             url: '/api/method',
             data:requestData,
             dataType: "json",
	     success:function(data){
               
                 if(data.error_code){
                  $('img.element_form').last().hide();
                     if($('div.error_api').length==0)
                     $('<div class="error_api">').text(data.error_msg).appendTo('div.group');
                 }else{
                 $('div.error_api').remove(); 
                 $('img.element_form')[0].src=data.photo_big;
                  
                 }
                  return data;
             },
               
             error:  function(xhr, str){
                 if(xhr.status&&xhr.status==200){
                 $('input.element_form')[0].value="";
                    $('img.element_form')[0].src=placeholder;
                    alert('VK haven`t group with name: '+requestData.group_name);
                 }
               else{
                   alert('Error:Please try later');
                  }
                },
        
             cache: false

            });
        };

$('input.element_form').change(function(e){
     var img =$(e.target).next();
     (!img.is(":visible"))?img.show():0;
      getAjax({method_name:"get_community_by_id_vk",data:{group_name:e.target.value}});
     
  });
  
});