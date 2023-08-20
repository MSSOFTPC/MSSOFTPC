<?php include('include.php');
head('IQ Login');
loginonly('logout');


class login{

   private $head_title;

   
    function __construct(){
        head($this->head_title);
        echo '<main class="">';
        echo '<section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">';
        echo '<div class="container">';


    }

    function login(){
        $logincheck = 1;

        // redirect check
        if(isset($_GET['redirect']) && !empty($_GET['redirect'])){ $redirect = '?redirect='.$_GET['redirect'];
        }else{ $redirect = '';  }

        if(isset($_GET['action']) && array_search($_GET['action'],array('reset','forget')) != ''){ $logincheck = 0; }
           
        if($logincheck == 1){
        echo '<div id="login_section">';
        echo '<p class="text-center">
            <a href="'.site_url().'" id="" class="d-flex align-items-center justify-content-center">
                <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                Back to homepage
            </a>
        </p>
      
        <div class="row justify-content-center form-bg-image" data-background-lg="'.adminassets("illustrations/signin.svg","img").'">
            <div class="col-12 d-flex align-items-center justify-content-center">
                <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">';
                IQ_add_admin_notice('login');
         
                echo  '  <div class="text-center text-md-center mb-4 mt-md-0">
                        <h1 class="mb-0 h3">Welcome to '.site_options("return_title").'</h1>
                    </div>
                    <form action="function.php'.$redirect.'" class="mt-4" id="LoginFroms" method="post">
                        <!-- Form -->
                        <div class="form-group mb-4">
                            <label for="email">Your Email</label>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">
                                    <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                                </span>
                                <input type="email" class="form-control" placeholder="example@company.com" id="email" name="email" autofocus required>
                            </div>  
                        </div>
                        <!-- End of Form -->
                        <div class="form-group">
                            <!-- Form -->
                            <div class="form-group mb-4">
                                <label for="password">Your Password</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon2">
                                        <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <input type="password" placeholder="Password" class="form-control" name="password" id="password" required>
                                </div>  
                            </div>
                            <!-- End of Form -->
                            <div class="d-flex justify-content-between align-items-top mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="remember">
                                    <label class="form-check-label mb-0" for="remember">
                                      Remember me
                                    </label>
                                </div>
                                <div><a href="'.site_url('admin','login.php?action=forget').'" class="small text-right" id="forget_btn">Lost password?</a></div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-gray-800" name="LoginSubmit">Sign in</button>
                        </div>
                    </form>
                    <!-- <div class="mt-3 mb-4 text-center">
                        <span class="fw-normal">or login with</span>
                    </div> -->
                    <!-- <div class="d-flex justify-content-center my-4">
                        <a href="#" class="btn btn-icon-only btn-pill btn-outline-gray-500 me-2" aria-label="facebook button" title="facebook button">
                            <svg class="icon icon-xxs" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook-f" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path></svg>
                        </a>
                        <a href="#" class="btn btn-icon-only btn-pill btn-outline-gray-500 me-2" aria-label="twitter button" title="twitter button">
                            <svg class="icon icon-xxs" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="twitter" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"></path></svg>
                        </a>
                        <a href="#" class="btn btn-icon-only btn-pill btn-outline-gray-500" aria-label="github button" title="github button">
                            <svg class="icon icon-xxs" aria-hidden="true" focusable="false" data-prefix="fab" data-icon="github" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"><path fill="currentColor" d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"></path></svg>
                        </a>
                    </div> -->
                    <!-- <div class="d-flex justify-content-center align-items-center mt-4">
                        <span class="fw-normal">
                            Not registered?
                            <a href="./sign-up.html" class="fw-bold">Create account</a>
                        </span>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    </div>';
    echo '  </div></div>
    </div>';
    echo '</section>';
    echo '</main>';
}
    }

    function registration(){
            
    }

