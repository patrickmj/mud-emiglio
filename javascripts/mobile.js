(function($) {
    $(document).ready(function() {
       $('#locate').click(function(event) {
           navigator.geolocation.getCurrentPosition(function(position) {
               $('#lat').val(position.coords.latitude);
               $('#lng').val(position.coords.longitude);
               $('#mobile-located').val(1);
               $('#located').show();
           });
       }); 
    });
})(jQuery);



