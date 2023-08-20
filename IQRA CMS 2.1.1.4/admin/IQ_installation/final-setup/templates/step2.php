<div class="container mt-4 align-center">
<div class="card-col-9">
    <div class="wizard-image m-auto text-center">
            <img src="<?php echo IQ_data('logo');?>" width="150px" >
    </div>
<div class="card notification-card border-0 shadow IQ_admin_card" id="IQ_dashbaord_welcome">
    <!-- body -->
        <div class="card-body m-4">

            
              
            <form action="" method="post">
            <div class="row">

                       
                <div class="form-group mt-3">
                    <label for="site-name">Site Name</label>
                    <input type="text" name="site-name" class="form-control" id="site-name" required>
                </div>

                <div class="form-group mt-3">
                    <label for="username">Username</label>
                    <input type="text" name="username" class="form-control" id="username" required>
                </div>

                <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input type="text" name="password" class="form-control" id="password" required>
                </div>

                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control" id="email" type="email" required>
                </div>

                <div class="form-group mt-5">
                    <input type="submit" name="IQ_final_setup" class="btn btn-primary" id="final-submit" value="Start Migration Database">
                </div>


                </div>

</form>

            


        </div>
 

        <!-- end body -->



    </div>
    
    </div>

    </div>