jQuery(document).ready(function($) {

	// Declare window variable
	var this_youtube_window = top.tinymce.activeEditor;
	
	// Set focus to 'search' field
	$('#queryinput').focus();
	
	// This function will search the YouTube api.. and return a json object of matched results
	function SearchYouTube(query, loadmore, number) {
		
		// Get starting number
		if(number) {
			number = number;
		}else {
			number = 1;
		}
		
		// Send ajax call to YouTube
		$.ajax({

			url: 'https://www.googleapis.com/youtube/v3/search?part=snippet&q=' + query + '&maxResults=5&order=viewCount&key=AIzaSyBi-Z0EbGeHNW6eLrSh_k1qJLqUZQFhz_U',
			dataType: 'jsonp',
			success: function (data) {

				var row = "";
				// If we have data feed results to display
				if(data.items) {
					
					// Loop all returned items and extract their data
					for (var i = 0; i < data.items.length; i++) {

						var obj = data.items[i];

						row += "<div class='search_item'>";
						row += "<table class='youtube_results' width='100%'>";
						row += "<tr>";
						row += "<td class='youtube_image' vAlign='top' align='left'>";
						row += "<a href='#' ><img width='120px' height='80px' src=" + obj.snippet.thumbnails.medium.url + " /></a>";
						row += "</td>";
						row += "<td class='youtube_info' vAlign='top' width='100%' align='left'>";
						row += "<a href='#' class='youtube_title'><b>" + obj.snippet.title + "</b></a><br />";
						row += "</td>";
						row += "<td class='youtube_url' vAlign='top' align='left'>";
						row += "<input class='youtube_video_url' type='hidden' value='http://www.youtube.com/watch?v="+obj.id.videoId+"' /><br />";
						row += "</td>";
						row += "</tr>";
						row += "</table>";
						row += "</div>";
					}
					//row += '<div class="load_more_results"><span class="get_more_results btn-default">Load More</span></div>';
					
					// If we are NOT loading more
					if(loadmore != 'true') {
						
						$("#search-results-block").html(row);
						$("div#search-results-block").scrollTop(0);
					}
					// Else we are loading more to current div
					else {
						
						// Replace 'load more' div with new set of results
						$('.load_more_results').last().html(row);
					}
				}
				// Else there are no data feed results to display
				else {
					
					row += "Result not found. Please try another search.";
					$("#search-results-block").html(row);
				}
			},
			// Throw error alert if ajax fails
			error: function () {
				alert("Error loading youtube video results");
			}
		});
		return false;
	}
	
	// Click function for 'Search' button
	$('#search_youtube').click(function() {
		
		search_term = $('#queryinput').val();
		SearchYouTube(search_term);
	});
	
	// Add binding click function to each table row of the returned ajax object
	$('body').on('click', '.youtube_results tr', function() {
		
		// Get and populate video title
		this_title = $(this).children('.youtube_info').children('.youtube_title').text();
		$('#youtube_title').val(this_title);
		
		// Get and populate video link
		this_link = $(this).children('.youtube_url').children('.youtube_video_url').val();
		this_link = this_link.replace('&feature=youtube_gdata','');
		$('#youtube_url').val(this_link);
		
		// Replace image placeholder with video preview
		preview_link = this_link.replace('http:','');
		preview_link = preview_link.replace('watch?v=','embed/');
		$('#video_preview').html('<iframe width="330" height="230" src="'+preview_link+'" frameborder="0" allowfullscreen></iframe>');
	});
	
	// Action buttons
	$('#youtube_cancel').click(function() {
		
		this_youtube_window.windowManager.close();
	});
	$('#youtube_insert').click(function() {
		
		// Get link from input box
		this_link = $('#youtube_url').val();
		this_link = this_link.replace('http:','');
		this_link = this_link.replace('watch?v=','embed/');
		
		// If no link.. alert user
		if(this_link == '') {
			
			alert('Please select a video; or enter a "YouTube Url" video link.');
			return false;
		}
		
		
		// Get user defined width and height
		this_width = $('#youtube_width').val();
		this_height = $('#youtube_height').val();
		
		// Get checkbox values
		autoplay = $('#youtube_autoplay').is(':checked');
		strip = $('#youtube_strip').is(':checked');

		if (autoplay != false || strip != false) {
			this_link += '?rel=0';
		}

		// Add appropriate options if user selected
		if(autoplay == true) {
			this_link += '&autoplay=1';
		}
		if(strip == true) {
			this_link += '&showinfo=0&controls=0';
		}
		
		// Assemble final link
		final_link = '<iframe width="'+this_width+'" height="'+this_height+'" src="'+this_link+'" frameborder="0" rel="nofollow" allowfullscreen></iframe>';
		
		this_youtube_window.execCommand('mceInsertContent', !1, final_link);  // Insert content into editor
		this_youtube_window.windowManager.close();  // Close window
	});
	
	// Determine if enter key was pressed on search field
	$("#queryinput").keyup(function(event){
		if(event.keyCode == 13){
			$("#search_youtube").click();  // Run 'Search' button function
		}
	});
	
	// Convert seconds to h:m:s
	function convertSeconds(s) {
		var h = Math.floor(s/3600);
		s -= h*3600;
		var m = Math.floor(s/60);
		s -= m*60;
		return h+":"+(m < 10 ? '0'+m : m)+":"+(s < 10 ? '0'+s : s); // Zero padding on minutes and seconds
	}

	// YouTube search autocomplete
	$("#queryinput").autocomplete({

		source: function(request, response){

			var apiKey = 'AI39si7ZLU83bKtKd4MrdzqcjTVI3DK9FvwJR6a4kB_SW_Dbuskit-mEYqskkSsFLxN5DiG1OBzdHzYfW0zXWjxirQKyxJfdkg';
			var query = request.term;

			// Send ajax request to google for youtube client autocomplete library
			$.ajax({

				url: "http://suggestqueries.google.com/complete/search?hl=en&ds=yt&client=youtube&hjson=t&cp=1&q="+query+"&key="+apiKey+"&format=5&alt=json&callback=?",
				dataType: 'jsonp',
				success: function(data, textStatus, request) {

					response( $.map( data[1], function(item) {

						return {
							label: item[0],
							value: item[0]
						}
					}));
				}
			});
		},
		// Run search function when user clicks an autocomplete selection
		select: function( event, ui ) {

			$("#search_youtube").click();
		}
	});

	// Run blur event on YouTube URL field.. for manually inputting url
	$('#youtube_url').on('blur', function() {
		
		preview_link = $(this).val();
		// If link contains valid http format
		if ((preview_link.indexOf("http://www.youtube.com/watch?v=") >= 0) || (preview_link.indexOf("https://www.youtube.com/watch?v=") >= 0)) {
			
			preview_link = preview_link.replace('https:','').replace('http:','');
			preview_link = preview_link.replace('watch?v=','embed/');
			$('#video_preview').html('<iframe width="330" height="230" src="'+preview_link+'" frameborder="0" allowfullscreen></iframe>');
		}
		// Else alert user to use valid http format
		else {
			
			$('#video_preview').html('Not a valid youtube url format. Please ensure the "YouTube URL" field contains a valid link.<br /><br />Acceptable link format:<br /><b>http://www.youtube.com/watch?v=4vTyEy7Dn70</b>');
		}
		
		
	});
	
	// Get more results
	$('body').on('click', '.get_more_results', function() {
		
		// Get search term
		search_term = $('#queryinput').val();
		
		// Count current number of '.search_item' divs
		count_divs = $('.search_item').length;
		// Add 1 to get the starting index for the next set
		count_divs = count_divs + 1;
		// Execute youtube api with updated count
		SearchYouTube(search_term, 'true', count_divs);
	});

});