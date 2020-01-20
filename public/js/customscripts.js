

//on hover change color of td
$( "#tr-color" ).hover(
    function() {
        $( "#th-color-dark" ).attr( "id", "th-color-darker" );
    }, function() {
        $( "#th-color-darker" ).attr( "id", "th-color-dark" );
    }
);



//daterangepicker initialisation
$('input[name="dates"]').daterangepicker();


//ajax za redirectanje "detaljno" u periodima -> zbog dodavanja datuma u session
$(document).ready(function () {

    $('.goToUserDetails').click(function () {

        var id = $(this).data('id');
        var date = $(this).data('date');

        $.ajax({
            url: "../../change-date-session",
            data:{
                date: date
            }
        }).done(function(data) {
            //redirect
            window.location.href = 'userreport/' + id;
        });
    });
});

//ajax za brisanje "newKey" u Novi Ključići
$(document).ready(function () {

    $('.deleteNewKey').click(function () {

        var id = $(this).data('id');

        $.ajax({
            data:{
                id: id
            }
        }).done(function(data) {
            //redirect
            window.location.href = '/deleteNewKey/' + id;
        });
    });
});


//freeze unfreeze icon stranica Upravljanje
$(document).ready(function(){
    $(".change-btn").click(function(){
        var id = $(this).data('id');
        if($(this).hasClass('enableInput'))
        {
            $(".row" + id + ' .disable').removeAttr('disabled');
        }
        else
        {
            $(".row" + id + ' .disable').attr('disabled', 'disabled');
        }
        $(this).toggleClass('enableInput');
    });
})
