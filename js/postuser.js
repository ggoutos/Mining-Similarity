    $(function(){
        $('form').submit(function(){
            
            var postData = $(this).serialize();
            $.ajax({
                type: 'POST',
                data: postData,
                //change the url for your project
                url: 'http://ggoutos.ddns.net:5000/fbconnect/users.php',
                success: function(data){
                    console.log(data);
                    //alert('Your profile has been registered.');
                },
                error: function(){
                    console.log(data);
                    alert('There was an error adding your comment');
                }
            });
            
            return false;
        });
    });
