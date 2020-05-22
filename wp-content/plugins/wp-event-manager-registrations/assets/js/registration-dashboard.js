jQuery(document).ready(function($) {

	$('section.event-registration-content, section.event-registration-notes, section.event-registration-edit').hide().prepend('<a href="#" class="hide_section">' + event_manager_registration.i18n_hide + '</a>');

	$('form.filter-event-registrations').on('change', 'select', function(){
		$('form.filter-event-registrations').submit();
	});

	$('#event-manager-event-registrations')
	
	.on( 'click', '.event-registration-toggle-content', function() {
		$(this).closest('li.event-registration').find('section:not(.event-registration-content)').slideUp();
		$(this).closest('li.event-registration').find('section.event-registration-content').slideToggle();
		return false;
	})
	.on( 'click', '.event-registration-toggle-edit', function() {
		$(this).closest('li.event-registration').find('section:not(.event-registration-edit)').slideUp();
		$(this).closest('li.event-registration').find('section.event-registration-edit').slideToggle();
		return false;
	})
	.on( 'click', '.event-registration-toggle-notes', function() {
		$(this).closest('li.event-registration').find('section:not(.event-registration-notes)').slideUp();
		$(this).closest('li.event-registration').find('section.event-registration-notes').slideToggle();
		return false;
	})
	.on( 'click', 'a.hide_section', function() {
		$(this).closest('section').slideUp();
		return false;
	})
	.on( 'click', '.event-registration-note-add input.button', function() {
		var button                     = $(this);
		var registration_id             = button.data('registration_id');
		var event_registration            = $(this).closest('.event-registration');
		var event_registration_note       = event_registration.find('textarea');
		var disabled_attr              = $(this).attr('disabled');
		var event_registration_notes_list = event_registration.find('ul.event-registration-notes-list');

		if ( typeof disabled_attr !== 'undefined' && disabled_attr !== false ) {
			return false;
		}
		if ( ! event_registration_note.val() ) {
			return false;
		}

		button.attr( 'disabled', 'disabled' );

		var data = {
			action: 		'add_event_registration_note',
			note: 			event_registration_note.val(),
			registration_id: registration_id,
			security: 		event_manager_registration.event_registration_notes_nonce,
		};

		$.post( event_manager_registration.ajax_url, data, function( response ) {
			event_registration_notes_list.append( response );
			button.removeAttr( 'disabled' );
			event_registration_note.val( '' );
		});

		return false;
	})
	.on( 'click', 'a.delete_note', function() {
		var answer = confirm( event_manager_registration.i18n_confirm_delete );
		if ( answer ) {
			var button  = $(this);
			var note    = $(this).closest('li');
			var note_id = note.attr('rel');

			var data = {
				action: 		'delete_event_registration_note',
				note_id:		note_id,
				security: 		event_manager_registration.event_registration_notes_nonce,
			};

			$.post( event_manager_registration.ajax_url, data, function( response ) {
				note.fadeOut( 500, function() {
					note.remove();
				}); 
			});
		}
		return false;
	})
	.on( 'click', 'a.delete_event_registration', function() {
		var answer = confirm( event_manager_registration.i18n_confirm_delete );
		if ( answer ) {
			return true;
		}
		return false;
	});
});