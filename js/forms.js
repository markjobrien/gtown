$ = jQuery;
var submit_btn = '';
var match_id = '';
var add_these = false;

$(document).ready(function(){

	submit_btn = $('.add_match_submit');

	$( ".datepicker" ).datepicker({
		dateFormat: "yy-mm-dd"
	});

	$('form.match-form').submit(function(e){
		e.preventDefault();
		var form_data = $(this).serialize();

		if (match_id > 0) {
			match(form_data, 'edit');
		} else {
			match(form_data, 'add');
		}
	});

	$('.edit_match').click(function(e){
		e.preventDefault();
		$('.back_to_add').fadeIn();
		submit_btn.val('Edit match');
		match_id = $(this).parents('tr').attr('id');

		var match_type = $(this).attr('title');

		fillEditForm(match_id, match_type);
	});

	$('.remove_match').click(function(e){
		e.preventDefault();
		if (confirm('Are you sure you want to delete this match?')) {
			match_id = $(this).parents('tr').attr('id');
			match('', 'remove');
		}
	});

	$('.back_to_add').click(function(e){
		e.preventDefault();
		$(this).fadeOut();
		resetForm();
		submit_btn.val('Add match');
	});

	$('.fill_form').click(function(e){
		e.preventDefault();
		$('form.match-form input[name=home_team]').val( 'aaa' );
		$('form.match-form input[name=away_team]').val( 'bbb' );
		$('form.match-form input[name=venue]').val( 'ccc' );
		$('form.match-form input[name=comp]').val( 'ddd' );
		$('form.match-form input[name=when]').val( '2015-05-04' );

	});


	$('form.scrape-form').submit(function(e){
		e.preventDefault();
		var form_data = $(this).serialize();
		sendScrapeData(form_data);
	});

});

function fillEditForm(match_id, match_type) {
	var row = 'tr#' + match_id;

	$('form.match-form select[name=match_type]').val( match_type );
	$('form.match-form select[name=team_type]').val( $(row + ' td.team_type').text() );
	$('form.match-form input[name=home_team]').val( $(row + ' td.home_team').text() );
	$('form.match-form input[name=away_team]').val( $(row + ' td.away_team').text() );
	$('form.match-form input[name=venue]').val( $(row + ' td.venue').text() );
	$('form.match-form input[name=comp]').val( $(row + ' td.comp').text() );
	$('form.match-form input[name=when]').val( $(row + ' td.when').data('when') );
	$('form.match-form select[name=when_time]').val( $(row + ' td.when_time').text() );

	if ( $(row + ' td.home_score').text().length > 0 ) {
		$('form.match-form input[name=home_score]').val( $(row + ' td.home_score').text() );
	}

	if ( $(row + ' td.away_score').text().length > 0 ) {
		$('form.match-form input[name=away_score]').val( $(row + ' td.away_score').text() );
	}

}

function resetForm() {
	$('form.match-form')[0].reset();
	match_id = 0;
}

function match(form_data, type) {

	var action = '';

	if ( type == 'edit' ) {
		action = 'editMatch';
		submit_btn.attr('disabled', true).val('Editing...');
	} else if ( type == 'add' ) {
		action = 'addMatch';
		submit_btn.attr('disabled', true).val('Adding...');
	} else if ( type == 'remove' ) {
		action = 'removeMatch';
	}

	$.ajax({
		type: 'POST',
		dataType: 'jsonp',
		cache: false,
		data: {
			action: action,
			form_data: form_data,
			match_id: match_id
		},
		success: function(data){
			submit_btn.attr('disabled', false);
			if ( type == 'edit' ) {
				submit_btn.val('Edited');
			} else if ( type == 'add' ) {
				submit_btn.val('Added');
			} else if ( type == 'remove' ) {
				alert('Match deleted');
			}
			window.location.reload();
		},
		error: function(data){
			console.log('error');
		}
	});
}

function sendScrapeData(form_data) {
	$.ajax({
		type: 'POST',
		dataType: 'jsonp',
		cache: false,
		data: {
			action: 'scrapeUrl',
			form_data: form_data,
			add_these: add_these
		},
		success: function(data){
			if (data.res > 0) {
				console.log(data.res + ' matches added');
				//window.location.reload();
			} else {
				console.log(data.res);
			}
		},
		error: function(data){
			console.log('error');
		}
	});
}
