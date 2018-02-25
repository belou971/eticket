/**
 * Created by Eve ODIN on 24/10/2017.
 */

/*****************************************************************************/
/*                     Functions definitions                                 */
/*****************************************************************************/
function initialize($step1, $step2)
{
    /* ******************* Step1 Handling ********************* */
    showStep($step1);
    hideStep($step2);

    var $step1BtnDiv = $step1.find('.step-buttons-container');
    if($step1BtnDiv !== null && $step1BtnDiv.length === 1) {
        hideStep($step1BtnDiv);
    }

    var $coordonnees = $step1.find('.coordonnees');
    var $allInputText = $coordonnees.find('input[type=text], input[type=email], select');
    var enableNextBtn = function() {
        var $continue = true;
        $allInputText.each(function() {
            if($(this).is('input')) {
                $continue = $continue && (!isEmpty($(this)));
            }

            if($(this).is('select')) {
                $continue = $continue && isSelected($(this));
            }
        });

        $continue = $continue && hasDateVisitor();

        if($continue === true) {
            showStep($step1BtnDiv);
        }
        else {
            hideStep($step1BtnDiv);
        }
    };
    enableNextBtn();

    $allInputText.on('change', enableNextBtn);
    $allInputText.on('input', enableNextBtn);

    /* ******************* Step2 Handling ********************* */
    var $step1Btn = $step1BtnDiv.find('button');
    $step1Btn.on('click', function() {

        var $ticketsDiv = $('div#eo_eticketbundle_booking_tickets');
        if($ticketsDiv.children().length === 0) {
            ticketFormBuilder();
        }
        showStep($step2);
        hideStep($step1);
        updateStepName(2);
    }
    );

    var $step2BtnDiv = $step2.find('.step-buttons-container');
    var $step2PrevBtn = $step2BtnDiv.find('.previousBtn');
    $step2PrevBtn.on('click', function(){
            showStep($step1);
            hideStep($step2);
            updateStepName(1);
        }
    );
}


function getNumberPlaceForDate($inputDate)
{
    var $data = {date: $inputDate};
    $.post("availableDate", $data)
        .done( function(infos) {
                $('.booking-date-info').text(infos.date);
                //récupérer la balise correspondant a la classe .available-place-info
                $('.available-place-info').text(infos.nbPlace);
            }
        );
}

function displayDateInfo()
{
    $('.dateinfo').css('display', 'block');
}

function hideDateInfo()
{
    $('.dateinfo').css('display', 'none');
}

//Change color state of booking type buttons on a click event associated to these buttons
function set_active_class($element) {
    $element.find('.btn').toggleClass('active');

    if ($element.find('.my-btn-success').length>0) {
        $element.find('.btn').toggleClass('my-btn-success');
    }

    $element.find('.btn').toggleClass('btn-default');
}

function bindInputs($ticket) {
    var $allBDayInputs = $ticket.find('select');

    $allBDayInputs.on('change', function($event) {
        var $element = $event.target;
        var $ticket_id = $element.closest('.ticket-container').id;
        updatePrice($ticket_id);
    }) ;
}

function getIndex() {
    var $ticketsDiv = $('div#eo_eticketbundle_booking_tickets');

    if($ticketsDiv.children().length == 0)
        return 0;

    var $lastTicket = $ticketsDiv.children().last();
    var $lastTicketId = $lastTicket.attr('id');
    return Number($lastTicketId.substr(33)) + 1;
}

//Build a ticket form
function ticketFormBuilder() {

    var $ticketsDiv   = $('div#eo_eticketbundle_booking_tickets');
    var $selectedDate = $('input.date-picker').val();
    var $ticketinfos  = {nbaddedticket: $ticketsDiv.children().length, date: $selectedDate};
    $.post("add", $ticketinfos)
        .done( function(data) {

                var $index = getIndex();
                var $ticketForm = replaceBy(data.ticket,'__name__', $index);
                $ticketsDiv.append($ticketForm);

                if($index === 0) {
                    var $firstTicket = $ticketsDiv.children().first();
                    autoFillTicketForm($firstTicket);
                    updatePrice($firstTicket.attr('id'));
                }

                var $newTicket = $ticketsDiv.find(':last-child');
                handlingBtns($newTicket);
                bindInputs($newTicket);

                var $disabled = (data.place <= 0);
                $('.addBtn').attr('disabled', $disabled);

                if($ticketsDiv.children().length > 0) {
                    var $step2 = $('.step-tickets');
                    var $step2BtnDiv = $step2.find('.step-buttons-container');
                    var $step2NextBtn = $step2BtnDiv.find('.nextBtn');
                    showStep($step2NextBtn);
                }
            }
        );
}

