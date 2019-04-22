$('document').ready(function() {
    var token = 'REAL_API_TOKEN';
    var url = "http://localhost:8000/api/messages";
    // xhr.setRequestHeader("X-AUTH-TOKEN", "REAL_API_TOKEN");

    if ($('tbody').length) {
      $.ajax({ 
        url: url,
        method: "GET",               
        success: function(data){    
          var messages = data['hydra:member'];
          for (var i = 0; i < messages.length; i++) {
                var tr  = "<tr><td>"+messages[i]['id']+"</td>"
                        + "<td>"+messages[i]['title']+"</td>"
                        + "<td>"+messages[i]['description']+"</td>"
                        + "<td>"+messages[i]['content']+"</td>"
                        + "<td>"+messages[i]['status']+"</td>"
                        + "<td>"+messages[i]['email']+"</td>"
                        + "<td><button class=\"approve-btn\" onclick=\"return approve();\" id="+messages[i]['id']+">Approve</button></td></tr>"
                ;                  
                $('tbody').append(tr);                    
          }         
        },
        error:function(error){
            console.log("Error");
        }
      });       
    }  

    $('#submit-btn').click(function(){
        var message = {
            'title': $('#title').val().trim(),
            'description': $('#description').val().trim(),
            'content': $('#content').val().trim(),
            'email': $('#email').val().trim()
        }

        $.ajax({ 
          url: "http://localhost:8000/api/messages",
          method: "POST",   
          processData: false,   
          async: true,
          crossDomain: true,   
          data: JSON.stringify(message), 
          headers: {
            "Content-Type": "application/json",
          },                       
          success: function(data){    
            alert("success!");
          },          
          error: function(error){
            alert("Something went wrong!");
            console.log("Error");
          }
       });          
    });
});

function approve(evt) {
    var id = $(event.target).attr('id');
    var url = "http://localhost:8000/api/messages/approve/" + id;
    $.ajax({ 
      url: url,
      method: "POST",   
      processData: false,   
      async: true,
      crossDomain: true,       
      headers: {
        "Content-Type": "application/json",
      },                       
      success: function(data){    
        alert("success!");
      },          
      error: function(error){
        alert("Something went wrong!");
        console.log("Error");
      }
  });    
}


/*
  $.ajax({
    url: url,
    type: "GET",        
    beforeSend: function (xhr) {
      xhr.setRequestHeader ("X-AUTH-TOKEN", "REAL_API_TOKEN");
    },        
    headers: { 'X-AUTH-TOKEN': 'REAL_API_TOKEN' },       
    success: function(data) { alert('Success!' ); }
  });
*/
