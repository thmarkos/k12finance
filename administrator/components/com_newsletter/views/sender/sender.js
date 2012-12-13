/**
 * The javascript file for sender view.
 *
 * @version	   $Id:  $
 * @copyright  Copyright (C) 2011 Migur Ltd. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE.txt
 */

window.addEvent('domready', function() {
try {

	if (defaultMailbox < 1){
		
		alert(Joomla.JText._(
			'DEFAULT_MAILBOX_PROFILE_UNDEFINED', 
			'Default mailbox profile is undefined. \n'+
			'If you are going to mail newsletters that uses Joomla! standard SMTP profile \n'+
			'then you probably cant use the "Process bounces" feature'
		));
	}

    historyPaginator = new Migur.lists.paginator($$('.sslist')[0]);
    Migur.lists.sortable.setup($$('.sslist')[0]);

    $$('#sender-export a')[0].addEvent('click', function(event){

        event.stop();

		if ($(this).hasClass('disabled')) {
			return false;
		}

        var newsletterId = $$('select')[0].get('value');

        if ( !newsletterId ) {
            alert(Joomla.JText._('PLEASE_SELECT_NEWSLETTER_FIRST','Please selct newsletter first'));
            return;
        }

        var lists = [];
        $$('[name=cid[]]').each(function(el){
            if (el.getProperty('checked')) {
            lists.push(el.get('value'));
        }
        });

        if ( lists.length == 0 ) {
            alert(Joomla.JText._('PLEASE_SELECT_AT_LEAST_ONE_LIST','Please selct at least one list'));
            return;
        }


        if ( confirm(Joomla.JText._(
			'DO_YOU_REALY_WANT_TO_SEND_THIS_NEWSLETTER_QM',
			"Do you realy want to send this newsletter?"
		)) ) {

			new Migur.iterativeAjax({

				url: '?option=com_newsletter&task=sender.addtoqueue&format=json',

				data: {
					lists: lists,
					newsletter_id: newsletterId
				},

				messagePath: '#send-message',
				preloaderPath: '#send-preloader',

				method: 'get',

				// all in one step
				onComplete: function(messages, data){

					$$('#sender-export a')[0].removeClass('disabled');

					this.showAlert(

						Joomla.JText._(
							'THE_NEWSLETTER_HAS_BEEN_QUEUED_SUCCESFULLY', 
							'The newsletter(s) has been queued succesfully'
						) + "\n" +
						Joomla.JText._('TOTAL','Total') + ": " + data.total + "\n" +
						Joomla.JText._('ERRORS', 'Errors') + ": " + data.errors + "\n"
					);

					document.location.reload();
				}
				
            }).start();
			
			$$('#sender-export a')[0].addClass('disabled');
        }
    });


    
} catch(e){
    if (console && console.log) console.log(e);
}
});
