
## About Laravel API

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel
1. install the sanctum api. (php artisan install:api).
2. create a user model add the (use hasApiTokens).
3. add fillable according to you.
4. make a authController in the api folder (make:controller Api/AuthController).
5. make three functions signup,signin,login. [Authcontroller](https://github.com/rajveer-me/laraapi/blob/master/app/Http/Controllers/Api/AuthController.php)
    
6. make one base controller for sendResponse and error message. which we use in auth and post controller. [BaseController](https://github.com/rajveer-me/laraapi/blob/master/app/Http/Controllers/Api/BaseController.php)

7. make one posts migration file, model and postcontroller in api folder with resource (make:controller Api/PostController --api). [postcontroller](https://github.com/rajveer-me/laraapi/blob/master/app/Http/Controllers/Api/PostController.php)

8. in post model add (fillable ['title','description','image']).
9. in the post controller make all function code and in the api.php make route.
    [web](https://github.com/rajveer-me/laraapi/blob/master/routes/web.php)
    [Api](https://github.com/rajveer-me/laraapi/blob/master/routes/api.php)
10. to list route use(route:list).
11. now you can test on postman.
12. make the blade template for login, allpost (post display modal and post delete modal), addpost. 
    [Login](https://github.com/rajveer-me/laraapi/blob/master/resources/views/login.blade.php)
    [Allpost](https://github.com/rajveer-me/laraapi/blob/master/resources/views/allposts.blade.php)
    [Addpost](https://github.com/rajveer-me/laraapi/blob/master/resources/views/addpost.blade.php)
