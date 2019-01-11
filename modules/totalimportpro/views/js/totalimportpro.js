/**
 * totalimportpro.js
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

// Start of Intercom Script
window.MAX_FILE_SIZE = "'.$this->getMaxFileUploadSize().'";
window.intercomSettings = {
app_id: "rtb0b21o",
store_domain: "'.$_SERVER['HTTP_HOST'].'",
name: $(".employee_name").text(), // Full name
email: "'.$this->context->employee->email.'", // Email address
import_profiles: "'.count($this->getSavedSettingNames()).'",
};
(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic("reattach_activator");ic("update",intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement("script");s.type="text/javascript";s.async=true;s.src="https://widget.intercom.io/widget/rtb0b21o";var x=d.getElementsByTagName("script")[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent("onload",l);}else{w.addEventListener("load",l,false);}}})()
// End of Intercom Script

// Start of HostJars Support Zendesk Widget script
window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("//assets.zendesk.com/embeddable_framework/main.js","hostjars.zendesk.com");
// End of HostJars Support Zendesk Widget script

$(document).ready(initSelectize);

function intercomTrack(e) {
  if (!$(e.target).attr('intercom-tracked'))
    var event = $(e.target).parent().attr('intercom-tracked');
  else
    var event = $(e.target).attr('intercom-tracked');

  if (event)
    Intercom('trackEvent', event);

  return false;
}

function initSelectize()
{
  setTimeout(function () {window.NC = false;}, 300);
  $('#nextRow').click(function() {
    buildSampleRow();
  });

  $('#configuration_form_submit_btn').on('click', function (e) {
    if (window.is_sending) return false;
    if (window.location.href.indexOf('step=1') !== -1) {
      //step1 form validations
      var files = $('#feed_file')[0].files;
      if (files.length > 0) {
        var file = files[0];
        var fileSizeRegex = new RegExp(/([0-9.]{1,})(k|K|m|M|G|g)?/);
        var _maxSize = MAX_FILE_SIZE.match(fileSizeRegex);
        var conversions = {
          'k' : 1024,
          'm' : 1048576,
          'g' : 1073741824
        };
        var maxSize = 999999;
        if (_maxSize[2] !== undefined) {
          //has a unit
          maxSize = parseInt(_maxSize[1]) * conversions[_maxSize[2].toLowerCase()];
        } else {
          maxSize = parseInt(_maxSize[1]);
        }
        if (file.size > maxSize) {
          displayAlert(false, 'File is too large, either reduce filesize or modify you ini file');
          return false;
        }
      }
      return true;
    } else if (window.location.href.indexOf('step=2') !== -1) {
      var unitVal = $('#unity').removeClass('error').val();
      var r = new RegExp(/([a-zA-Z\s])+/gi);
      if (!r.exec(unitVal)) {
        $('#unity').addClass('error');
        displayAlert(false, 'Unit must only contain letters');
        return false;
      }
    } else if (window.location.href.indexOf('step=3') !== -1) {
      var error = false;
      var validEmptyInputs = ['deleteRow', 'deleteRowWhereNot', 'remove', 'deleteRowWhereContains', 'deleteRowWhereNotContains', 'mergeColumns'];
      var validEmptySelects = ['mergeRows', 'splitCombinations'];
      $('tbody#operations td input[type="text"]').each(function (i, input) {
        //get the adjustment type
        var adj_type = $(input).parent().parent().find('input[type="hidden"]').val();
        if ($(input).val().length === 0 && validEmptyInputs.indexOf(adj_type) === -1) {
          error = true;
          $(input).addClass('error');
          e.preventDefault();
        } else {
          $(input).removeClass('error');
        }
      });

      $('tbody#operations td select').each(function (i, select) {
        //get the adjustment type
        var adj_type = $(select).parent().parent().find('input[type="hidden"]').val();
        if ($(select).val().length === 0 && validEmptySelects.indexOf(adj_type) === -1) {
          error = true;
          $(select).addClass('error');
          e.preventDefault();
        } else {
          $(select).removeClass('error');
        }
      });
      if (error) {
        displayAlert(false, 'Some operation values must be entered');
        return false;
      }
    }
    window.is_sending = true;
    return true;
  });

  window.$selects = [];
  var options = {
    create: false,
    sortField: "text",
  };
  $selects = $('td.source_field select').selectize(options);
  $('div.lang-select-group div.selectize-control:not(:first-of-type)').hide();

  $("#content").on('click', '[intercom-tracked]', intercomTrack);

  //step-2 multishop check
  if ($('#multishop-tree ul li input:checked').length === 0) {
    //no shop has been selected
    $('#multishop-tree ul li:eq(0) input').click(); //click the default shop input to cause the cascade
  }
}

/**
* Display a bootstrap alert
*
* @param			boolean		success
* @param			string		messageString		string to display
*
* @return     nil
*/
function displayAlert(success, messageString) {
	var alertClass = (success) ? 'alert-success' : 'alert-danger';
	$('div.alert-wrap div.alert.'+alertClass).remove(); //remove alerts of same type
	var html = '';
	html += '<div class="alert '+alertClass+'" role="alert">';
	html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
	html += messageString;
	html += '</div>';

	$('form.totalimportpro').prepend(html);
	$(document).scrollTop(0);
}

