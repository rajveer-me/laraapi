<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
<div class="container">
        <h2>Login</h2>

            <div class="error" id="error-message"></div>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" id="loginButton" name="loginSubmit">Login</button>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('#loginButton').on('click',function(){
            const lemail = $("#email").val();
            const lpassword = $("#password").val();

            $.ajax({
                url : '/api/login',
                type : 'POST',
                contentType : 'application/json',
                data : JSON.stringify({
                    email : lemail,
                    password : lpassword,
                }),
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token
                },
                success : function(response){
                    console.log(response);
                    //save the token to localstorage
                    localStorage.setItem('api_token',response.token);
                    //redirec to all posts
                    window.location.href = "/user/allposts";

                },
                error : function(xhr,status,error){
                    console.error('Error:', xhr.responseText);
                }
            });
        });


    });

</script>
    
</body>
</html>