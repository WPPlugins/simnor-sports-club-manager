jQuery(function() {
	
/* Options Panel Tabs */
	var tabtoshow = 0;
	if(jQuery('.sn-plugin-options-wrap').size() > 0) {
		jQuery('.sn-plugin-options-wrap .sn-options-tabpane').hide();
		jQuery('.sn-plugin-options-wrap .sn-options-tabpane').eq(0).show();
		jQuery('.sn-plugin-options-wrap h2.nav-tab-wrapper a').click(function() {
			tabtoshow = jQuery(this).parent('h2.nav-tab-wrapper').find('a').index(jQuery(this));
			jQuery('.sn-plugin-options-wrap h2.nav-tab-wrapper a').removeClass('nav-tab-active');
			jQuery('.sn-plugin-options-wrap h2.nav-tab-wrapper a').eq(tabtoshow).addClass('nav-tab-active');
			jQuery('.sn-plugin-options-wrap .sn-options-tabpane').hide();
			jQuery('.sn-plugin-options-wrap .sn-options-tabpane').eq(tabtoshow).show();
			return false;
		});
	}
	if(jQuery('.sn-post-options-wrap').size() > 0) {
		jQuery('.sn-post-options-wrap .sn-post-options-wrap-tabpane').hide();
		jQuery('.sn-post-options-wrap .sn-options-tabpane').eq(0).show();
		jQuery('.sn-post-options-wrap h2.nav-tab-wrapper a').click(function() {
			tabtoshow = jQuery(this).parent('h2.nav-tab-wrapper').find('a').index(jQuery(this));
			jQuery('.sn-post-options-wrap h2.nav-tab-wrapper a').removeClass('nav-tab-active');
			jQuery('.sn-post-options-wrap h2.nav-tab-wrapper a').eq(tabtoshow).addClass('nav-tab-active');
			jQuery('.sn-post-options-wrap .sn-options-tabpane').hide();
			jQuery('.sn-post-options-wrap .sn-options-tabpane').eq(tabtoshow).show();
			return false;
		});
	}
	if(jQuery('.sn-post-options-wrap h2.nav-tab-wrapper a').size() < 2) { jQuery('.sn-post-options-wrap h2.nav-tab-wrapper').hide(); }

/* Repeater */
	if(jQuery('.sn-repeater').size() > 0) {
		
		var repeater_counter = 0;
		var repeater_field_id = '';
		function update_repeater_panes() {
			jQuery('.sn-repeater').each(function() {
				repeater_counter = 0;
				jQuery(this).find('li.sn-repeater-item').each(function() {
				if(jQuery(this).parent('ul').hasClass('sn-repeater-items')) {
					repeater_counter++;
					jQuery(this).attr({'id':'repeater__'+repeater_counter});
					jQuery(this).find('input[type="text"], input[type="email"], input[type="checkbox"], input[type="radio"], select, textarea').each(function() {
						if(jQuery('.sn-plugin-options-wrap').size() > 0) {
							repeater_field_id = jQuery(this).attr('id');
							if(repeater_field_id == undefined) { repeater_field_id = "__"; }
							repeater_field_id = repeater_field_id.split('__');
							repeater_field_id = repeater_field_id[0]+'__'+repeater_counter+']';
						} else {
							repeater_field_id = jQuery(this).attr('id');
							if(repeater_field_id == undefined) { repeater_field_id = "__"; }
							repeater_field_id = repeater_field_id.split('__');
							repeater_field_id = repeater_field_id[0]+'__'+repeater_counter+'';
						}
						jQuery(this).attr({"id":repeater_field_id});
						jQuery(this).attr({"name":repeater_field_id});
					});
				}
				});
			});
		}
		
		/* Set up Sortable */
		jQuery('.sn-repeater').each(function() {
			jQuery(this).find('ul').sortable({
				handle: ".sn-repeater-pane-heading",
				start: function(e, ui) {
					jQuery(this).find('li.sn-repeater-item').removeClass('active');
					jQuery(this).find('li.sn-repeater-item').find('.sn-repeater-pane-content').hide();
					jQuery(this).find('.tinyMCE').each(function(){
				        tinyMCE.execCommand( 'mceRemoveControl', false, jQuery(this).attr('id') );
				    });
				},
				update: function(event, ui) {
					update_repeater_panes();
				}
			});	
		});
		
		/* Add New Pane */
		jQuery('.add-new-repeater-pane').click(function() {
			jQuery(this).parentsUntil('.sn-repeater').parent('.sn-repeater').find('ul.sn-repeater-items').append('<li id="repeater__NEW" class="sn-repeater-item">'+jQuery(this).parentsUntil('.sn-repeater').parent('.sn-repeater').find('.repeater_template').html()+'</li>');
			jQuery(this).parentsUntil('.sn-repeater').parent('.sn-repeater').find('li#repeater__NEW').find('input[type="text"], input[type="email"], input[type="checkbox"], input[type="radio"], select, textarea').each(function() {
				jQuery(this).val('');
			});
			jQuery(this).parentsUntil('.sn-repeater').parent('.sn-repeater').find('li#repeater__NEW').find('.sn-repeater-pane-heading').find('h4').text('');
			jQuery(this).parentsUntil('.sn-repeater').parent('.sn-repeater').find('li#repeater__NEW').find('.sn-repeater-pane-content').show();
			jQuery(this).parentsUntil('.sn-repeater').parent('.sn-repeater').find('li#repeater__NEW').toggleClass('active');
			
			/* Colorpicker */
			if(jQuery(this).parentsUntil('.sn-repeater').parent('.sn-repeater').find('li#repeater__NEW').find('.sn-options-field-color-picker').size() > 0) { 
				jQuery(this).parentsUntil('.sn-repeater').parent('.sn-repeater').find('li#repeater__NEW').find('.sn-options-field-color-picker').each(function() {
					jQuery(this).wpColorPicker();
				});
			}
			
			update_repeater_panes();
			
			/* Chosen */
			jQuery(this).parentsUntil('.sn-repeater').parent('.sn-repeater').find('li.sn-repeater-item:last').find('.chosen').each(function() {
				jQuery(this).chosen();
			});
			
			return false;
		});
		
		/* Toggle Options */
		jQuery(document).delegate('.sn-repeater-toggle-options', 'click', function() {
			jQuery(this).parentsUntil('li').parent('li').find('.sn-repeater-pane-content').slideToggle();
			jQuery(this).parentsUntil('li').parent('li').toggleClass('active');
			jQuery(this).parentsUntil('li').parent('li').find('.tinyMCE').each(function(){
		        tinyMCE.execCommand( 'mceRemoveControl', false, jQuery(this).attr('id') );
		    });
			jQuery(this).parentsUntil('li').parent('li').find('.tinyMCE').each(function(){
		        tinyMCE.execCommand( 'mceAddControl', true, jQuery(this).attr('id') );
		    });
			return false;
		});
		
		/* Remove Pane */
		jQuery(document).delegate('.sn-repeater-remove', 'click', function() {
			jQuery(this).parentsUntil('li').parent('li').remove();
			update_repeater_panes();
			return false;
		});
	
	}
	
/* Datepicker */
	jQuery(document).delegate('.sn-options-field-date-picker', 'focus', function() {
		jQuery(this).datepicker({dateFormat: 'yy-mm-dd'});
	});
	
/* Colorpicker */
	if(jQuery('.sn-options-field-color-picker').size() > 0) {
		jQuery('.sn-options-field-color-picker').each(function() {
			if(jQuery(this).parentsUntil('.repeater_template').parent('.repeater_template').hasClass('repeater_template')) { } else {
				jQuery(this).wpColorPicker();
			}
		});
	}
	
/* Chosen */
	if(jQuery('.chosen').size() > 0) {
		jQuery('.chosen').each(function() {
			if(jQuery(this).parentsUntil('.repeater_template').parent('.repeater_template').hasClass('repeater_template')) { } else {
				jQuery(this).chosen();
			}
		});
	}
	
/* Checkbox */
	jQuery(document).delegate('.sn-options-field-checkbox input[type="checkbox"]', 'click', function() {
		if(jQuery(this).is(':checked')) {
			jQuery(this).parent('.sn-options-field-checkbox').find('input[type="text"]').val(1);			
		} else {
			jQuery(this).parent('.sn-options-field-checkbox').find('input[type="text"]').val(0);			
		}
	});

/* Upload */
	jQuery(document).delegate('.sn-options-field-upload-button', 'click', function() {
		var field_id    = jQuery(this).parent('div').parent('div').find('.sn-options-field-upload-text').attr('name'),
		    post_id     = jQuery(this).attr('rel'),
		    backup      = window.send_to_editor,
		    showImageHTML  = '',
		    intval      = window.setInterval(function() {
				jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use this file'); 
				jQuery('#TB_iframeContent').contents().find('.savesend .button').addClass('button-primary'); 
				jQuery('#TB_iframeContent').contents().find('.savesend .button').css({"marginTop":"10px", "marginBottom":"20px"}); 
			}, 50);
		tb_show('', 'media-upload.php?post_id='+post_id+'&field_id='+field_id+'&type=image&TB_iframe=1');
		window.send_to_editor = function(html) {
		  var href = jQuery(html).attr('href');
		  var href_filename = href.substr( (href.lastIndexOf('.') +1) );
		  switch(href_filename) {
			case 'jpeg':
			case 'jpg':
			case 'png':
			case 'gif':
				showImageHTML += '<div class="show-image"><img src="'+href+'" alt="" /></div>';
			break;
		  }
		  jQuery('input[name="'+field_id+'"]').val(href);
		  jQuery('input[name="'+field_id+'"]').parent('div').find('.show-image').remove();
		  jQuery('input[name="'+field_id+'"]').parent('div').append(showImageHTML);
		  tb_remove();
		  window.clearInterval(intval);
		  window.send_to_editor = backup;
	    };
		return false;
	});    
	jQuery(document).delegate('.sn-options-field-remove-upload', 'click', function() {
		jQuery(this).parent('div').parent('div').find('.sn-options-field-upload-text').attr({'value':''})
		jQuery(this).parent('div').parent('div').find('.show-image').remove();
		return false;
	});
	
	if(jQuery('#sn-fixture-meta').size() > 0) {
		jQuery('#titlediv').after('<h2 style="font-size: 18px;">Game Report</h2>');
		jQuery('#title-prompt-text').text('Add fixture name (eg, the team you\'re playing)');
	}
	if(jQuery('#sn-player-meta').size() > 0) {
		jQuery('#postimagediv h3 span').text('Player Image');
		jQuery('#titlediv').after('<h2 style="font-size: 18px;">Player Biography</h2>');
		jQuery('#title-prompt-text').text('Add player name');
	}
	
});