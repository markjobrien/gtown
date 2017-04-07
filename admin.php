<?php
/*
*  Template name: Admin
*/

get_header();

$is_admin = true;

$fixtures = display_matches( $is_admin, 0, 2017 );
$results = display_matches( $is_admin, 1, 2017 );

?>

<div id="primary" class="site-content">
<div id="content" role="main">

	<article <?php post_class(); ?>>
		<header class="entry-header">
			<h1 class="entry-title">Fixtures / Results</h1>
		</header>

		<div class="entry-content">

			<form class="scrape-form" method="post">
				<label>Match type: </label>
				<select name="match_type">
					<option value="0">Fixture</option>
					<option value="1">Result</option>
				</select>
				<label>URL to scrape</label>
				<input type="text" name="url_scrape" />
				<label>Team type: </label>
				<select name="team_type">
					<option value="Mens Senior">Mens Senior</option>
				</select>
				<label>Competition</label>
				<input type="text" name="comp" />

				<input type="submit" value="Scrape URL" class="scrape_url" />
			</form>


			<form class="match-form" method="post">

				<div class="half-form">
					<label>Match type: </label>
					<select name="match_type">
						<option value="0">Fixture</option>
						<option value="1">Result</option>
					</select>
				</div>

				<div class="half-form">
					<label>Team type: </label>
					<select name="team_type">
						<option value="Mens Senior">Mens Senior</option>
					</select>
				</div>

				<div class="half-form">
					<label>Home Team</label>
					<input type="text" name="home_team" />
				</div>

				<div class="half-form">
					<label>Away Team</label>
					<input type="text" name="away_team" />
				</div>

				<div class="half-form">
					<label>Venue</label>
					<input type="text" name="venue" />
				</div>

				<div class="half-form">
					<label>Competition</label>
					<input type="text" name="comp" />
				</div>

				<div class="half-form">
					<label>Home score</label>
					<input type="text" name="home_score" placeholder="Leave blank if fixture" />
				</div>

				<div class="half-form">
					<label>Away score</label>
					<input type="text" name="away_score" placeholder="Leave blank if fixture" />
				</div>

				<div class="half-form">
					<label>When</label>
					<input type="date" name="when" class="datepicker" />
				</div>

				<div class="half-form">
					<label>Time</label>
					<select name="when_time">
						<?php
							for( $i = 10; $i <=20; $i++ ) {
								for( $j = 0; $j <= 3; $j++ ) {
										$time = $i . ':' . ($j == 0 ? '00' : $j * 15);
									echo '<option value="' . $time . '">' . $time  . '</option>';
								}
							}
						?>
					</select>
				</div>


				<input type="submit" value="Add match" class="add_match_submit" />
				<a href="#" class="back_to_add">Change back to add fixture</a>
				<!-- <button class="fill_form">Test</button>	-->

			</form>

			<?php echo $fixtures; ?>
			<?php echo $results; ?>

		</div><!-- .entry-content -->
	</article><!-- #post -->


</div><!-- #content -->
</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
