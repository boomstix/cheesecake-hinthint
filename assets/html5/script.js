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
		
		// is there a field with name '_other'
		var other_field = settings.other_rdo_field_id ? $('#' + settings.other_rdo_field_id) : false;
		// new inputs we add
		var inputs = [];
		
		function clearActive() {
			for (ix = 0, iz = inputs.length; ix < iz; ix = ix + 1) {
				$(inputs[ix]).removeClass(settings.activeClass);
			};
		}
		
		function setActive() {
			var el = $(this);
			el.data('radio').attr('checked','checked');
			clearActive();
			el.addClass(settings.activeClass);
		}
		
		this.each(function(ix, el){
		
			var button, text;
			el = $(el);
			
			if (settings.other_rdo_field_id && (el.attr('id') == settings.other_rdo_field_id)) {
				text = $('#' + settings.other_txt_field_id);
				text.data('radio', el);
				text.on('focus', setActive);
				inputs.push(text);
			}
			else {
				button = $(document.createElement('input'));
				el.after(button);
				button.data('radio', el);
				button.attr('value', el.val());
				button.attr('type','button');
				button.on('click', setActive);
				inputs.push(button);
			}
			el.parent().removeClass('radio').addClass('form-group');
			el.addClass(settings.hiddenClass);
			$('label[for="' + el.attr('id') + '"]').addClass(settings.hiddenClass);
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

