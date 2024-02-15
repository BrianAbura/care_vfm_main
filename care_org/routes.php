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

    define('BASEPATH','/care_org/');
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

    $router->get('/password-reset', function () {
        require __DIR__ . '/forgot_password.php';
    });

    $router->post('/reset_pass', function () {
        require __DIR__ . '/acc_reset.php';
    });

    $router->get('/home', function () {
        require __DIR__ . '/dashboard.php';
    });

    $router->get('/logout', function () {
        require __DIR__ . '/logout.php';
    });

    
    // Departments
    $router->mount('/departments', function () use ($router) {

        $router->get('/', function () {
           require __DIR__ . '/departments/';
        });
    });

        // Users
    $router->mount('/users', function () use ($router) {
        $router->get('/', function () {
            require __DIR__ . '/users/';
        });
        //Add User
        $router->get('add_user', function () {
            require __DIR__ . '/users/add_new_user.php';
        });
        $router->post('add_user', function () {
            require __DIR__ . '/users/Users.php';
        });
        
        //Edit Users
        $router->get('(\w+)', function ($user_id) {
            require __DIR__ . '/users/edit_user.php';
            htmlspecialchars($user_id);
        });
        $router->post('edit_user', function () {
            require __DIR__ . '/users/Users.php';
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

           // Requisitions
    $router->mount('/requisitions', function () use ($router) {
        $router->get('/', function () {
            require __DIR__ . '/requisitions/';
        });
        $router->get('create_requisition', function () {
            require __DIR__ . '/requisitions/create_requisition.php';
        });
        //Uploads
        $router->get('upload_requisition', function () {
            require __DIR__ . '/requisitions/upload_requisition.php';
        });
        $router->post('upload_verification', function () {
            require __DIR__ . '/requisitions/upload_verification.php';
        });
        $router->post('upload_complete', function () {
            require __DIR__ . '/requisitions/upload_complete.php';
        });

        $router->post('add_requisition', function () {
            require __DIR__ . '/requisitions/Requisitions.php';
        });
        $router->get('(\w+)-view', function ($requisition_id) {
            require __DIR__ . '/requisitions/view_requisition.php';
            htmlspecialchars($requisition_id);
        });
        $router->get('(\w+)-assign', function ($requisition_id) {
            require __DIR__ . '/requisitions/assign_requisition.php';
            htmlspecialchars($requisition_id);
        });
        $router->post('assignments', function () {
            require __DIR__ . '/requisitions/Assignments.php';
        });
        //Edit
        $router->get('(\w+)', function ($requisition_id) {
            require __DIR__ . '/requisitions/edit_requisition.php';
            htmlspecialchars($requisition_id);
        });
        $router->post('edit_requisition', function () {
            require __DIR__ . '/requisitions/Requisitions.php';
        });
    });

        // Tenders
        $router->mount('/tenders', function () use ($router) {
        $router->get('/', function () {
            require __DIR__ . '/tenders/';
        });
        $router->get('(\w+)-create', function ($requisition_id) {
            require __DIR__ . '/tenders/create_tender.php';
            htmlspecialchars($requisition_id);
        });
        $router->post('add_tender', function () {
            require __DIR__ . '/tenders/Tenders.php';
        });
        $router->get('(\w+)-view', function ($tender_id) {
            require __DIR__ . '/tenders/view_tender.php';
            htmlspecialchars($tender_id);
        });
        $router->get('(\w+)-review', function ($tender_id) {
            require __DIR__ . '/tenders/review_tender.php';
            htmlspecialchars($tender_id);
        });
        $router->get('(\w+)-publish', function ($tender_id) {
            require __DIR__ . '/tenders/publish_tender.php';
            htmlspecialchars($tender_id);
        });
        $router->get('(\w+)-edit', function ($tender_id) {
            require __DIR__ . '/tenders/edit_tender.php';
            htmlspecialchars($tender_id);
        });
        $router->post('edit_tender', function () {
            require __DIR__ . '/tenders/TenderEdit.php';
        });
        $router->get('pending', function () {
            require __DIR__ . '/tenders/pending_tenders.php';
        });
        $router->get('published', function () {
            require __DIR__ . '/tenders/published_tenders.php';
        });
        $router->post('tender_review', function () {
            require __DIR__ . '/tenders/TenderReview.php';
        });
    });

    // Evaluations
        $router->mount('/evaluations', function () use ($router) {
            $router->get('/', function () {
                require __DIR__ . '/evaluations/';
            });
            $router->get('(\w+)-quotations', function ($tender_id) {
                require __DIR__ . '/evaluations/view_quotations.php';
                htmlspecialchars($tender_id);
            });
            $router->get('all_evaluations', function () { //Current Evaluations
                require __DIR__ . '/evaluations/view_evaluations.php';
            });
            $router->get('(\w+)-nominate_committee', function ($tender_id) {
                require __DIR__ . '/evaluations/nominate_members.php';
                htmlspecialchars($tender_id);
            });
            $router->post('nominate_committee', function () {
                require __DIR__ . '/evaluations/Nominations.php';
            });
             //Preliminary Evaluation
            $router->get('(\w+)-bids-preliminary', function ($tender_id) {
                require __DIR__ . '/evaluations/all_preliminary_bids.php';
                htmlspecialchars($tender_id);
            });
            $router->post('evaluate-preliminary', function () {
                require __DIR__ . '/evaluations/evaluate_preliminary_bid.php';
            });
             //Technical Evaluation
            $router->get('(\w+)-bids-technical', function ($tender_id) {
                require __DIR__ . '/evaluations/all_technical_bids.php';
                htmlspecialchars($tender_id);
            });
            $router->post('evaluate-technical', function () {
                require __DIR__ . '/evaluations/evaluate_technical_bid.php';
            });

            //Financial Evaluation
            $router->get('(\w+)-bids-financial', function ($tender_id) {
            require __DIR__ . '/evaluations/all_financial_bids.php';
            htmlspecialchars($tender_id);
            });
            $router->post('evaluate-financial', function () {
                require __DIR__ . '/evaluations/evaluate_financial_bid.php';
            });
                //Submit Evaluation
            $router->post('submit_evaluation', function () {
                require __DIR__ . '/evaluations/Evaluations.php';
            });
            $router->post('submit_financials', function () {
                require __DIR__ . '/evaluations/Financials.php';
            });
            $router->get('summary', function () {
                require __DIR__ . '/evaluations/evaluation_summary.php';
            });
            $router->get('summary_tech', function () {
                require __DIR__ . '/evaluations/evaluation_tech_summary.php';
            });
            $router->post('submit_summary', function () {
                require __DIR__ . '/evaluations/Summary.php';
            });

            //Post-Qualification
            $router->get('post_qualification', function () {
                require __DIR__ . '/evaluations/upload_post_qn.php';
            });
            $router->get('postqn_del', function () {
                require __DIR__ . '/evaluations/Postqn.php';
            });
            $router->post('postqn', function () {
                require __DIR__ . '/evaluations/Postqn.php';
            });

            //Finalize
            $router->get('finalize', function () {
                require __DIR__ . '/evaluations/finalize_evaluation.php';
            });
            $router->post('conclude_evaluation', function () {
                require __DIR__ . '/evaluations/conclude_evaluation.php';
            });

            //Completed Evaluations: Evaluation Report Stage
            $router->get('completed_evaluations', function () { //Current Evaluations
                require __DIR__ . '/evaluations/view_completed_evaluations.php';
            });
            $router->get('report', function () { //Current Evaluations
                require __DIR__ . '/evaluations/evaluation_report.php';
            });
            $router->get('publish', function () { //Current Evaluations
                require __DIR__ . '/evaluations/publish_notice.php';
            });
            $router->post('complete_publish', function () { //Current Evaluations
                require __DIR__ . '/evaluations/Publish.php';
            });
        });


    // Vendors
    $router->mount('/vendors', function () use ($router) {

        $router->get('/', function () {
            require __DIR__ . '/vendors/';
        });
        $router->get('(\w+)-view', function ($vendor_id) {
            require __DIR__ . '/vendors/view.php';
            htmlspecialchars($vendor_id);
        });
        $router->get('(\w+)-review', function ($vendor_id) {
            require __DIR__ . '/vendors/review.php';
            htmlspecialchars($vendor_id);
        });
        $router->post('vendor_review', function () {
            require __DIR__ . '/vendors/Vendors.php';
        });
        $router->get('active', function () {
            require __DIR__ . '/vendors/';
        });
        $router->get('pending', function () {
            require __DIR__ . '/vendors/all_pending.php';
        });
        $router->get('on_hold', function () {
            require __DIR__ . '/vendors/all_onhold.php';
        });
        $router->get('rejected', function () {
            require __DIR__ . '/vendors/all_rejected.php';
        });

    });

    // System Management
    $router->mount('/system_management', function () use ($router) {

        $router->get('/', function () {
            require __DIR__ . '/system_management/';
        });

        $router->get('thresholds', function () {
            require __DIR__ . '/system_management/index.php';
        });

        $router->get('(\w+)', function ($threshold_id) {
            require __DIR__ . '/system_management/edit_threshold.php';
            htmlspecialchars($threshold_id);
        });

        $router->post('edit_threshold', function () {
            require __DIR__ . '/system_management/Thresholds.php';
        });

    });


    $router->run();

    ?>
