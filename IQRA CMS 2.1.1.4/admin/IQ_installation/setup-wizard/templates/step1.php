<div class="container mt-4 align-center">
<div class="card-col-9">
    <div class="wizard-image m-auto text-center">
            <img src="<?php echo IQ_data('logo');?>" width="150px" >
    </div>
<div class="card notification-card border-0 shadow IQ_admin_card" id="IQ_dashbaord_welcome">
    <!-- body -->
        <div class="card-body m-4">

            <p>Below you should enter your database connection details. If you are not sure about these, contact your host.</p>
            
            <div class="row">
                <?php 
                ?>
                <form action="" method="post">

                       
                            <div class="form-group mt-3">
                                <label for="database-name">Database Name</label>
                                <input type="text" name="database-name" class="form-control" id="database-name" required>
                            </div>

                            <div class="form-group mt-3">
                                <label for="database-username">Database Username</label>
                                <input type="text" name="database-username" class="form-control" id="database-username" required>
                            </div>

                            <div class="form-group mt-3">
                                <label for="password">Password</label>
                                <input type="text" name="password" class="form-control" id="password">
                            </div>

                            <div class="form-group mt-3">
                                <label for="database-host">Database Host</label>
                                <input type="text" name="database-host" class="form-control" id="database-host" value="localhost" required>
                            </div>

                            <div class="form-group mt-5">
                                <input type="submit" name="IQ_database_submit" class="btn btn-primary" id="database-submit" value="Connect Database">
                            </div>
                           
                        

                </form>


            </div>

        </div>
 

        <!-- end body -->



    </div>
    
    </div>

    </div>