$ = jQuery;
var w = window.innerWidth;

$(document).ready(function(){
	  if ( w > 400) {
		showUpcomingFixtures();
	  }
});

var i = 1;

function movetoNextFixture(i) {

	if (i == 3) {
	i = 0;
	}

	var pixels = 18 * i;
	$('.next-fixture li').css({
	'-webkit-transform' : 'translateY(-' + pixels + 'px)',
	'-ms-transform': 'translateY(-' + pixels + 'px)',
	'transform' : 'translateY(-' + pixels + 'px)'
	});

	return i;
}

function showUpcomingFixtures() {

	setInterval(function(){
		i = movetoNextFixture(i);
		i++;
	}, 5000);
}
