$(document).ready(function() {
    $('#adminedit').css('cursor', 'pointer');
    $("#adminedit").click(function() {
        $('#adminedit').unbind();
        $('#adminedit').children('a').html('Stop Editing');
        $("#adminedit").click(function() {
              window.location = "http://cyberdiscovery.latech.edu/cyberscience/index.php";
        });
        $('.editcell').css('background-color','#008080');
        $('.editcell').hover(
              function () {
                $(this).css('background-color','#48D1CC').css('cursor', 'pointer');
              }, 
              function () {
                $(this).css('background-color','#008080').css('cursor', 'auto');
              }
        );
        $('.editcell').click(function() {
            var edithead = $(this).attr("name");
            var oldhead = $(this).children('span').text();
            $('.editcell').unbind();
            $(this).children('span').html('<textarea rows="1" cols="23" name="edithead" id="edithead">'+ oldhead +'</textarea><input type="submit" name="edittimesubmit" value="Submit" />').unbind();      
            $(this).children('span').children('textarea').focus();

            $('input[type="submit"]').click(function() {
                var editheadcontent = $('#edithead').val();

                $.ajax({
                   type: "POST",
                   url: "headeruploader.php",
                   data: "edithead=" + edithead + "&editheadcontent=" + editheadcontent + "",
                   success: function(){
                     window.location = "http://cyberdiscovery.latech.edu/cyberscience/index.php";
                   }
                });
            });     
        });     
    });
});			