<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Laravel</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
           .row.custom-auction {
                margin-top: 100px;
            }

            h3 {
                width: 100%;
                text-align: center;
                margin-bottom: 30px;
            }
            p.time-left {
                font-size: 20px;
            }

            span#timleft {
                color: red;
                margin-left: 7%;
            }

            .input-group {
                width: 200px;
            }
            span#nameUser {
                font-size: 24px;
                text-transform: capitalize;
                margin-left: 15px;
                font-weight: bold;
            }

            table#tblEntAttributes {
                margin-top: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row custom-auction">
                <div class="col-md-12">
                    <h3>Wellcome to the Auction DEMO  </h3>
                    <p>Username: <span id="nameUser"></span> </p>
                </div>
               <div class="col-md-6">
                  <div class="img-product">
                      <img style="max-width:100%" src="https://store.storeimages.cdn-apple.com/4668/as-images.apple.com/is/macbook-pro-13-og-202011?wid=1200&hei=630&fmt=jpeg&qlt=95&.v=1604347427000" alt="">
                  </div>
               </div>
               <div class="col-md-6">
                    <div class="action">
                        <p class="time-left"> <strong>Time left:</strong> <span id="timleft">15</span>s</p> 
                        <div class="input-group">
                            <input class="form-control" value="100"  type="number" id="getVaule" min="25" step="100" name=""  max="100000" aria-label="Recipient's " aria-describedby="my-addon">
                            <div class="input-group-append">
                                <button class="input-group-text" id="my-addon">Acutions</button>
                            </div>
                        </div>
                        
                    </div>
                    <div class="result">
                        <table class="table table-striped " id="tblEntAttributes">
                            <tbody>
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Time</th>
                                </tr>   
                            </tbody>
                        </table>
                    </div>
               </div>
            </div>
        </div>
        <p>{{$a=5}}</p>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
        <script src="https://cdn.socket.io/4.1.2/socket.io.min.js" integrity="sha384-toS6mmwu70G0fw54EGlWWeA4z3dyJ+dlXBtSURSKN4vyRFOcxd3Bzjj/AoOwY+Rg" crossorigin="anonymous"></script>
        <script>
          $(document).ready(function () {
              console.log({{$a}});
              function gettime()
              {
                var dt = new Date();
                var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds() + ":" + dt.getMilliseconds();   
                return time
              }
              const ip_address = '127.0.0.1';
              const socket_port = '3000';
              const socket = io(ip_address + ":" + socket_port);
              const urlHttp = 'http://127.0.0.1:8000/auction';
              let step=0;
              let countdown = 15;
              const chatInput = $("#chatInput");
              const name = prompt("Để lại cao danh quí tánh để đấu giá!");
              $("#nameUser").append(name);
             function callAjaxFromLaravel(url,content,method){
                console.log("Call complete ajax!");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: method,
                    url: url,
                    data: {data:content},
                    success: function (response) {
                        console.log("Response from controller ");
                        console.log(response);
                    }
                });
             }

             $("#my-addon").click(function (e) { 
                // $("#getVaule").val();
                step+=1;
                let mesages = parseInt($("#getVaule").val());
                let increase = mesages + mesages*0.35;
                console.log(increase);
                const time = gettime();
                let content = {
                    'step':step,
                    'name':name,
                    'price': mesages,
                    'time': time
                }
                socket.emit("sendChatToServer", content);
                $("#getVaule").val(0);
                $("#getVaule").val(increase);
              
             });

             socket.on("sendChatToClient",(mesages)=>{
                callAjaxFromLaravel(urlHttp,mesages,"post");
               
                let table_content=`
                            <tr>
                                <td>${mesages.step}</td>
                                <td>${mesages.name}</td>
                                <td><strong>${mesages.price}</strong></td>
                                <td style="color:green">${mesages.time}</td>
                            </tr>
                `;
                $("#tblEntAttributes tbody").append(table_content);
             })
          });
        </script>
    </body>
</html>