function loadPrices() {

    var $ticketsContainer   = $('div#eo_eticketbundle_booking_tickets');
    var $nbTickets          = $ticketsContainer.children().length;
    var $tickets            = $ticketsContainer.children();

    $tickets.each(function(){
        var $ticket = $(this).find('.ticket-container');
        var $ticket_id = $ticket.attr('id');
        updatePrice($ticket_id);
        handlingBtns($ticket);
    });
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

            if($(this).hasClass('day')) {
                var $addressDay = $address.find('select[name*=day]');
                copyTo($addressDay, $(this));
            }

            if($(this).hasClass('month')) {
                var $addressMonth = $address.find('select[name*=month]');
                copyTo($addressMonth, $(this));
            }

            if($(this).hasClass('year')) {
                var $addressYear = $address.find('select[name*=year]');
                copyTo($addressYear, $(this));
            }
        }
    );
}


function handlingBtns($ticket) {
    var $delBtn = $ticket.find('.delete-btn');
    $delBtn.bind('click', function() {
        $(this).unbind('click');
        $(this).parent().closest('.ticket-container').remove();
        if($('div#eo_eticketbundle_booking_tickets').children().length <= 0) {
            var $step2 = $('.step-tickets');
            var $step2BtnDiv = $step2.find('.step-buttons-container');
            var $step2NextBtn = $step2BtnDiv.find('.nextBtn');
            hideStep($step2NextBtn);
        }
        computeInvoice();
    });

    var $reducing_checkbox = $ticket.find('input[type=checkbox]');
    $reducing_checkbox.bind('change', function() {
        //if reduce button is checked
        if($reducing_checkbox.is(':visible')) {
            if ($reducing_checkbox.prop('checked')) {
                var $reducing_rate_info = $rates.find(isReducingRate);
                setRateHeader($ticket, $reducing_rate_info.type, $reducing_rate_info.value);
            }
            else {
                var $ticket_id = $ticket.attr('id');
                updatePrice($ticket_id);
            }
        }
    });
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

function isSelected($tag) {
    var $val = $('option:selected', $tag).attr('value');
    return ( (Number($val) !== Number.NaN) && (Number($val)>0) );
}

function initRates() {
    $.get("load")
        .done( function(data) {
            $.each(JSON.parse(data), function($idx, $obj) {
                $rates.push({"id": $obj.id, "type": $obj.type, "ageMax":$obj.ageMax, "value": $obj.value});
            });

            loadPrices();
        });
}

function isReducingRate(item) {
    return (item.type === "réduit");
}

function isNormalRate(item) {
    return (item.type === "normal");
}

function isSeniorRate(item) {
    return (item.type === "sénior");
}

function setRateHeader($ticket, $type, $value ) {
    var $ticket_title = $ticket.find('.ticket-title ');
    if($ticket_title.length === 1) {
        var $ticket_header = "Ticket " + $type;
        $ticket_title.text($ticket_header);
    }

    var $rate_header = $ticket.find('.rate-type');
    if($rate_header.length === 1) {
        var $newTitle = "Tarif "+ $type;
        $rate_header.text($newTitle);

        var $inputRateType = $ticket.find('input[id*=rateType]');
        if ($inputRateType.length === 1) {
            $inputRateType.val($type);
        }

        var $inputRateValue = $ticket.find('input[id*=value]');
        if($inputRateValue.length === 1) {
            if($value !== '') {
                $inputRateValue.val($value.toFixed(2));
            }
            else {
                $inputRateValue.val('');
            }
        }
    }

    computeInvoice();
}

function getAge($ticket) {
    var $year = $ticket.find('select[name*=year]');
    var $month = $ticket.find('select[name*=month]');
    var $day = $ticket.find('select[name*=day]');

    if(($year.val() === '') || ($month.val() === '') || ($day.val() === '')) {
        return null;
    }

    var $birthday = new Date($year.val(), $month.val(), $day.val());
    var $now = Date.now();

    return (($now - $birthday)/31536000000).toFixed(0);
}

function findRate($age){
    return $rates.find(function($rate){
        if( ($rate.type !== "t.v.a") && ($rate.type !== "réduit") ) {
            return ( ($rate.ageMax !== null) && ($age <= $rate.ageMax) ) ;
        }
    });
}

function getTVA(){
    return $rates.find(function($rate){
        return ($rate.type === "t.v.a");
    });
}

function updatePrice($ticket_id) {
    var $ticket = $('#'+$ticket_id);
    var $reducing_checkbox = $ticket.find('input[type=checkbox]');
    var $reducing_container = $ticket.find('div[id*=container]');

    //compute the visitor's age
    var $visitorAge = getAge($ticket);

    if($visitorAge !== null) {
        //update rate section and the title of the ticket
        var $rate_info = findRate($visitorAge);
        if ($rate_info) {
            setRateHeader($ticket, $rate_info.type, $rate_info.value);
            if(isNormalRate($rate_info) || isSeniorRate($rate_info)) {
                showStep($reducing_container);
            }
            else {
                hideStep($reducing_container);
            }
        }
    }
    else {
        setRateHeader($ticket, '', '');
        hideStep($reducing_container);
    }
    $reducing_checkbox.prop('checked', false);
}

function showInvalidStep($step1, $step2)
{
    var $step1HasError = ($step1.find('.has-error').length > 0);
    var $step2HasError = ($step2.find('.has-error').length > 0);

    if($step1HasError) {
        //Nothing to do, because we display the step1 by default
    }
    else if($step2HasError) {
        //We have to display the step 2 to show in priority the invalidates fields
        showStep($step2);
        hideStep($step1);
    }
}

function updateStepName($nthChild)
{
    var stepNamesLst = $('ol.step-names');

    var oldStep = stepNamesLst.find('li.active');
    oldStep.removeClass();
    oldStep.addClass('hidden-xs');

    var currentStep = stepNamesLst.find('li:nth-child('+$nthChild+')');
    currentStep.addClass('active');
    currentStep.removeClass('hidden-xs');
}

function sumPriceHt()
{
    var $ticketsContainer   = $('div#eo_eticketbundle_booking_tickets');
    var $inputsRate = $ticketsContainer.find('input[id*=value]');
    var $sum = 0;

    $inputsRate.each(function() {
        $sum += Number($(this).val());
    });

    return $sum;
}

function computeInvoice()
{
    var $taxRate         = getTVA();
    var $invoiceFreeTaxe = sumPriceHt();
    var $saleTax         = Number($taxRate.value) * $invoiceFreeTaxe;
    var $invoiceTotal     = $invoiceFreeTaxe + $saleTax;

    $('#invoiceFT').text($invoiceFreeTaxe.toFixed(2) + ' €');
    $('#saleTax').text($saleTax.toFixed(2) + ' €');
    $('#invoiceTotal').text($invoiceTotal.toFixed(2) + ' €');
}


/*****************************************************************************/
/*                     Bootstrap Datepicker interactions                     */
/*****************************************************************************/
var $dpicker = $('input.date-picker');

$dpicker.datetimepicker({
    locale: 'fr',
    format: 'YYYY-MM-DD',
    inline: true,
    minDate: new Date(),
    useCurrent: false,
    daysOfWeekDisabled: [0, 2, 6]
});

$dpicker.on('dp.change', function(e){
    $('input.date-picker').val(e.date.format('YYYY-MM-DD'));

    getNumberPlaceForDate(e.date.format('YYYY-MM-DD'));

    displayDateInfo();
});

function hasDateVisitor() {
    var $dateSize = $('input.date-picker').val().length;
    var $notEmpty = ($dateSize > 0);
    if(!$notEmpty) {
        $('.booking-date-info').text('Veuillez choisir une date de réservation');
        displayDateInfo();
    }
    else {
        if($('.dateinfo').is(':visible')) {
            hideDateInfo();
        }
    }

    return $notEmpty;
}


/*****************************************************************************/
/*                     Booking type buttons interaction                     */
/*****************************************************************************/
$('.btn-toggle').on('click', function() {
    set_active_class($(this));
});

$('.booking-choice-btn').on('click', function (){
    var $inputElement = $(this).parent().find('input');
    if($inputElement !== null) {
        var $choice = $(this).val();
        $inputElement.val($choice);
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
var $rates = [];
$(document).ready( function(){
    var $step1 = $('.step-booking');
    var $step2 = $('.step-tickets');
    var $step3 = $('.step-payment');
    var $step4 = $('.step-confirmation');

    if($step1.length >0) {
        initRates();

        initialize($step1, $step2);

        showInvalidStep($step1, $step2);
    }

    if($step3.length > 0) {
        updateStepName(3);
    }

    if($step4.length > 0) {
        updateStepName(4);
    }

});


