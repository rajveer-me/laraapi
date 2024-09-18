<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <a href="/user/addpost" class="">Add new Post</a>
    <button class="" id="logoutButton">logout</button>

    <div id="postsContainer">
    </div>


    <!-- single post modal -->

    <!-- view post Modal -->
    <div class="modal fade" id="singlepost" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ...
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Understood</button>
        </div>
        </div>
    </div>
    </div>

<!-- update post Modal -->
<div class="modal fade" id="updatepost" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updatePostLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="updatePostLabel">Update Post</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateForm">
                <div class="modal-body">
                    <input type="text" id="updateId" placeholder="Id" readonly/>
                    <input type="text" id="updateTitle" placeholder="Title"/>
                    <textarea id="updateDescription" placeholder="Description"></textarea>
                    <input type="file" id="updateImage"/>
                    <img id="showImage" src="" alt="Current Image" width="150px" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" id="updatebutton" class="btn btn-primary" value="Update Post">
                </div>
            </form>
        </div>
    </div>
</div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    
    document.querySelector("#logoutButton").addEventListener('click',function(){
        const token = localStorage.getItem('api_token');
        
        fetch('/api/logout',
        {
            method:'POST',
            headers:{
                'Authorization' : `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            window.location.href = "/user/login";
        });
    });

    function loadData(){
        const token = localStorage.getItem('api_token');
        fetch('/api/posts',
        {
            method:'GET',
            headers:{
                'Authorization' : `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.data.posts);
            var allposts = data.data.posts;
            const postcontainer = document.querySelector("#postsContainer");

            var tableform = `<table>
            <tr>
                <th>id</th>
                <th>title</th>
                <th>description</th>
                <th>image</th>
                <th>view</th>
                <th>update</th>
                <th>delete</th>
            </tr>`;

            //loop for posts
            allposts.forEach(post => {
                tableform += `<tr>
                    <td><p>${post.id}</p></td>
                    <td>${post.title}</td>
                    <td>${post.description}</td>
                    <td><img src ="/uploads/${post.image}" width="150px"/></td>
                    <td>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-post="${post.id}" data-bs-toggle="modal" data-bs-target="#singlepost">
                        View
                        </button>
                    </td>
                    <td><button type="button" class="btn btn-primary" data-bs-post="${post.id}" data-bs-toggle="modal" data-bs-target="#updatepost">Update</button></td>
                    <td><button type="button" onclick="delete_post(${post.id})">Delete</button></td>
                </tr>`;
            });

            tableform += `</table>`;
            //to set the tableform in the postcontainer 
            postcontainer.innerHTML = tableform;
        });

    }
    loadData();


    //open single post modal
    var singlepostmodal = document.querySelector("#singlepost");
    if (singlepostmodal) {
        singlepostmodal.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget
            const postid = button.getAttribute('data-bs-post');

            //empty the modal body
            const modalbody = document.querySelector("#singlepost .modal-body");
                modalbody.innerHTML = "";
            
            const token = localStorage.getItem('api_token');
            fetch(`/api/posts/${postid}`,
            {
                method:'GET',
                headers:{
                    'Authorization' : `Bearer ${token}`,
                    'Content-type' : 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const post = data.data;

                modalbody.innerHTML = ` Title : ${post.title} <br>
                                        Description : ${post.description}<br>
                                        Image : <img src="/uploads/${post.image}" width="150px" />`;
            })


        })
    }


    //update modal 
    var updatePostModal = document.querySelector("#updatepost");
    if (updatePostModal) {
        updatePostModal.addEventListener('show.bs.modal', event => {
            // Button that triggered the modal
            const button = event.relatedTarget
            const postid = button.getAttribute('data-bs-post');

            const token = localStorage.getItem('api_token');
            fetch(`/api/posts/${postid}`,
            {
                method:'GET',
                headers:{
                    'Authorization' : `Bearer ${token}`,
                    
                }
            })
            .then(response => response.json())
            .then(data => {
                const post = data.data;

                document.querySelector("#updateId").value = post.id;
                document.querySelector("#updateTitle").value = post.title;
                document.querySelector("#updateDescription").value = post.description;
                document.querySelector("#showImage").src = `/uploads/${post.image}`;
            });


        })
    }

    //update form data
    var updateform = document.querySelector("#updateForm");

    updateform.onsubmit = async (e)=>{
        e.preventDefault();
        const token = localStorage.getItem('api_token');
        const postid = document.querySelector("#updateId").value;
        const title = document.querySelector("#updateTitle").value;
        const description = document.querySelector('#updateDescription').value;
        
        console.log(token,postid,title,description);

        var formData = new FormData(); //cls of javascript
        //formData.append('id', postid);
        formData.append('title', title);
        formData.append('description', description);

        let image = document.querySelector("#updateImage").files[0]; // Ensure this line is correct
        if (image) {
        formData.append('image', image);
        }
        console.log(image);
        console.log(' befre FormData:', Array.from(formData.entries())); // Log FormData entries

        
        let response = await fetch(`/api/posts/${postid}`,{
            method:'POST',
            body: formData,
            headers:{
                'Authorization' : `Bearer ${token}`,
                'Accept': 'application/json',
                'X-HTTP-Method-Override' : 'PUT',
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.message);
            window.location.href = "/user/allposts";
        });


    }

    //delete post
    async function delete_post(postid){
        const token = localStorage.getItem('api_token');
        let response = await fetch(`/api/posts/${postid}`,
            {
                method:'DELETE',
                headers:{
                    'Authorization' : `Bearer ${token}`,
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                window.location.href = "/user/allposts";
            })
    }

</script>
</body>

</html>