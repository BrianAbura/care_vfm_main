<?php
/**
 * This is the Routing file : Routes all system requests to the appropriate page
 * The Routing is supported by Bramus Routing | bramus/router is released under the MIT public license.
 * Ref: https://github.com/bramus/router
 * 
 * NOTE: Do not delete this page
 */

    // In case one is using PHP 5.4's built-in server
    $filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
    
    if (php_sapi_name() === 'cli-server' && is_file($filename)) {
        return false;
    }

    require __DIR__ . '/vendor/autoload.php';

    $router = new \Bramus\Router\Router();

    define('BASEPATH','/care_vendors/');
    /** 
     * Custom 404 Handler
     * This can be improved
    */ 
    $router->set404(function () {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        header("Location: ".BASEPATH . 'error_404.php');
    });

    
    $router->get('/', function () {
        require __DIR__ . '/login_page.php';
    });
    
    $router->get('/login_page_redirect', function () { //Incase of Session Termination
        require __DIR__ . '/login_page.php';
    });

    $router->post('/login', function () {
        require __DIR__ . '/acc_login.php';
    });

    $router->get('/home', function () {
        require __DIR__ . '/dashboard.php';
    });

    $router->get('/password-reset', function () {
        require __DIR__ . '/forgot_password.php';
    });

    $router->post('/reset_pass', function () {
        require __DIR__ . '/acc_reset.php';
    });
    
    $router->get('/logout', function () {
        require __DIR__ . '/logout.php';
    });

    $router->get('new_registration', function () {
        require __DIR__ . '/new_registration.php';
    });

    $router->post('new_reg', function () {
        require __DIR__ . '/new_reg.php';
    });

            // Registration
    $router->mount('/register', function () use ($router) {
        $router->get('/', function () {
            require __DIR__ . '/register/';
        });
        //Individuals
        $router->get('individual', function () {
            require __DIR__ . '/register/reg_individual.php';
        });
        $router->post('add_individual', function () { //Add/Edit
            require __DIR__ . '/register/Individual.php';
        });
        $router->get('(\d+)-individual', function ($vendor_id) {
            require __DIR__ . '/register/edit_individual.php';
            htmlspecialchars($vendor_id);
        });

        //Company/Org
        $router->post('add_company', function () { //Add/Edit
            require __DIR__ . '/register/Company.php';
        });
        $router->get('(\d+)-company', function ($vendor_id) {
            require __DIR__ . '/register/edit_company.php';
            htmlspecialchars($vendor_id);
        });

        $router->get('(\d+)-view', function ($vendor_id) {
            require __DIR__ . '/register/view.php';
            htmlspecialchars($vendor_id);
        });
        $router->get('(\d+)', function ($vendor_id) {
            require __DIR__ . '/register/edit.php';
            htmlspecialchars($vendor_id);
        });
    });

        // Profile
        $router->mount('/profile', function () use ($router) {
            $router->get('/', function () {
                require __DIR__ . '/profile/';
            });
            $router->post('edit_profile', function () {
                require __DIR__ . '/profile/Profile.php';
            });
            $router->post('edit_password', function () {
                require __DIR__ . '/profile/Profile.php';
            });
        });
    

        // Tenders
        $router->mount('/tenders', function () use ($router) {
        $router->get('/', function () {
            require __DIR__ . '/tenders/';
        });
        $router->get('shortlisted', function () {
            require __DIR__ . '/tenders/tender_shortlist.php';
        });
        $router->get('submitted', function () {
            require __DIR__ . '/tenders/tenders_submitted.php';
        });
        $router->get('closed', function () {
            require __DIR__ . '/tenders/tenders_closed.php';
        });
        $router->get('(\d+)-closed', function ($tender_id) {
            require __DIR__ . '/tenders/view_closed.php';
            htmlspecialchars($tender_id);
        });
        $router->get('(\d+)-view', function ($tender_id) {
            require __DIR__ . '/tenders/view.php';
            htmlspecialchars($tender_id);
        });
        $router->get('(\d+)-submit', function ($tender_id) {
            require __DIR__ . '/tenders/submit.php';
            htmlspecialchars($tender_id);
        });
        $router->post('send_application', function () {
            require __DIR__ . '/tenders/Tenders.php';
        });
        $router->get('(\d+)-submitted', function ($tender_id) {
            require __DIR__ . '/tenders/view_submit.php';
            htmlspecialchars($tender_id);
        });
        $router->get('(\d+)-draft', function ($tender_id) {
            require __DIR__ . '/tenders/draft_submit.php';
            htmlspecialchars($tender_id);
        });
        $router->post('send_draft', function () {
            require __DIR__ . '/tenders/TendersDraft.php';
        });
    });


       // Evaluations
       $router->mount('/evaluations', function () use ($router) {
        $router->get('/', function () {
            require __DIR__ . '/evaluations/';
        });
        $router->get('(\d+)-notice', function ($tender_id) {
            require __DIR__ . '/evaluations/notice.php';
            htmlspecialchars($tender_id);
        });
    });

    $router->run();
