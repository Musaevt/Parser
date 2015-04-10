$(document).ready(function(){
    
    var lineChartData ,search_id,a;
    
    search_id=$('#search_id').html();
    a=Ajax.get({url:"/api",data:{method_name:'get_rating_communities_from_search_percent',search_id:search_id,count:10}},function(a){
        
       var data= lineChartData(a,'screen_name','persent','graph');
       var ctx = document.getElementById("percent").getContext("2d");
		window.myLine = new Chart(ctx).Line(data, {
			responsive: true
		})});
    a=Ajax.get({url:"/api",data:{method_name:'get_rating_communities_from_search_count',search_id:search_id,count:10}},function(a){
        
       var data= lineChartData(a,'screen_name','counts','graph');
       var ctx = document.getElementById("count").getContext("2d");
		window.myLine = new Chart(ctx).Line(data, {
			responsive: true
		})});
    a=Ajax.get({url:"/api",data:{method_name:'get_rating_members',search_id:search_id,count:10}},function(a){
        
       var data= lineChartData(a,'first_name','communities_count','graph');
       var ctx = document.getElementById("members_community").getContext("2d");
		window.myLine = new Chart(ctx).Line(data, {
			responsive: true
		})});
    
    
    
    
    
    
    
    
    
    
    
    
    lineChartData = function(a,labels,data,name){
       var array_data=[];
       var array_labels=[];
        for(var i=0;i < a.length;i++){
            array_labels[i]= a[i][labels];
            array_data[i]=a[i][data];
        }
                var data={
			labels : array_labels,
			datasets : [
				{
					label: name,
					fillColor : "rgba(220,220,220,0.2)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(220,220,220,1)",
					data : array_data
				}]}
                        return data;

		};
})