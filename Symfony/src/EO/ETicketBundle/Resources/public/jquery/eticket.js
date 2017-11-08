/**
 * Created by Eve ODIN on 24/10/2017.
 */

/*****************************************************************************/
/*                     Functions definitions                                 */
/*****************************************************************************/
function getNumberPlaceForDate(inputDate)
{
    var data = {date: inputDate};
    $.post("availableDate", data)
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

