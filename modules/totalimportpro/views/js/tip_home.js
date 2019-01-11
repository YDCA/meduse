/**
 * tip_home.js
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
 * file except in compliance with the License. You may obtain a copy of the License at:
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under
 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
 * ANY KIND, either express or implied. See the License for the specific language
 * governing permissions and limitations under the License.
 *
 * @author HostJars <support@hostjars.com>
 */

$(document).ready(function()
{
	$('[data-toggle="tooltip"]').tooltip();

	$("#settingsUpload input").on("change", function (e) {
	  $("#settingsUpload").submit();
	});

	$('#uploadProfile').on('click', function() {
	  $("#settingsUpload input").click();
	  return false;
	});

	$(".cron-profile").on("click", function(e) {
		var profile_name = $(this).data('profile');
		if (profile_name.indexOf(' ') !== -1)
			profile_name = "'" + profile_name + "'";
		$("#cron-dialog-command").val(TotalImportPRO.php_path + ' ' + TotalImportPRO.cron_index_path + ' ' + profile_name);
		$("#cron-dialog").dialog({
			draggable: false,
			modal: true,
			resizable: false,
			width: '450',
			buttons: [
				{
				  text: "OK",
				  click: function()
					{
					$(this).dialog("close");
				  }
				}
			]
		});
		e.preventDefault();
		return false;
	});

	$(".delete-profile").on("click", function(e)
	{
		var profile_name = $(this).data("profile");
		var profile_row = $(this).closest(".profile_row");
		if (profile_name !== '') {
			var url = TotalImportPRO.ajax_url;
				$.ajax({
					type: "POST",
					url: url,
					data: {
						ajax: true,
						action: "DeleteProfile",
						profile_name: profile_name,
					},
					success: function(output)
					{
						profile_row.remove();
						addSave(output);
						if ($('.profile_row').length == 0) {
							location = location; // Refresh page to get the "No Profiles Saved" message. Avoiding DRY but still ugly
						}
					}
			});
		}
		e.preventDefault();
		return false;
	});

	$(".load-profile").on("click", function(e) {
		var profile_name = $(this).data("profile");
		if (profile_name !== '') {
			var url = TotalImportPRO.ajax_url;
				$.ajax({
					type: "POST",
					url: url,
					data: {
						ajax: true,
						action: "LoadProfile",
						profile_name: profile_name,
					},
					success: function(output)
					{
						addSave(output);
					}
			});
		}
		e.preventDefault();
		return false;
	});

	function addSave(result)
	{
		$('#total_import').prepend('<div class="bootstrap"><div class="module_confirmation conf confirm alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button>' + result + '</div></div>');
	}
});