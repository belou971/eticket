/**
 * Created by Eve ODIN on 24/10/2017.
 */

/*****************************************************************************/
/*                     Functions definitions                                 */
/*****************************************************************************/
function getNumberPlaceForDate($inputDate)
{
    var $data = {date: $inputDate};
    $.post("availableDate", $data)
        .done( function(infos) {
                $('.booking-date-info').text(infos.date);
                //récuperer la balise correspondant a la classe .available-place-info
                $('.available-place-info').text(infos.nbPlace);
            }
        );
}


function displayDateInfo()
{
    $('.dateinfo').css('display', 'block');
}

//Change color state of booking type buttons on a click event associated to these buttons
function set_active_class($element) {
    $element.find('.btn').toggleClass('active');

    if ($element.find('.btn-success').length>0) {
        $element.find('.btn').toggleClass('btn-success');
    }

    $element.find('.btn').toggleClass('btn-default');
}

//Build a ticket form
function ticketFormBuilder() {
    $.post("add")
        .done( function(data) {

                var $ticketsDiv = $('div#eo_eticketbundle_booking_tickets');
                var $index = $ticketsDiv.children().length;
                var $ticketForm = replaceBy(data.ticket,'__name__', $index);
                $ticketsDiv.append($ticketForm);
                if($index === 0) {
                    var $firstTicket = $ticketsDiv.children().first();
                    autoFillTicketForm($firstTicket);
                }

            }
        );
}

//To Fill automatically the first ticket with address information of the buyer
function autoFillTicketForm($ticket) {
    var $address = $('.coordonnees');

    var $allInputText = $ticket.find(':input');

    $allInputText.each(function() {
            if($(this).hasClass('name')) {
                var $addressName = $address.find('.name');
                copyTo($addressName, $(this));
            }

            if($(this).hasClass('surname')) {
                var $addressSurname = $address.find('.surname');
                copyTo($addressSurname, $(this));
            }
        }
    );
}

function copyTo($from, $to) {
    $to.val( $from.val() );
}

function replaceBy($content, $old, $new) {
    var toReplace = new RegExp($old, "g");
    return $content.replace(toReplace, $new);
}

function showStep($tag) {
    $tag.show();
}

function hideStep($tag) {
    $tag.hide();
}

function isEmpty($tag) {
   return ($tag.val().length === 0);
}

function readyStep() {

}

/*****************************************************************************/
/*                     Bootstrap Datepicker interactions                     */
/*****************************************************************************/
var $dpicker = $('input.date-picker');

$dpicker.datetimepicker({
    locale: 'fr',
    format: 'L',
    inline: true,
    minDate: new Date(),
    daysOfWeekDisabled: [0, 6]
});

$dpicker.on('dp.change', function(e){
    $('input.date-picker').val(e.date.format('L'));

    getNumberPlaceForDate(e.date.format('L'));

    displayDateInfo();
});


/*****************************************************************************/
/*                     Booking type buttons interaction                     */
/*****************************************************************************/
$('.btn-toggle').on('click', function() {
    set_active_class($(this));
});

$('.booking-choice-btn').on('click', function (){
    var inputElement = $(this).parent().find('input');
    if(inputElement !== null) {
        var choice = $(this).val();
        inputElement.val(choice);
    }
});

/*****************************************************************************/
/*                         Add ticket button interaction                     */
/*****************************************************************************/
$('.addBtn').on('click', function(e) {
    ticketFormBuilder();
    e.preventDefault();
});

/*****************************************************************************/
/*                         step button interaction                           */
/*****************************************************************************/


/*****************************************************************************/
/*                          Init booking form                                */
/*****************************************************************************/
$(document).ready( function(){
    var $step1 = $('.step-booking');
    var $step2 = $('.step-tickets');

 /* ******************* Step1 Handling ********************* */
    showStep($step1);
    hideStep($step2);

    var $step1BtnDiv = $step1.find('.step-buttons-container');
    if($step1BtnDiv !== null && $step1BtnDiv.length === 1) {
        hideStep($step1BtnDiv);
    }

    var $coordonnees = $step1.find('.coordonnees');
    var $allInputText = $coordonnees.find('input[type=text], input[type=email]');
    $allInputText.on('input', function() {
            var $continue = true;
            $allInputText.each(function() {
                $continue = $continue && (!isEmpty($(this))) ;
            });

            if($continue === true) {
                showStep($step1BtnDiv);
            }
            else {
                hideStep($step1BtnDiv);
            }
        }
    );

    /* ******************* Step2 Handling ********************* */
    var $step1Btn = $step1BtnDiv.find('button');
    $step1Btn.on('click', function() {
            var $ticketsDiv = $('div#eo_eticketbundle_booking_tickets');
            if($ticketsDiv.children().length === 0) {
                ticketFormBuilder();
            }
            showStep($step2);
            hideStep($step1);
        }
    );

    var $step2BtnDiv = $step2.find('.step-buttons-container');
    var $step2PrevBtn = $step2BtnDiv.find('.previousBtn');
    $step2PrevBtn.on('click', function(){
            showStep($step1);
            hideStep($step2);
        }
    );
});

