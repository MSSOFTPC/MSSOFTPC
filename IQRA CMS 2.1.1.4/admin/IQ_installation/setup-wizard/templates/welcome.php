<div class="container mt-4 align-center">
<div class="card-col-9">
    <div class="wizard-image m-auto text-center">
            <img src="<?php echo IQ_data('logo');?>" width="150px" >
    </div>
<div class="card notification-card border-0 shadow IQ_admin_card" id="IQ_dashbaord_welcome">
    <!-- body -->
        <div class="card-body m-4">

       <p style="font-weight: bold"> Welcome to IQRA CMS. Before getting started, you will need to know the following items.</p style="font-weight: bold">

       <ul>
            <li>Database username</li>
            <li>Database password</li>
            <li>Database host</li>
            <li>Database name</li>

       </ul>


        <p>This information is being used to create a config.php file. <span style="font-weight: bold">If for any reason this automatic file creation does not work, do not worry. All this does is fill in the database information to a configuration file. Simply Go to our website <a href="https://iqra.mssoftpc.com" style="color: blue">IQ CMS</a> and download or copy config file and create manually on IQRA CMS.</span> </p>
        <p> In all likelihood, these items were supplied to you by your web host. If you do not have this information, then you will need to contact them before you can continue. If you are ready…</p>

        <a class="btn btn-primary mt-3" href="<?php echo IQ_base_url().'admin?step=1' ?>">Let's Go</a>
        </div>
 

        <!-- end body -->



    </div>
    
    </div>

    </div>