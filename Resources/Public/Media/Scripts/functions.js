$(document).ready(function() {
	var activ = false;

	$("#submitbutton").css('display', 'none');

	$("#checklist input[type=checkbox]").click(function() {
		if (!activ) {
			activ = true;
			var ajaxParameter = {
				'sf_checklist[action]'	: 'ajax',
				'sf_checklist[name]'	: $(this).attr("name"),
				'sf_checklist[status]'	: $(this).is(":checked"),
				'sf_checklist[plugin]'	: $("#plugin").val()
			};

			var offset = $(this).offset()
			$(this).before('<div id="ajax_indicator"></div>');
			$("#ajax_indicator").css("left", offset.left - 1);
			$("#ajax_indicator").css("top", offset.top - 1);

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