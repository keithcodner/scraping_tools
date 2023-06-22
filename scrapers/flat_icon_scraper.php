<html>
   <head>
		<title>Flat Icon Scraper</title>
		<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		
		<script>
		$(function() {

			//--------GLOBAL VARS---------
			let run1;
			let run2;
			let isRunning = false;
			let start_count = 0;
			let end_count = 100;
			let mainCounter = 0;
			let jobType;
			let mainJobTimeInterval = 500;
			let errorMsg;
			let statusMsg;


			//--------ON REFRESH------
			window.onbeforeunload = function() {

				//if(isRunning)
                 return "Data will be lost if you leave the page, are you sure?";
				
            };


			//--------ON LOAD---------
			setTimeout(() => {
				

			}, 100);
			
			//--------FUNCTIONS---------
			jQuery.extend({
				getValues: function(data, url) {
					var result = null;
					$.ajax({
						url: url,
						type: 'post',
						data: data,
						async: false,
						success: function(datas) {
							result = datas;
						}
					});

					return result;
				}
			});

			function countDeterminer(start, end){

				let data;
				if(start < end)
				{
					data = end - start;
				}else{
					data = 'dates not in right order';
				}

				return data;
			}

			function importBookData(importID, jobTypeData, cmd){
				var send = $.getValues({
							"id" : importID,
							"jobType" : jobTypeData,
							"cmd" : cmd,
							}, 'scraperz.php'); 
				return send;
			}
			
			//--------CLICKS---------

			// Run XPath Tests
			$('#xpath_tests_btn').click(function(){
				
				var sendzz = $.getValues({
							"cmd" : "test_nodes",
							}, 'scraperz.php'); 
				
				$('#test_box_result').append(sendzz);
				
			});

			// Run Book import
			$('#start-btn').click(function(){
				let data = $("#textarea_data").val().split(/\n/);
				let path = $("#end_path").val();
				let new_path = path.replace(/\\/g, "\\\\");

				console.log(new_path);


				mainCounter = 0;

				if(isRunning == false){
					//start the job tracker
					isRunning = true;
					//Start the job process
					run2 = setInterval(function(){

						// stop if no more data
						if(data[mainCounter].length < 1){
							clearInterval(run2);
						}

						importBookData(new_path, data[mainCounter], 'flat_icon')

						console.log(data[mainCounter]);
						mainCounter++;
					}, mainJobTimeInterval);
				
				}else if(isRunning == true){

					//do nothing, in case the button is clicked twice
					alert('Job is already running...');
				}
				
			});
			
			// Stop book Import
			$('#stop-btn').click(function(){
				
				clearInterval(run2);
			});	
		});
			
		</script>
		<style></style>
		<style></style>
    </head>
	
	<body>
		<!-- <div id="prog"></div> -->
		<h2> Flat Icon Scraper</h2>
		<div id="control_box">
			<h3> Controls:</h3>
			<div id="info_box"><i>Info:</i></div>
			<!-- Start: <input id="start_input" value="1" placeholder="0" style="width:60px;" place  type="input" />
			End: <input id="end_input" value="10" placeholder="100" style="width:60px;"  type="input" /> -->

			<textarea id="textarea_data">
			

			</textarea>
			<br />
			path:
			<input id="end_path" type="text" value="" />
			
			<br/>
			<p>Import Book/Author/Specific Import :</p>
			<input type="radio" id="book_type" name="import_type" value="book_type" />
			<label for="age1">Book</label><br>

		</div> <br/><hr>
		
		<input id="start-btn" value="start" type="button" />
		<input id="stop-btn"  value="stop"  type="button" />
		<div id="prog"></div><br/><hr>

	
	</body>
        
</html>