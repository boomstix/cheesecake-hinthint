$(function(){

	// replace radio buttons with regular buttons which have an active state
	// and which select the corresponding radio value
	
	$.fn.ButtonsForRadio = function(options) {
		
		var settings = $.extend( {
			other_rdo_field_id: false
		,	other_txt_field_id: false
		,	activeClass: 'active-button'
		,	hiddenClass: 'hidden'
		}, $.fn.ButtonsForRadio.defaults, options );
		
		// Is there a field with name '_other'
		// var other_field = settings.other_rdo_field_id ? $('#' + settings.other_rdo_field_id) : false;
		// New input elements we add to the DOM
		var inputs = [];
		
		function clearActive() {
			// Clear the active class from the new elements added to the DOM
			for (ix = 0, iz = inputs.length; ix < iz; ix = ix + 1) {
				$(inputs[ix]).removeClass(settings.activeClass);
			};
		}
		
		function setActive() {
			var el = $(this);
			// Check the hidden radio button
			el.data('radio').attr('checked','checked');
			// Clear any new elements that have been set active
			clearActive();
			// Set this new element to active
			el.addClass(settings.activeClass);
			/*
			// Clear the other field if other is not selected
			if (el.data('radio').attr('id') != settings.other_rdo_field_id) {
				$('#' + settings.other_txt_field_id).val('');
			}*/
		}
		
		this.each(function(ix, el){
		
			var newElement;
			el = $(el);
			// Is this the 'other' field and the other radio's id has been passed in
			if (settings.other_rdo_field_id && (el.attr('id') == settings.other_rdo_field_id)) {
				// Allow focus of the other text field to set the other radio button
				newElement = $('#' + settings.other_txt_field_id);
				newElement.on('focus', setActive);
			}
			else {
				// Add a button whose text is the radio button's value
				newElement = $(document.createElement('input'));
				el.after(newElement);
				newElement.attr('value', el.val());
				newElement.attr('type','button');
				// Clicking sets the corresponding radio button
				newElement.on('click', setActive);
			}
			// Store the radio button tied to this element
			newElement.data('radio', el);
			// Stash the new element
			inputs.push(newElement);
			// Remove the radio layout css class from the radio button's parent
			el.parent().removeClass('radio').addClass('form-group');
			// Hide the radio button
			el.addClass(settings.hiddenClass);
			$('label[for="' + el.attr('id') + '"]').addClass(settings.hiddenClass);
			// If the radio button is in the checked state, add the active class
			if (el.attr('checked') == 'checked') {
				newElement.addClass(settings.activeClass);
			}
			
		});
		
		return this;
		
	}
	
	$('input[name="cake"]').ButtonsForRadio({
			other_rdo_field_id: "cake-other"
		,	other_txt_field_id: "txt-cake-other"
		});

	$('input[name="reminder_to"]').ButtonsForRadio();

	$('input[name="reminder_date"]').ButtonsForRadio();

});

