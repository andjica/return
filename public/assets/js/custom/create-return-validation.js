    
//by andjica
$(".sha").on('click', function(){
    let shablon = $('input[name="shablon"]:checked').val();
    
    if(shablon == "admin-r")
    {
        if(document.getElementById("re1").style.display="none")
        {
            document.getElementById("re1").style.display="";
            document.getElementById("re2").style.display="none";
        }
    }
    else
    {
        document.getElementById("re1").style.display="none";
        document.getElementById("re2").style.display="";
        
    }
});

//setup before functions
    var typingTimer;                //timer identifier
    var doneTypingInterval = 1500;  //time in ms, 5 second for example

    //on keyup, start the countdown
    $('#orderId').on('keyup', function () {
        
        clearTimeout(typingTimer);
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });

    //on keydown, clear the countdown 
    $('#orderId').on('keydown', function () {
        clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    function doneTyping () {
       let orderId = $('#orderId').val();
       var token =  $('input[name="token"]').attr('value');
       
       $.ajax({
        type: "POST",
        url: "/return/create",
        data: {
            orderId : orderId,
            token : token
        },
        success: function (data) {
          
           if(data.products == null)
           {
               
              document.getElementById('er-order').innerHTML="There is no order with id "+orderId;
               
           }
           else
           {

               document.getElementById('er-order').innerHTML="";
               document.getElementById('items').style.display="";
               
               var text =``;
                $.each(data.products, function (index, value) {
                    text+= `<option value="${value['id']}">${value['title']}</option>`;
                    
                })
              
                document.getElementById('products').innerHTML=text;
           }
               
           
        },
        error: function (errormessage) {
            //do something else
        }
    });
    }

    $('#custom-return').on('submit', function(e){
    
       
        let orderId = $('#orderId').val();
        let quantity = $('#quantity').val();
        let street = $('#street').val();
        let postcode = $('#postcode').val();

        let mistakes = [];
        if(orderId == "")
        {
            mistakes.push = "Order id is empty field";
            document.getElementById('er-order').innerHTML="Order id is required field";
            e.preventDefault();
        }

        if(quantity == "")
        {
            mistakes.push = "Quantity is empty field";
            document.getElementById('er-quantity').innerHTML="Enter quantity thath you returning";
            e.preventDefault();
        }

        if(street == "")
        {
            mistakes.push = "Street is empty field";
            document.getElementById('er-street').innerHTML="Street is required field";
            e.preventDefault();
        }

        if(postcode == "")
        {
            mistakes.push = "Postcode is empty field";
            document.getElementById('er-postcode').innerHTML="Postcode is required field";
            e.preventDefault();
        }


        let shablon = $('input[name="shablon"]:checked').val();
        
        //if admin choose to write reasons :)
        if(shablon == "user-r")
        {
            let reason = $('#reason').val();
            let photos = $('#uploadmedia').val();
            //check textarea
            if(reason == "")
            {
                mistakes.push = "You must enter reason for returning item";
                document.getElementById('er-reason').innerHTML="You must enter reason for returning item";
                e.preventDefault();
            }

            $("#reason").on('keyup' , function(){
    
            var reason = $("#reason").val();
        
            if(reason == "")
            {
                $("#er-reason").text("Reason is required");
            }
            else {
                $("#er-reason").text("");
            }
            });

            //check input type file for photos
            if(document.getElementById('uploadmedia').files.length == 0)
            {
                document.getElementById('er-photos').innerHTML="You must send photos of product for return";
                e.preventDefault();
            }
            else if(document.getElementById('uploadmedia').files.length >= 1)
            {   
                if (!document.getElementById('uploadmedia').files[0].name.match(/.(jpg|jpeg|png)$/i))
                    {
                        document.getElementById('er-photos').innerHTML="Image  extenstions must  are jpg, jpeg, png";
                        e.preventDefault();
                    }
            }

             //video validations
            if(document.getElementById('uploadvideos').files.length >= 1)
            {
               if (!document.getElementById('uploadvideos').files[0].name.match(/.(webm|mkv|flv|mp4)$/i))
                    {
                        document.getElementById('er-videos').innerHTML="Video extenstions must be  webm, mkv, flv, mp4";
                        e.preventDefault();
                    }
            }

            //onchange
            $("#uploadmedia").on('change' , function(){
    
            var photos = $("#uploadmedia").val();
        
            if(photos == "")
            {
                $("#er-photos").text("Photos is required");
            }
            else {
                $("#er-photos").text("");
            }
            });
        }
        //zsavrsi ovde proveri sva polja

        if(mistakes.length > 0)
        {
            return false;
        }
        
    })


    $("#orderId").on('keyup' , function(){
    
        var orderId = $("#orderId").val();
    
        if(orderId == "")
        {
        $("#er-order").text("Order id is required");
        }
        else {
            $("#er-order").text("");
        }
    });

    $("#quantity").on('keyup' , function(){
    
        var quantity = $("#quantity").val();
    
        if(quantity == "")
        {
        $("#er-quantity").text("Quantity is required");
        }
        else {
            $("#er-quantity").text("");
        }
    });

    $("#street").on('keyup' , function(){
    
        var street = $("#street").val();
    
        if(street == "")
        {
        $("#er-street").text("Street is required");
        }
        else {
            $("#er-street").text("");
        }
    });

    $("#postcode").on('keyup' , function(){
    
        var postcode = $("#postcode").val();
    
        if(postcode == "")
        {
        $("#er-postcode").text("Postcode is required");
        }
        else {
            $("#er-postcode").text("");
        }
    });