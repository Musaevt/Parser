var lineChartData;
 
var data={
     function:'get_rating_groups',
     without:['abbyymobile','club18763860']
};
        $.ajax({ // для краткости - jQuery
             type:'POST',
             url: '/index.php',
             data:data,
             dataType: "json",
	     success:function(data){
                 var lab=[];
                 var uchastniki=[];
                for (var i = 0; i < data.length; i++) {
              lab[i]=data[i].name;
              uchastniki[i]=parseInt(data[i].our_members)/parseInt(data[i].members_count);
             }
              lineChartData = {
			labels : lab,
			datasets : [
				{
					label: "My First dataset",
					fillColor : "rgba(220,220,220,0.2)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(220,220,220,1)",
					data : uchastniki
				}
			]
                       

		},
                   getgraph();
                },
             error:  function(xhr, str){
                    alert('Error conecting with server');
                },
        

             cache: false

            });
            
   
  var getgraph = function(){
					var ctx = document.getElementById("canvas").getContext("2d");
					window.myLine = new Chart(ctx).Line(lineChartData, {
						responsive: true
					});};
 
var getAjax=function(requestData){
     $.ajax({ // для краткости - jQuery
             type:'POST',
             url: '/api',
             data:requestData,
             dataType: "json",
	     success:function(data){
                 return data;
             },
               
             error:  function(xhr, str){
                    alert('Error conecting with server');
                },
        
             cache: false

            });
}