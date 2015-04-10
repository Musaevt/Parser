<div class="container">
    
    <h1 class="search_number">  Поиск № <span  id="search_id"> <?php echo $this->data['search_id'];?> </span></h1>
    <div class="graph">
       <div class="chart">
           <h3>График 1</h3>
			<div>
				<canvas id="percent"  height="300" width="450"></canvas>
			</div>
        </div>
        <div class="chart_description">
                <h3>Описание Графика 1</h3>
                <p>В графике №1 ось Y обозначает процент интересющих нас пользователей в группе.На оси X изображенны названия групп(screen names), отсортированные по убыванию %.
                   На графике не предоставленные группы,численность которых меньше 5 человек.
                </p>
        </div>
    </div>
     <div class="graph">
        <div class="chart">
               <h3>График 2</h3>
			<div>
				<canvas id="count"  height="300" width="450"></canvas>
			</div>
        </div>
     <div class="chart_description">
         <h3>Описание Графика 2</h3>
                <p>
                   На графике представленные группы по количеству подписчиков из исходной группы. Ось Y-количество подписчиков из исходной группы. Ось X-названия групп.
                </p>
     </div>
     </div>
     <div class="graph">
         <div class="chart">
                <h3>График 3</h3>
			<div>
				<canvas id="members_community" height="300" width="450"></canvas>
			</div>
	 </div>
         <div class="chart_description">
                <h3>Описание Графика 3</h3>
                <p>В графике №3 ось Y обозначает количество групп пользователя.
                   На оси X представленны подписчики исходной группы.                   
                </p>
                <p>*Пользователи с количеством групп свыше 700 не рассматриваются.</p>
         </div>
     </div>
            
</div>