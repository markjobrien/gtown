<?php


/**
 * Twitter feed Class
 *
 * This widget produces the initial embed for the twitter feed. It allows you to choose the
 * type of feed, be it a feed for #itsligo or a feed of the twitter account of itsligo
 */
class Twitter_feed extends WP_Widget {
	/** constructor */
	function __construct() {
		parent::__construct(
			'twitter_feed',
			__('Twitter feed', 'text_domain'), // Name
			array( 'description' => __( 'Add a twitter feed to the homepage. Choose a feed for #garristown or user @garristowngfc', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		 echo $args['before_widget'];
		if ( !empty( $instance['feed_type'] ) ) {
			$feed_type = $instance['feed_type'];
		} ?>

		<div class="twitter-holder">
			<h2>Follow on Twitter</h2>
			<div class="feed-holder">
				<a class="twitter-timeline" href="https://twitter.com/twitterapi" data-widget-id="395201624680833024" data-theme="light" data-chrome="noheader nofooter noborders"  data-related="twitterapi,twitter" data-aria-polite="assertive" width="400" height="300" lang="EN">Tweets by @twitterapi</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
		</div>
		<?php
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'feed_type' ] ) ) {
			$feed_type = $instance[ 'feed_type' ];
		}
		else {
			$feed_type = 'inside';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'feed_type' ); ?>"><?php _e( 'Select feed type:' ); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'feed_type' ); ?>" name="<?php echo $this->get_field_name( 'feed_type' ); ?>">
			<option value="inside" <?php if ( $feed_type == 'inside' ) { echo 'selected'; } ?>>@garristowngfc twitter account only</option>
			<option value="outside" <?php if ( $feed_type == 'outside' ) { echo 'selected'; } ?>>#garristown</option>
		</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['feed_type'] = ( ! empty( $new_instance['feed_type'] ) ) ? strip_tags( $new_instance['feed_type'] ) : '';
		return $instance;
	}
} // class Twitter feed



/**
 * Facebook feed Class
 *
 * This widget produces the initial embed for the twitter feed. It allows you to choose the
 * type of feed, be it a feed for #itsligo or a feed of the twitter account of itsligo
 */
class Facebook_feed extends WP_Widget {
	/** constructor */
	function __construct() {
		parent::__construct(
			'facebook_feed',
			__('Facebook feed', 'text_domain'), // Name
			array( 'description' => __( 'Add a facebook feed to the homepage.', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		 echo $args['before_widget'];
		?>

		<div class="facebook-holder">
			<h2>Follow on Facebook</h2>
			<div class="feed-holder">
				<div class="fb-page" data-href="https://www.facebook.com/garristowngfcpage" data-tabs="timeline" data-width="375" data-height="290" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"><blockquote cite="https://www.facebook.com/garristowngfcpage" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/garristowngfcpage">Garristown Gfc Page</a></blockquote></div>
			</div>
		</div>

		<?php
		echo $args['after_widget'];
	}
} // class Facebook feed

?>
