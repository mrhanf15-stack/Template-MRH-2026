<?php

/*
contentAnywhere

Karl 22.02.2021


Syntax:

{99|contentAnywhere} // ersetze die coID 99 mit der coID des gewuenschten Contents

 */

function smarty_modifier_contentAnywhere($Input) {

	if($Input) {

		global $main;
		$modifier_content_data = $main->getContentData((int)$Input, '', '', false);

		if (
			empty($modifier_content_data)
			|| !is_array($modifier_content_data)
			|| empty($modifier_content_data['content_text'])
		) {
			return false;
		}

		$modifier_content_heading = (!empty($modifier_content_data['content_heading']) ? $modifier_content_data['content_heading'] : (!empty($modifier_content_data['content_title']) ? $modifier_content_data['content_title'] : ''));

		$modifier_content =     '<div class="content' . $Input . '">'."\n";
		//$modifier_content .=    '   <div class="box-heading w-100 navbar-brand border-bottom mb-2 text-light">' . $modifier_content_heading . '</div>'."\n";
		$modifier_content .=    '   <div class="clearfix">'."\n";
		$modifier_content .=    '   	' . $modifier_content_data['content_text'] ."\n";
		$modifier_content .=    '   </div>'."\n";
		$modifier_content .=    '</div>'."\n";

		return $modifier_content;

	}

}
?>