    function reset(){
        if(isset($_GET['action']) && $_GET['action'] == 'reset'){ 
          
            
        if(isset($_GET['forgettoken']) && isset($_GET['email'])){
               
            $fetch = fetch('user', 'email="'.$_GET['email'].'" and forgettoken="'.$_GET['forgettoken'].'" and status="1"');
            if(!empty($fetch['email'])){
               $tokenexploid = explode('_',$fetch['forgettoken'] );
               $tokenget = explode('_',$_GET['forgettoken'] );
                if($tokenget[1] != $tokenexploid[1]){
                    $_SESSION['error_login_notice'] = 'Reset Token Expired!';
                    back('login.php');
                    die();
                }
            }else{
                $_SESSION['error_login_notice'] = 'Email Not Found';
                back('login.php');
                die();
            }

            echo '<div id="forgettoken_section">';
            echo '<div class="row justify-content-center form-bg-image">';
            echo '<p class="text-center"><a href="login.php" class="d-flex align-items-center justify-content-center">
            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
            Back to log in
            </a>
            </p>';
            echo '<div class="col-12 d-flex align-items-center justify-content-center">
            <div class="bg-white shadow border-0 rounded p-4 p-lg-5 w-100 fmxw-500">';
            echo '<h1 class="h3 mb-4">Reset password</h1>';
            IQ_add_admin_notice('login');
            echo '<form action="'.site_url("admin", "function.php").'" method="post">
            <!-- Form -->
            <div class="mb-4">
                <label for="email" >Your Email</label>
                <div class="input-group">
                    <input type="email" class="form-control" id="email" value="'.$_GET["email"].'" disabled="">
                    <input type="hidden" name="email" value="'.$_GET["email"].'">
                <kpm-button style="position: relative !important; z-index: 3 !important;" class="_1mUok1yqu_fHkbNOXVoU6l kpm_input-field-button kpm_gray-key-icon"><div class="_12GLdmNfqjBzgAQBymfRrk" style="position: absolute !important; width: 20px !important; height: 24px !important; top: 7.5px !important; left: -26.5104px !important;"><svg class="_1jZ1QRhwRIXZDO2Rgp_qon" viewBox="25 25 50 50" xmlnsXlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" style="inset: 0px -2px !important;"><circle cx="50" cy="50" r="20"></circle></svg></div><svg width="12" height="24" viewBox="0 0 8 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlnsXlink="http://www.w3.org/1999/xlink" class="_2WQbmZpFI_2_k72D9o87Hq kpm_reset _2p2h7gYS-bpObPoQxR7lvo" style="position: absolute !important; width: 12px !important; height: 24px !important; top: 7.5px !important; left: -26.5104px !important;"><path fillRule="evenodd" clipRule="evenodd" d="M6 7.46487C7.1956 6.77325 8 5.48056 8 4C8 1.79086 6.20914 0 4 0C1.79086 0 0 1.79086 0 4C0 5.48056 0.804397 6.77325 2 7.46487V9H3V10H2V12H3V13H2V14.5L4 16L6 14.5V7.46487ZM5 3C5 3.55228 4.55229 4 4 4C3.44772 4 3 3.55228 3 3C3 2.44772 3.44772 2 4 2C4.55229 2 5 2.44772 5 3ZM4 8H5V14H4V8Z"></path></svg></kpm-button></div>  
            </div>
            <!-- End of Form -->
            <!-- Form -->
            <div class="form-group mb-4">
                <label for="password">Your Password</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon2">
                        <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                    </span>
                    <input type="password" name="password" placeholder="Password" class="form-control" id="password" required="">
                <kpm-button style="position: relative !important; z-index: 3 !important;" class="_1mUok1yqu_fHkbNOXVoU6l kpm_input-field-button kpm_gray-key-icon"><div class="_12GLdmNfqjBzgAQBymfRrk" style="position: absolute !important; width: 20px !important; height: 24px !important; top: 7.5px !important; left: -26.5104px !important;"><svg class="_1jZ1QRhwRIXZDO2Rgp_qon" viewBox="25 25 50 50" xmlnsXlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" style="inset: 0px -2px !important;"><circle cx="50" cy="50" r="20"></circle></svg></div><svg width="12" height="24" viewBox="0 0 8 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlnsXlink="http://www.w3.org/1999/xlink" class="_2WQbmZpFI_2_k72D9o87Hq kpm_reset _2p2h7gYS-bpObPoQxR7lvo" style="position: absolute !important; width: 12px !important; height: 24px !important; top: 7.5px !important; left: -26.5104px !important;"><path fillRule="evenodd" clipRule="evenodd" d="M6 7.46487C7.1956 6.77325 8 5.48056 8 4C8 1.79086 6.20914 0 4 0C1.79086 0 0 1.79086 0 4C0 5.48056 0.804397 6.77325 2 7.46487V9H3V10H2V12H3V13H2V14.5L4 16L6 14.5V7.46487ZM5 3C5 3.55228 4.55229 4 4 4C3.44772 4 3 3.55228 3 3C3 2.44772 3.44772 2 4 2C4.55229 2 5 2.44772 5 3ZM4 8H5V14H4V8Z"></path></svg></kpm-button></div>  
            </div>
            <!-- End of Form -->
            <!-- Form -->
            <div class="form-group mb-4">
                <label for="confirm_password">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text" id="basic-addon2">
                        <svg class="icon icon-xs text-gray-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                    </span>
                    <input type="password" name="password2" placeholder="Confirm Password" class="form-control" id="confirm_password" required="">
                </div>  
            </div>
            <!-- End of Form -->
            <div class="d-grid">
                <button type="submit" name="resettokenSubmit" class="btn btn-gray-800">Reset password</button>
            </div>
        </form>
            </div>
        </div>
        </div>';
        echo '  </div></div>
        </div>';
        echo '</section>';
        echo '</main>';


        }else{
            // back('login.php');
        }


            
    }
}

    function forget(){
        if(isset($_GET['action']) && $_GET['action'] == 'forget'){
        echo '<div id="forget_section">';
        echo '<div class="row justify-content-center form-bg-image">
                <p class="text-center"><a href="login.php"  id="login_btn" class="d-flex align-items-center justify-content-center">
                    <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                    Back to log in
                    </a>
                </p>
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="signin-inner my-3 my-lg-0 bg-white shadow border-0 rounded p-4 p-lg-5 w-100 fmxw-500">';
                    IQ_add_admin_notice('login');
                    echo '    <h1 class="h3">Forgot your password?</h1>
                        <p class="mb-4">Don"t fret! Just type in your email and we will send you a code to reset your password!</p>
                        <form action="'.site_url("admin", "function.php").'" method="post">
                            <!-- Form -->
                            <div class="mb-4">
                                <label for="email">Your Email</label>
                                <div class="input-group">';

                                if(isset($_POST["email"])){ $emailforget = $_POST["email"];}else{$emailforget = '';}
        echo                  '<input type="email" name="email" value="'.$emailforget.'" class="form-control" id="email" placeholder="example@company.com" required="" autofocus="">
                                </div>  
                            </div>
                            <!-- End of Form -->
                            <div class="d-grid">
                                <button type="submit" name="ForgetSubmit" class="btn btn-gray-800">Recover password</button>
                            </div>
                        </form>
                    
              
    </div>';
    echo '  </div></div>
    </div>';
    echo '</section>';
    echo '</main>';
        }
    }

    function __destruct(){
      
    
      
            
    }

}


// execute class
$login = new login();
$login->login();
$login->registration();
$login->reset();
$login->forget();

?>        



 <?php footer() ?>