function addSub(el)
{
  sub = $(el).closest('.hori').children('td').children('.lang-select-group').first().clone();
  sub.attr('needsUpdate', 'true');
  $(el).before(sub);

  $('[needsUpdate="true"]').find('div.selectize-control').remove();
  $('[needsUpdate="true"]').find('select.selectized').removeClass('selectized').html(getOptions($selects[0].selectize.options)).selectize({
    create: false,
    sortField: "text",
  });
  $('[needsUpdate="true"]').removeAttr('needsUpdate').find('div.selectize-control:not(:first-of-type)').hide();
  $('.dropdown-toggle').dropdown();

  return false;
}

function addVert(el, multi)
{
  var newEl = $(el).gparent().clone().attr('needsUpdate', 'true');
  $(el).hide(); //hide the more button
  $(el).closest('.vert').after(newEl); //add new html to DOM

  if (multi) {
    $('[needsUpdate="true"]').find('.lang-select-group:not(:first-of-type)').remove();
  }

  $('[needsUpdate="true"]').find('div.selectize-control').remove();
  $('[needsUpdate="true"]').find('select.selectized').each(function () {
    var name = $(this).attr('name');
    var _name = /^(field_names\[category\])\[([0-9]{1,})\]\[([0-9]{1,})\]\[\]/g.exec(name);
    if (_name) {
      var newName = _name[1]+'['+_name[2]+']['+(parseInt(_name[3])+1)+'][]';
      $(this).attr('name', newName).removeClass('selectized').html(getOptions($selects[0].selectize.options)).selectize({
        create: false,
        sortField: "text",
      });
    } else {
      $(this).removeClass('selectized').html(getOptions($selects[0].selectize.options)).selectize({
        create: false,
        sortField: "text",
      });
    }
  });
  $('[needsUpdate="true"]').removeAttr('needsUpdate').find('div.lang-select-group div.selectize-control:not(:first-of-type)').hide();
  $('.dropdown-toggle').dropdown();
  return false;
}

function getOptions(options)
{
  var html = '<option value="">None</option>';
  for (i in options) {
    html += '<option value="'+options[i].value+'">'+options[i].text+'</option>';
  }
  return html;
}

function addCombo(el)
{
  var newEl = $(el).gparent().clone().attr('needsUpdate', 'true');
  $(el).hide(); //hide the more button
  $(el).closest('.combo').after(newEl); //add new html to DOM

  $('[needsUpdate="true"]').find('div.selectize-control').remove();
  $('[needsUpdate="true"]').find('select.selectized').removeClass('selectized').html(getOptions($selects[0].selectize.options)).selectize({
    create: false,
    sortField: "text",
  });
  $('[needsUpdate="true"]').removeAttr('needsUpdate').find('div.lang-select-group div.selectize-control:not(:first-of-type)').hide();

  return false;
}

function addCatVert(el, multi)
{
  newEl = '<tr class="vert';
  if (multi)
  {
    newEl += ' hori';
  }
  newEl += '">' + $(el).closest('.vert').html() + '</tr>';
  if (multi)
  {
    matches = newEl.match(/\]\[(\d+)\]\[\]/);
    count = parseInt(matches[1]);
    count = count + 1;
    newEl = newEl.replace(']['+(count-1).toString()+'][]', ']['+count.toString()+'][]');
  }
  $(el).hide();
  $(el).closest('.vert').after(newEl);
  return false;
}

function hideOtherLanguages(caller, lang_code, lang_name) {
  caller.closest('.dropdown').children('.dropdown-toggle').html(lang_name + '<span class="caret"></span>');
  caller.closest('.lang-select-group').children('.selectize-control').hide();
  caller.closest('.lang-select-group').children('.selectize-control.lang-' + lang_code).show();
}

function removeSelectBefore(el)
{
  $(el).prev().remove();
  $(el).remove();
}

function strip_tags (input, allowed) {
  allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(""); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
      commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;

  if (input === null) return "";
  return input.replace(commentsAndPhpTags, "").replace(tags, function ($0, $1) {
    return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : "";
  });
}

function buildSampleRow() {
  $.ajax({
    url: window.ajaxUrl,
    type: 'post',
    data: 'ajax=true&action=GetNextRow&nextRow=' + $('#nextRow').val(),
    dataType: 'json',
    success: function(json) {
      if (json) {
        $('#nextRow').val(parseInt($('#nextRow').val()) + 1);
        $('#sampleFields tbody').empty().append('<tr>');
        $('#sampleFields thead tr th').each(function() {
          tmp = strip_tags(json[$(this).text().trim()]);
          $('#sampleFields tbody tr').append('<td class="text-left">'+ ((tmp.length > 90) ? tmp.substr(0, 90) + '...' : tmp) + "</td>");
        });
      }
      else {
        $('#nextRow').val(0);
        buildSampleRow();
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
}

$.fn.gparent = function () {
  return $(this).parent().parent();
};
