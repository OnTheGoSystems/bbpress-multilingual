<?php

/**
 * Main plugin class
 *
 * Allows compatibility between bbPress and WPML.
 *
 * @since 0.1.0
 */
class BBPML {

	/**
	 * BBP_Multilingual constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ), 20 );
	}

	/**
	 * Initialize plugin.
	 *
	 * @since 0.1.0
	 */
	public function init() {
		// Sanity check.
		if ( ! defined( 'ICL_SITEPRESS_VERSION' ) || ! class_exists( 'bbPress' ) ) {
			add_action( 'admin_notices', array( $this, 'error_no_plugins' ) );

			return;
		}
		// WPML setup has to be finished.
		if ( ! apply_filters( 'wpml_setting', false, 'setup_complete' ) ) {
			add_action( 'admin_notices', array( $this, 'error_wpml_setup' ) );

			return;
		}

		$this->init_hooks();
	}

	/**
	 * Error message
	 *
	 * Notice if requirements not met.
	 *
	 * @since 0.1.0
	 */
	public function error_no_plugins() {
		$message = __( '%s plugin is enabled but not effective. It requires %s and %s plugins in order to work.', 'bbpress_multilingual' );
		echo '<div class="error"><p>' .
		     sprintf( $message, '<strong>bbPress multilingual</strong>',
			     '<a href="http://wpml.org/">WPML</a>',
			     '<a href="https://bbpress.org">bbPress</a>' ) .
		     '</p></div>';
	}

	/**
	 * Error message
	 *
	 * Notice if WPML setup is not finished.
	 *
	 * @since 0.1.0
	 */
	public function error_wpml_setup() {
		$message = __( '%s plugin is enabled but not effective. You have to finish WPML setup.', 'bbpress_multilingual' );
		echo '<div class="error"><p>' . sprintf( $message, '<strong>bbPress multilingual</strong>' ) . '</p></div>';
	}

	/**
	 * Hook init
	 *
	 * Load plugin hooks.
	 *
	 * @since 0.1.0
	 */
	public function init_hooks() {

		// Handling posts count on user profile pages.
		add_filter( 'bbp_get_user_topic_count_raw', array( $this, 'get_user_topic_count' ), 10, 2 );
		add_filter( 'bbp_get_user_reply_count_raw', array( $this, 'get_user_reply_count' ), 10, 2 );
	}

	/**
	 * Get proper number of topics for user
	 *
	 * @since 0.1.0
	 *
	 * @param $count Not used parameter
	 * @param $user_id Author ID
	 *
	 * @return int
	 */
	public function get_user_topic_count( $count, $user_id ) {
		return $this->get_user_post_count( bbpress()->topic_post_type, $user_id );
	}

	/**
	 * Get proper number of replies for user
	 *
	 * @since 0.1.0
	 *
	 * @param int $count Not used parameter
	 * @param int $user_id Author ID
	 *
	 * @return int
	 */
	public function get_user_reply_count( $count, $user_id ) {
		return $this->get_user_post_count( bbpress()->reply_post_type, $user_id );
	}

	/**
	 * Get number of published posts for user
	 *
	 * @since 0.1.0
	 *
	 * @param string $post_type Post type name.
	 * @param int $user Author ID.
	 *
	 * @return int Number of published posts for user.
	 */
	public function get_user_post_count( $post_type, $user ) {
		$args = array(
			'post_status' => 'publish',
			'post_type'   => $post_type,
			'author'      => $user
		);

		$all_posts = new WP_Query( $args );

		return $all_posts->found_posts;
	}
}