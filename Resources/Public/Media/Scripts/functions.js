/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009 Sebastian Fischer <typo3@fischer.im>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

$(document).ready(function() {
	var activ = false;

	$("#submitbutton").css('display', 'none');

	$("#checklist input[type=checkbox]").click(function() {
		if (!activ) {
			activ = true;
			var ajaxParameter = {
				'tx_sfchecklist_list[name]'		: $(this).attr("name"),
				'tx_sfchecklist_list[status]'	: $(this).is(":checked"),
				'tx_sfchecklist_list[pluginid]'	: $("input[name=tx_sfchecklist_list[pluginid]]").val()
			};

			var offset = $(this).offset()
			$(this).before('<div id="ajax_indicator"></div>');
			$("#ajax_indicator").css("left", offset.left);
			$("#ajax_indicator").css("top", offset.top);

			$.ajax({
				url: "index.php?eID=tx_sfchecklist",
				type: "POST",
				data: ajaxParameter,
				dataType: "json",
				success: function(succmsg) {
					$("#ajax_indicator").remove();
					activ = false;
				},
				error: function(errmsg) {}
			});
		} else {
			$(this).checked = !$(this).is(":checked");
			return false;
		}
	});
});