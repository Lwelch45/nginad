<?php
/**
 * NGINAD Project
 *
 * @link http://www.nginad.com
 * @copyright Copyright (c) 2013-2016 NginAd Foundation. All Rights Reserved
 * @license GPLv3
 */

namespace buyrtbheaderbidding\workflows\tasklets\video\insertionorderlineitemvideorestrictions;

class CheckSupportedApis {
	
	public static function execute(&$Logger, &$Workflow, \model\openrtb\RtbBidRequest &$RtbBidRequest, \model\openrtb\RtbBidRequestImp &$RtbBidRequestImp, &$InsertionOrderLineItem, &$InsertionOrderLineItemVideoRestrictions) {
		
		$RtbBidRequestVideo = $RtbBidRequestImp->RtbBidRequestVideo;
		
		$result = true;
		
		if (empty($InsertionOrderLineItemVideoRestrictions->ApisSupportedCommaSeparated)):
			return $result;
		endif;
		
		$api_code_list = explode(',', $InsertionOrderLineItemVideoRestrictions->ApisSupportedCommaSeparated);
		
		if (!count($api_code_list)):
			return $result;
		endif;
		
		// Validate that the value is an array
		if (!is_array($RtbBidRequestVideo->api)):
			if ($Logger->setting_log === true):
			$Logger->log[] = "Param Not Required: No Values to Match: " . "Check video APIs code :: EXPECTED: "
					. 'Array(),'
					. " GOT: " . $RtbBidRequestVideo->api;
			endif;
			return $result;
		endif;

		/*
		 * All codes in the publisher ad zone
		* for the publisher's video player settings
		* have to be included in the VAST video demand
		*/
		foreach($RtbBidRequestVideo->api as $api_code_to_match):
			
			if (!in_array($api_code_to_match, $api_code_list)):
				
				$result = false;
				break;
					
			endif;
			
		endforeach;
		
		if ($result === false && $Logger->setting_log === true):
			$Logger->log[] = "Failed: " . "Check video APIs code :: EXPECTED: "
				. $InsertionOrderLineItemVideoRestrictions->ApisSupportedCommaSeparated
				. " GOT: " . join(',', $RtbBidRequestVideo->api);
		endif;
		
		return $result;
	}
}
