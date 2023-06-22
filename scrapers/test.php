<html>
   <head>
		<title>Book Grabber</title>
		<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		
		<script>
		$(function() {

			//--------GLOBAL VARS---------
			let run1;
			let run2;
			let isRunning = false;
			let start_count;
			let end_count;
			let mainCounter = 0;
			let initImpoprtPermission = false;
			let initCountOrderPermission = false;
			let jobType;
			let mainJobTimeInterval = 500;
			let errorMsg;
			let statusMsg;
			let getTableData = "";
			let getCounts = "";


			//--------ON REFRESH------
			window.onbeforeunload = function() {

				//if(isRunning)
                 return "Data will be lost if you leave the page, are you sure?";
				
            };


			//--------ON LOAD---------
			setTimeout(() => {
				getTableData = bookUpdateGetTable();
				getCounts = bookUpdateGetCount();

				$('#table_data').append(getTableData);

				let arr = getCounts.split('|');
				let start_arr = arr[0];
				let end_arr = arr[1];

				let new_start_count = parseInt(end_arr) + 1;
				let new_end_count = parseInt(end_arr) + 100;

				start_count = new_start_count;
				end_count = new_end_count;

				$('#start_input').val(new_start_count);
				$('#end_input').val(new_end_count);

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

			function importScout(){}

			function bookUpdateHistory(start, end){
				var send = $.getValues({
							
							"cmd" : "history_update",
							"start_date" : start,
							"end_date" : end,
							}, 'scraperz.php'); 
			}

			function bookUpdateGetCount(){
				var sendzz = $.getValues({
		
							"cmd" : "history_get_count",
							}, 'scraperz.php'); 
							return sendzz;
			}

			function bookUpdateGetTable(){
				var sendxx = $.getValues({
		
							"cmd" : "history_get_table",
							}, 'scraperz.php'); 

				return sendxx;
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

				if(isRunning == false){
					//start the job tracker
					isRunning = true;

					//Grab job parameters
					start_count = parseInt($('#start_input').val());
					end_count = parseInt($('#end_input').val());
					jobType = $('input[name="import_type"]:checked').val();

					initCountOrderPermission = false

					//Check to see if we have proper interval order
					if(end_count > start_count)
					{
						initCountOrderPermission = true
					}
					else{
						initCountOrderPermission = false; 
						errorMsg = "Cannot have Start number bigger than End number";
						alert(errorMsg +  ' ; end; '+end_count+' > start ; '+ start_count);
						console.log(errorMsg);
					}
					
					// If we have proper interval order, proceed with job
					if(initCountOrderPermission == true){

						//record for history
						bookUpdateHistory(start_count, end_count);

						mainCounter = 0;
						let sessionEndCounter = countDeterminer(start_count, end_count); //Calculates the number and count for the job to run
						let sessionIDCounter = start_count; //indicates the counter of the import id... that should match the id of the source
						let sessionData;

						//Start the job process
						run2 = setInterval(function(){
							//If we reach the end of the intervals, complete the job
							if(mainCounter > sessionEndCounter){
								clearInterval(run);
								isRunning = false;
								statusMsg = "Job is Complete!"
							}else{ //Else Continue the job intervals
								
								//Determine Job Type
								if(jobType == 'book_type'){
									let thisData;
									thisData = importBookData(sessionIDCounter, jobType, 'run_import');

									sessionData += thisData;
									console.log(thisData);

								}else if(jobType == 'author_type'){
									let thisData;
									thisData = importBookData(sessionIDCounter, jobType, 'run_import');

									sessionData += thisData;
									console.log(thisData);
								}else if(jobType == 'specific_type'){
									let thisData;
									thisData = importBookData(sessionIDCounter, jobType, 'run_import');

									sessionData += thisData;
									console.log(thisData);
								}else if(jobType == 'author_img_type'){
									let thisData;
									thisData = importBookData(sessionIDCounter, jobType, 'run_import');

									sessionData += thisData;
									console.log(thisData);
								
								}else if(jobType == 'book_img_type'){
									let thisData;
									thisData = importBookData(sessionIDCounter, jobType, 'run_import');

									sessionData += thisData;
									console.log(thisData);
								}

								let progressPercent = mainCounter/sessionEndCounter *100;

								//Update Progress bar
								$('#main_progress').css('width', progressPercent+'%');
								$('#main_prog_id').text('-> Job Started: '+progressPercent+'%');
								
							}
							
							sessionIDCounter++;
							mainCounter++;
						}, mainJobTimeInterval);
					}
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
		<h2> Book Grabber</h2>
		<div id="control_box">
			<h3> Controls:</h3>
			<div id="info_box"><i>Info:</i></div>
			Start: <input id="start_input" value="1" placeholder="0" style="width:60px;" place  type="input" />
			End: <input id="end_input" value="10" placeholder="100" style="width:60px;"  type="input" />
			
			<br/>
			<p>Import Book/Author/Specific Import :</p>
			<input type="radio" id="book_type" name="import_type" value="book_type" />
			<label for="age1">Book</label><br>
			<input type="radio" id="author_type" name="import_type" value="author_type" />
			<label for="age2">Author</label><br> 
			<input type="radio" id="specific_type" name="import_type" value="specific_type"  />
			<label for="age2">Specific Import</label><br> 
			<input type="radio" id="author_img_type" name="import_type" value="author_img_type" />
			<label for="age2">Get Author Images</label><br> 
			<input type="radio" id="book_img_type" name="import_type" value="book_img_type"  checked/>
			<label for="age2">Get Book Images</label><br> 
		</div> <br/><hr>
		
		<input id="start-btn" value="start" type="button" />
		<input id="stop-btn"  value="stop"  type="button" />
		<div id="prog"></div><br/><hr>

		<h3> Current Progress -<span id="main_prog_id"></span></h3>
		<div id="progress_box">
			<div class="progress">
				<div id="main_progress" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
			</div>

		</div><br/><hr>

		<h3> Test Curl Nodes:</h3>
		<div id="test_box">
			Test All xpaths routes before importing:
			<input id="xpath_tests_btn" value="Test" type="button" />
			<div id="test_box_result"></div>
		</div><br/><hr>

		<h3> Job History:</h3>
		<div id="history_box">
				<table class="table">
					<thead>
						<tr>
							<th>id</th>
							<th>start count</th>
							<th>end count</th>
							<th>import count</th>
							<th>date last updated</th>
						</tr>
					</thead>
					<tbody id="table_data">
						
					</tbody>
					
				</table>
		</div><br/>
	</body>
        
</html>