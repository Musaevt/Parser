
$(document).ready(function(){
    var i = 0; //это будет наш счетчик, заодно используем его для формирования класса file-0x
    
    
    var groups=$('.groups');
    
     addChange(groups.children());
    
    
    
    $('#add_group').click(function(e){
    i++; //крутим счетчик на 1
    $("<input type='text' class='vk_groups'>").appendTo('.groups');
    //добавляем в див files еще одно поле для загрузки
      if(i>=2){ //если наш счетчик насчитал, что мы уже 4 поля добавили, прячем кнопку
         $(this).hide();                    
      }
    return false;
    });

  
});
var addChange=function(group){
  var i=0;  
    console.log(group.last().children('.vk_group'));
 var data={
       request_name:"get_group_by_id",
       group_name:group.last().children('.vk_group')[0].value
   }
 
    group.change(function(e){
       var newgroup=addGroup();
       addChange(newgroup);
       getAjax(data,i);
       i++;
   
   
        
    })
}
var addGroup=function(){
    return $(" <div class='group'><input type='text' class='vk_group'><img src='/st/img/placeholder.jpg' class='vk_group_logo'></div>").appendTo('.groups'); 
}





var getAjax=function(requestData,id){
     $.ajax({ // для краткости - jQuery
             type:'POST',
             url: '/api',
             data:requestData,
             dataType: "json",
	     success:function(data){
                 $('.vk_group_logo')[id].src=data.photo_medium;
                 return data;
             },
               
             error:  function(xhr, str){
                    alert('Error conecting with server');
                },
        
             cache: false

            });
}

var data={
    request_name:"get_group_by_id",
    group_name:"vkwhy"
}

getAjax(data,0);