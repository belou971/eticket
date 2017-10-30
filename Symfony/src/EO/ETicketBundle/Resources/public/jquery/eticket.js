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
