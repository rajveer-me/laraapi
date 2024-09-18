<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add post</title>
</head>
<body>
    <form id="addform">
        <input type="text" id="title" placeholder="title"/>
        <textarea id="description" placeholder="description"></textarea>
        <input type="file" id="image"/>
        <input type="submit" id="addPost">
    </form>
<script>
    var addform = document.querySelector("#addform");

    addform.onsubmit = async (e)=>{
        e.preventDefault();

        const token = localStorage.getItem('api_token');
        const title = document.querySelector("#title").value;
        const description = document.querySelector('#description').value;
        const image = document.querySelector("#image").files[0];

        var formData = new FormData(); //cls of javascript
        formData.append('title',title);
        formData.append('description',description);
        formData.append('image',image);
        console.log(formData);

        let response = await fetch('/api/posts',
        {
            method:'POST',
            body : formData,
            headers:{
                'Authorization' : `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.message);
            window.location.href = "/user/allposts";
        });
    }
</script>
</body>
</html>