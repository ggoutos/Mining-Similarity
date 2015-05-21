    $(function(){
        $('form').submit(function(){
            
            var postData = $(this).serialize();
            $.ajax({
                type: 'POST',
                data: postData,
                //change the url for your project
                url: 'http://ggoutos.ddns.net:5000/fbconnect/friends.php',
                success: function(data){
                    console.log(data);
                    //alert('post.js suceess');
                },
                error: function(){
                    console.log(data);
                    alert('There was an error adding your comment');
                }
            });
            
            return false;
        });
    });
