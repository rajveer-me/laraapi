
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
    
    // signup function
    public function signup(Request $req){
        //validate the user data
        $validateUser = Validator::make(
            $req->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);

        //if the validation fails then gives error
        if($validateUser->fails()){
            $errormessage = $validateUser->errors()->all();
            return $this->sendError('Validation Error',$errormessage,401);
        }

        //if the authentication pass then store the data
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => $req->password,
        ]);

        //if the data is inserted successfully
        return $this->sendResponse($user,'User Created successfully');

    }
    //login function 
    public function login(Request $req){
        $validateUser = Validator::make(
            $req->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]);

        //if the authentication fails
        if($validateUser->fails()){
            $errormessage = $validateUser->errors()->all();
            return $this->sendError('User Authentication Fails',$errormessage,404);
        }

        $authenticatedUser = [
            'email'=>$req->email,
            'password' => $req->password
        ];
        //if the authentication pass 
        if(Auth::attempt($authenticatedUser)){
            $authUser = Auth::user();
                return response()->json([
                    'status' =>true,
                    'message' => 'User Logged successfully',
                    'token' => $authUser->createToken("API Token")->plainTextToken,
                    'token_type' => 'bearer',
                ],200);
                
            }else{

                $errormessage = '';
                return $this->sendError('Email, Password wrong',$errormessage,401);                
            }
    }
    //logout function
    public function logout(Request $req){
        $loggedUser = $req->user();
        $loggedUser->currentAccessToken()->delete();

        //loggout successful
        return $this->sendResponse($loggedUser,'Logged out successfully');
    }

6. make one base controller for sendResponse and error message. which we use in auth and post controller.

7. make one posts migration file, model and postcontroller in api folder with resource (make:controller Api/PostController --api). [postcontroller](https://github.com/rajveer-me/laraapi/blob/master/app/Http/Controllers/Api/PostController.php)
8. in post model add (fillable ['title','description','image']).
9. in the post controller make all function code and in the api.php make route.
10. to list route use(route:list).
11. now you can test on postman.