 <?php
  include 'connection.php';
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">

  <div>
    <form method="post" action="getdata.php" id="myForm">
    <p><input type="text" name="name" required="required" placeholder="Name"></p>
    <p><input type="text" name="age" required="required" placeholder="Age"></p>
    <p><input type="email" name="email" required="required" placeholder="E-mail"></p>
    <p><input type="submit" name="submit" value="SUBMIT" class="btn btn-primary"></p>
  </form>

  <span id="result">
    <!---successfully inserted reord or error occured-->
  </span>

  </div>



  <table class="table">
    <thead>
      <tr>
        <th>
          <input type="checkbox" id="checkAll">
        </th>
        <th>Name</th>
        <th>Age</th>
        <th>Email</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
          $table  = mysqli_query($conn ,'SELECT * FROM users');
          while($row  = mysqli_fetch_array($table)){ ?>
              <tr id="<?php echo $row['id']; ?>">
                <td><input class="checkbox" type="checkbox" id="<?php echo $row['id'] ?>" name="id[]"></td>
                <td data-target="name"><?php echo $row['name']; ?></td>
                <td data-target="age"><?php echo $row['age']; ?></td>
                <td data-target="email"><?php echo $row['email']; ?></td>
                <td><a href="#" data-role="update" data-id="<?php echo $row['id'] ;?>">Update</a></td>
              </tr>
         <?php }
       ?>
       
    </tbody>
  </table>

  <br/>
  <button type="button" class="btn btn-danger" id="delete">Delete Selected </button>
  
</div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modal Header</h4>
          </div>
          <div class="modal-body">
              <div class="form-group">
                <label>Name</label>
                <input type="text" id="name" class="form-control">
              </div>
              <div class="form-group">
                <label>Age</label>
                <input type="text" id="age" class="form-control">
              </div>

               <div class="form-group">
                <label>Email</label>
                <input type="email" id="email" class="form-control">
              </div>
                <input type="hidden" id="id" class="form-control">


          </div>
          <div class="modal-footer">
            <a href="#" id="save" class="btn btn-primary pull-right">Update</a>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

</body>

<script>
  $(document).ready(function(){

        $('input[type=submit]').click(function(){

          inputValue = $('input').val();

          //prevents from inserting blank records
          if(inputValue){
                //serializing array and storing into local variable
                var data = $('#myForm :input').serializeArray();

                //selecting form and passing data and the returned value is stored in getdata---successfully inserted reord or error occured
                $.post($('#myForm').attr('action'), data, function(getdata){
                  $('#result').html(getdata);
                });
                clearInput();
            }

        });


        $('#myForm').submit(function(){
          return false; //prevents from redirecting to other page
        });


        //clearing input from input fields once user pressed the submit button
        function clearInput() {
          $('#myForm :input').each(function(){
            $(this).val(''); //as submit is also an input, this statement makes its value blank. Therefore blank button appears on frontend

            //setting value of input type submit as 'SUBMIT'
            $('input[type=submit]').val('SUBMIT');
          });
        }

    //  append values in input fields
      $(document).on('click','a[data-role=update]',function(){
            var id  = $(this).data('id');
            var name  = $('#'+id).children('td[data-target=name]').text();
            var age  = $('#'+id).children('td[data-target=age]').text();
            var email  = $('#'+id).children('td[data-target=email]').text();

            $('#name').val(name);
            $('#age').val(age);
            $('#email').val(email);
            $('#id').val(id);
            $('#myModal').modal('toggle');
      });

      // now create event to get data from fields and update in database 

       $('#save').click(function(){
          var id  = $('#id').val(); 
         var name =  $('#name').val();
          var age =  $('#age').val();
          var email =   $('#email').val();

          $.ajax({
              url      : 'connection.php',
              method   : 'post', 
              data     : {name : name , age: age , email : email , id: id},
              success  : function(response){
                            // now update user record in table 
                             $('#'+id).children('td[data-target=name]').text(name);
                             $('#'+id).children('td[data-target=age]').text(age);
                             $('#'+id).children('td[data-target=email]').text(email);
                             $('#myModal').modal('toggle'); 
                         }
          });
       });



//delete
       $('#checkAll').click(function(){
         if(this.checked){
             $('.checkbox').each(function(){
                this.checked = true;
             });   
         }else{
            $('.checkbox').each(function(){
                this.checked = false;
             });
         } 
      });


    $('#delete').click(function(){
       var dataArr  = new Array();
       if($('input:checkbox:checked').length > 0){
          $('input:checkbox:checked').each(function(){
              dataArr.push($(this).attr('id'));
              $(this).closest('tr').remove();
          });
          sendResponse(dataArr)
       }else{
         alert('No record selected');
       }

    });  


    function sendResponse(dataArr){
        $.ajax({
            type    : 'post',
            url     : 'function.php',
            data    : {'data' : dataArr},
            success : function(response){
                        alert(response);
                      },
            error   : function(errResponse){
                      alert(errResponse);
                      }                     
        });
    }

  });
</script>
</html>
