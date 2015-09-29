<?php

class AffiliateWP_Store_Credit_EDD extends AffiliateWP_Store_Credit_Base {


	/**
	 * Get things started
	 *
	 * @access public
	 * @since 2.0.0
	 * @return void
	 */
	public function init() {
		$this->context = 'edd';

		// Make sure Wallet is installed
		if( ! class_exists( 'EDD_Wallet' ) ) {
			add_action( 'admin_notices', array( $this, 'missing_edd_wallet' ) );
			return;
		}
	}


	/**
	 * Display a notice if EDD Wallet is missing
	 *
	 * @access public
	 * @since 2.0.0
	 * @return void
	 */
	public function missing_edd_wallet() {
		echo '<div class="error"><p>' . __( 'AffiliateWP - Store Credit EDD integration requires the EDD Wallet extension!', 'affiliatewp-store-credit' ) . '</p></div>';
	}


	/**
	 * Add a payment to a referrer
	 *
	 * @access protected
	 * @since 0.1
	 * @param int $referral_id The referral ID
	 * @return
	 */
	protected function add_payment( $referral_id ) {

		// Return if the referral ID isn't valid
		if( ! is_numeric( $referral_id ) ) {
			return;
		}

		// Get the referral object
		$referral = affwp_get_referral( $referral_id );

		// Get the user id
		$user_id = affwp_get_affiliate_user_id( $referral->affiliate_id );

		// Deposit the funds into the users' wallet
		edd_wallet()->wallet->deposit( $user_id, $referral->amount, 'admin-deposit' );

		return;
	}


	/**
	 * Remove a payment from a referrer
	 *
	 * @access protected
	 * @since 0.1
	 * @param int $referral_id The referral ID
	 * @return
	 */
	protected function remove_payment( $referral_id ) {

		// Return if the referral ID isn't valid
		if( ! is_numeric( $referral_id ) ) {
			return;
		}

		// Get the referral object
		$referral = affwp_get_referral( $referral_id );

		// Get the user id
		$user_id = affwp_get_affiliate_user_id( $referral->affiliate_id );

		// Withdraw the funds from the users' wallet
		edd_wallet()->wallet->withdraw( $user_id, $referral->amount, 'admin-withdrawal' );

		return;
	}
}
new AffiliateWP_Store_Credit_EDD;