<?php 
$BASEPATH = dirname(__DIR__);
$DIR = __DIR__;
require_once($BASEPATH.'/validator.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tender Edit | Care Uganda</title>
    <?php 
    /*** Include the Global Headers Scripts */
    include $DIR."/headers.php"; 
	?> 
    <style>
	.control-label{
		font-weight:bold;
        color: #139cdb;
	}
    #demo-bv-bsc-tabs hr{
        background-color: rgb(133, 206, 249, 0.3);
        border: 0 none;
        height: 6px;
    }
</style>
</head>
<body>
    <div id="container" class="effect aside-float aside-bright mainnav-sm page-fixedbar">
        <header id="navbar">
            <div id="navbar-container" class="boxed">
                <!--Brand logo & name-->
                <!--================================-->
                <div class="navbar-header">
                    <a href="index.html" class="navbar-brand">
					
                        <div class="brand-title">
                            <span class="brand-text">Care</span>
                        </div>
                    </a>
                </div>
                <div class="navbar-content">
                    <ul class="nav navbar-top-links">
                        <li id="dropdown-user" class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="ic-user pull-right">

                                    <i class="ion-person icon-lg"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right panel-default">
                            <ul class="head-list">
                                    <li>
                                    <a href="../profile"><i class="demo-pli-gear icon-lg icon-fw"></i> Account Settings</a>
                                    </li>
                                    <li>
                                        <a href="../logout"><i class="demo-pli-unlock icon-lg icon-fw"></i> Logout</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#">
                                <i class="demo-pli-dot-vertical"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <!--===================================================-->
        <!--END NAVBAR-->

        <div class="boxed">
            <div id="content-container">
                <div id="page-head">
                    <div id="page-title">
                        <h1 class="page-header text-overflow">Vendor Registration</h1>
						<ol class="breadcrumb">
					<li><a href="#"><i class="ion-compose icon-2x"></i></a></li>
					<li class="active">Edit Vendor Details</li>
                    </ol>
                    </div>
                </div>
                <div class="page-fixedbar-container">
                    <div class="page-fixedbar-content">
					<span class="pad-ver text-main text-sm text-uppercase text-bold"><img src="..\img\care-int-logo.png" alt="Care-International" width="80%"></span>
                        <div class="nano">
                            <div class="nano-content">
							<hr class="new-section-xs">
                            <div class="panel">
                            <div class="panel-body text-center">
                                <?php 
                                $profile = "..\\img\\5.png";
                                $vendor = DB::queryFirstRow('SELECT * from vendors where vendor_user_id=%s', $current_user['user_id']);
                                if($vendor){
                                    $attachment = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s AND description IN %ls', $vendor['vendor_id'], ['Logo','Profile']);
                                    if(!empty($attachment['document_file'])){
                                        $profile =  str_replace($BASEPATH, '..', $attachment['document_file']);
                                    }
                                }
                                ?>
                                <img alt="Profile Picture" class="img-lg img-circle mar-btm" src="<?php echo $profile;?>">
                                <p class="text-lg text-semibold mar-no text-main"><?php echo $current_user['first_name']." ".$current_user['last_name'];?></p>
                                <p class="text-muted"><?php echo $current_user['email_address'];?></p>
                            </div>
                        </div>
                                <p class="pad-all text-main text-lg text-uppercase text-bold">Navigation</p>
                                <div class="list-group bg-trans">
                                    <a class="list-group-item active"><i class="ti-receipt icon-lg icon-fw"></i> Tenders</a>
									<ul class="breadcrumb">
									<li class="active"><a href="" class="list-group-item"> Current Tenders</a></li>
									<li><a href="submitted" class="list-group-item"> Tenders Submitted</a></li>
									<li><a href="closed" class="list-group-item">Closed Tenders</a></li>
									</ul>
						            <a href="../evaluations" class="list-group-item"><i class="ti-ruler-pencil icon-lg icon-fw"></i> Evaluations</a>
                                    <a href="#" class="list-group-item"><i class="ti-bell icon-lg icon-fw"></i> Notifications</a>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>


<?php 
$vendor_id = filter_var(trim($vendor_id), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$vendor = DB::queryFirstRow('SELECT * from vendors where vendor_id=%s', $vendor_id);
if(!isset($vendor))
{ //Display the notice below if null
?>
 <div id="page-content">
<div class="row"> 
	<div class="col-sm-7">
		<div class="panel">
			<div class="panel-body">
				<br/>
				<div class="alert alert-danger">
					<button class="close" data-dismiss="alert"></button>
					<strong>Warning!</strong> <br/><br/>
					The Record your are trying to access does not exist. Click <a href="../home" class="alert-link">Here.</a> to go back to the list of tenders.
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php 
}
else{
?>
                <div id="page-content">
					<div class="panel">
					    <div class="panel-body">
                        <form id="demo-bv-bsc-tabs" action="edit_vendor" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="token" value="edit_vendor">
                        <input type="hidden" name="vendor_id" value="<?php echo $vendor_id;?>">
						<div class="row">	
					    <div class="col-lg-12">
						    <div class="panel">
					            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">Vendor Name</label>
                                        <input type="text" class="form-control" name="vendor_name" placeholder="Vendor Registered Name" value="<?php echo $vendor['vendor_name'];?>" autocomplete="off" required>
                                    </div>
                                    </div>
                                    <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">Registration Number</label>
                                        <input type="text" class="form-control" name="registration_num" placeholder="Registration Number" autocomplete="off" value="<?php echo $vendor['registration_num'];?>" required>
                                    </div>
                                    </div>
                                    <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">Tax Identification Number</label>
                                        <input type="text" class="form-control" name="tin_num" placeholder="TIN/Equivalent" autocomplete="off" value="<?php echo $vendor['tin_num'];?>">
                                    </div>
                                    </div>
                                    <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">Business Email Address</label>
                                        <input type="email" class="form-control" name="email_address" placeholder="Business Email" autocomplete="off" value="<?php echo $vendor['email_address'];?>">
                                        <small class="text-muted">For Offical Communications</small>
                                    </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">Business Phone Number</label>
                                        <input type="text" class="form-control" name="phone_number" placeholder="Business Phone Number" autocomplete="off" value="<?php echo $vendor['phone_num'];?>">
                                    </div>
                                    </div>
                                    <div class="col-sm-3">
                                    <div class="form-group">
                                        <label class="control-label">Country of Registration</label>
                                <select class="selectpicker form-control" name="country" data-live-search="true">
                                    <option value="" selected="selected">Select Country</option> 
                                    <option value="Afghanistan">Afghanistan</option> 
                                    <option value="Albania">Albania</option> 
                                    <option value="Algeria">Algeria</option> 
                                    <option value="American Samoa">American Samoa</option> 
                                    <option value="Andorra">Andorra</option> 
                                    <option value="Angola">Angola</option> 
                                    <option value="Anguilla">Anguilla</option> 
                                    <option value="Antarctica">Antarctica</option> 
                                    <option value="Antigua and Barbuda">Antigua and Barbuda</option> 
                                    <option value="Argentina">Argentina</option> 
                                    <option value="Armenia">Armenia</option> 
                                    <option value="Aruba">Aruba</option> 
                                    <option value="Australia">Australia</option> 
                                    <option value="Austria">Austria</option> 
                                    <option value="Azerbaijan">Azerbaijan</option> 
                                    <option value="Bahamas">Bahamas</option> 
                                    <option value="Bahrain">Bahrain</option> 
                                    <option value="Bangladesh">Bangladesh</option> 
                                    <option value="Barbados">Barbados</option> 
                                    <option value="Belarus">Belarus</option> 
                                    <option value="Belgium">Belgium</option> 
                                    <option value="Belize">Belize</option> 
                                    <option value="Benin">Benin</option> 
                                    <option value="Bermuda">Bermuda</option> 
                                    <option value="Bhutan">Bhutan</option> 
                                    <option value="Bolivia">Bolivia</option> 
                                    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option> 
                                    <option value="Botswana">Botswana</option> 
                                    <option value="Bouvet Island">Bouvet Island</option> 
                                    <option value="Brazil">Brazil</option> 
                                    <option value="British Indian Ocean Territory">British Indian Ocean Territory</option> 
                                    <option value="Brunei Darussalam">Brunei Darussalam</option> 
                                    <option value="Bulgaria">Bulgaria</option> 
                                    <option value="Burkina Faso">Burkina Faso</option> 
                                    <option value="Burundi">Burundi</option> 
                                    <option value="Cambodia">Cambodia</option> 
                                    <option value="Cameroon">Cameroon</option> 
                                    <option value="Canada">Canada</option> 
                                    <option value="Cape Verde">Cape Verde</option> 
                                    <option value="Cayman Islands">Cayman Islands</option> 
                                    <option value="Central African Republic">Central African Republic</option> 
                                    <option value="Chad">Chad</option> 
                                    <option value="Chile">Chile</option> 
                                    <option value="China">China</option> 
                                    <option value="Christmas Island">Christmas Island</option> 
                                    <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option> 
                                    <option value="Colombia">Colombia</option> 
                                    <option value="Comoros">Comoros</option> 
                                    <option value="Congo">Congo</option> 
                                    <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option> 
                                    <option value="Cook Islands">Cook Islands</option> 
                                    <option value="Costa Rica">Costa Rica</option> 
                                    <option value="Cote D'ivoire">Cote D'ivoire</option> 
                                    <option value="Croatia">Croatia</option> 
                                    <option value="Cuba">Cuba</option> 
                                    <option value="Cyprus">Cyprus</option> 
                                    <option value="Czech Republic">Czech Republic</option> 
                                    <option value="Denmark">Denmark</option> 
                                    <option value="Djibouti">Djibouti</option> 
                                    <option value="Dominica">Dominica</option> 
                                    <option value="Dominican Republic">Dominican Republic</option> 
                                    <option value="Ecuador">Ecuador</option> 
                                    <option value="Egypt">Egypt</option> 
                                    <option value="El Salvador">El Salvador</option> 
                                    <option value="Equatorial Guinea">Equatorial Guinea</option> 
                                    <option value="Eritrea">Eritrea</option> 
                                    <option value="Estonia">Estonia</option> 
                                    <option value="Ethiopia">Ethiopia</option> 
                                    <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option> 
                                    <option value="Faroe Islands">Faroe Islands</option> 
                                    <option value="Fiji">Fiji</option> 
                                    <option value="Finland">Finland</option> 
                                    <option value="France">France</option> 
                                    <option value="French Guiana">French Guiana</option> 
                                    <option value="French Polynesia">French Polynesia</option> 
                                    <option value="French Southern Territories">French Southern Territories</option> 
                                    <option value="Gabon">Gabon</option> 
                                    <option value="Gambia">Gambia</option> 
                                    <option value="Georgia">Georgia</option> 
                                    <option value="Germany">Germany</option> 
                                    <option value="Ghana">Ghana</option> 
                                    <option value="Gibraltar">Gibraltar</option> 
                                    <option value="Greece">Greece</option> 
                                    <option value="Greenland">Greenland</option> 
                                    <option value="Grenada">Grenada</option> 
                                    <option value="Guadeloupe">Guadeloupe</option> 
                                    <option value="Guam">Guam</option> 
                                    <option value="Guatemala">Guatemala</option> 
                                    <option value="Guinea">Guinea</option> 
                                    <option value="Guinea-bissau">Guinea-bissau</option> 
                                    <option value="Guyana">Guyana</option> 
                                    <option value="Haiti">Haiti</option> 
                                    <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option> 
                                    <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option> 
                                    <option value="Honduras">Honduras</option> 
                                    <option value="Hong Kong">Hong Kong</option> 
                                    <option value="Hungary">Hungary</option> 
                                    <option value="Iceland">Iceland</option> 
                                    <option value="India">India</option> 
                                    <option value="Indonesia">Indonesia</option> 
                                    <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option> 
                                    <option value="Iraq">Iraq</option> 
                                    <option value="Ireland">Ireland</option> 
                                    <option value="Israel">Israel</option> 
                                    <option value="Italy">Italy</option> 
                                    <option value="Jamaica">Jamaica</option> 
                                    <option value="Japan">Japan</option> 
                                    <option value="Jordan">Jordan</option> 
                                    <option value="Kazakhstan">Kazakhstan</option> 
                                    <option value="Kenya">Kenya</option> 
                                    <option value="Kiribati">Kiribati</option> 
                                    <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option> 
                                    <option value="Korea, Republic of">Korea, Republic of</option> 
                                    <option value="Kuwait">Kuwait</option> 
                                    <option value="Kyrgyzstan">Kyrgyzstan</option> 
                                    <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option> 
                                    <option value="Latvia">Latvia</option> 
                                    <option value="Lebanon">Lebanon</option> 
                                    <option value="Lesotho">Lesotho</option> 
                                    <option value="Liberia">Liberia</option> 
                                    <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option> 
                                    <option value="Liechtenstein">Liechtenstein</option> 
                                    <option value="Lithuania">Lithuania</option> 
                                    <option value="Luxembourg">Luxembourg</option> 
                                    <option value="Macao">Macao</option> 
                                    <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option> 
                                    <option value="Madagascar">Madagascar</option> 
                                    <option value="Malawi">Malawi</option> 
                                    <option value="Malaysia">Malaysia</option> 
                                    <option value="Maldives">Maldives</option> 
                                    <option value="Mali">Mali</option> 
                                    <option value="Malta">Malta</option> 
                                    <option value="Marshall Islands">Marshall Islands</option> 
                                    <option value="Martinique">Martinique</option> 
                                    <option value="Mauritania">Mauritania</option> 
                                    <option value="Mauritius">Mauritius</option> 
                                    <option value="Mayotte">Mayotte</option> 
                                    <option value="Mexico">Mexico</option> 
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option> 
                                    <option value="Moldova, Republic of">Moldova, Republic of</option> 
                                    <option value="Monaco">Monaco</option> 
                                    <option value="Mongolia">Mongolia</option> 
                                    <option value="Montserrat">Montserrat</option> 
                                    <option value="Morocco">Morocco</option> 
                                    <option value="Mozambique">Mozambique</option> 
                                    <option value="Myanmar">Myanmar</option> 
                                    <option value="Namibia">Namibia</option> 
                                    <option value="Nauru">Nauru</option> 
                                    <option value="Nepal">Nepal</option> 
                                    <option value="Netherlands">Netherlands</option> 
                                    <option value="Netherlands Antilles">Netherlands Antilles</option> 
                                    <option value="New Caledonia">New Caledonia</option> 
                                    <option value="New Zealand">New Zealand</option> 
                                    <option value="Nicaragua">Nicaragua</option> 
                                    <option value="Niger">Niger</option> 
                                    <option value="Nigeria">Nigeria</option> 
                                    <option value="Niue">Niue</option> 
                                    <option value="Norfolk Island">Norfolk Island</option> 
                                    <option value="Northern Mariana Islands">Northern Mariana Islands</option> 
                                    <option value="Norway">Norway</option> 
                                    <option value="Oman">Oman</option> 
                                    <option value="Pakistan">Pakistan</option> 
                                    <option value="Palau">Palau</option> 
                                    <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option> 
                                    <option value="Panama">Panama</option> 
                                    <option value="Papua New Guinea">Papua New Guinea</option> 
                                    <option value="Paraguay">Paraguay</option> 
                                    <option value="Peru">Peru</option> 
                                    <option value="Philippines">Philippines</option> 
                                    <option value="Pitcairn">Pitcairn</option> 
                                    <option value="Poland">Poland</option> 
                                    <option value="Portugal">Portugal</option> 
                                    <option value="Puerto Rico">Puerto Rico</option> 
                                    <option value="Qatar">Qatar</option> 
                                    <option value="Reunion">Reunion</option> 
                                    <option value="Romania">Romania</option> 
                                    <option value="Russian Federation">Russian Federation</option> 
                                    <option value="Rwanda">Rwanda</option> 
                                    <option value="Saint Helena">Saint Helena</option> 
                                    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
                                    <option value="Saint Lucia">Saint Lucia</option> 
                                    <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option> 
                                    <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option> 
                                    <option value="Samoa">Samoa</option> 
                                    <option value="San Marino">San Marino</option> 
                                    <option value="Sao Tome and Principe">Sao Tome and Principe</option> 
                                    <option value="Saudi Arabia">Saudi Arabia</option> 
                                    <option value="Senegal">Senegal</option> 
                                    <option value="Serbia and Montenegro">Serbia and Montenegro</option> 
                                    <option value="Seychelles">Seychelles</option> 
                                    <option value="Sierra Leone">Sierra Leone</option> 
                                    <option value="Singapore">Singapore</option> 
                                    <option value="Slovakia">Slovakia</option> 
                                    <option value="Slovenia">Slovenia</option> 
                                    <option value="Solomon Islands">Solomon Islands</option> 
                                    <option value="Somalia">Somalia</option> 
                                    <option value="South Africa">South Africa</option> 
                                    <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option> 
                                    <option value="Spain">Spain</option> 
                                    <option value="Sri Lanka">Sri Lanka</option> 
                                    <option value="Sudan">Sudan</option> 
                                    <option value="Suriname">Suriname</option> 
                                    <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option> 
                                    <option value="Swaziland">Swaziland</option> 
                                    <option value="Sweden">Sweden</option> 
                                    <option value="Switzerland">Switzerland</option> 
                                    <option value="Syrian Arab Republic">Syrian Arab Republic</option> 
                                    <option value="Taiwan, Province of China">Taiwan, Province of China</option> 
                                    <option value="Tajikistan">Tajikistan</option> 
                                    <option value="Tanzania, United Republic of">Tanzania, United Republic of</option> 
                                    <option value="Thailand">Thailand</option> 
                                    <option value="Timor-leste">Timor-leste</option> 
                                    <option value="Togo">Togo</option> 
                                    <option value="Tokelau">Tokelau</option> 
                                    <option value="Tonga">Tonga</option> 
                                    <option value="Trinidad and Tobago">Trinidad and Tobago</option> 
                                    <option value="Tunisia">Tunisia</option> 
                                    <option value="Turkey">Turkey</option> 
                                    <option value="Turkmenistan">Turkmenistan</option> 
                                    <option value="Turks and Caicos Islands">Turks and Caicos Islands</option> 
                                    <option value="Tuvalu">Tuvalu</option> 
                                    <option value="Uganda">Uganda</option> 
                                    <option value="Ukraine">Ukraine</option> 
                                    <option value="United Arab Emirates">United Arab Emirates</option> 
                                    <option value="United Kingdom">United Kingdom</option> 
                                    <option value="United States">United States</option> 
                                    <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option> 
                                    <option value="Uruguay">Uruguay</option> 
                                    <option value="Uzbekistan">Uzbekistan</option> 
                                    <option value="Vanuatu">Vanuatu</option> 
                                    <option value="Venezuela">Venezuela</option> 
                                    <option value="Viet Nam">Viet Nam</option> 
                                    <option value="Virgin Islands, British">Virgin Islands, British</option> 
                                    <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option> 
                                    <option value="Wallis and Futuna">Wallis and Futuna</option> 
                                    <option value="Western Sahara">Western Sahara</option> 
                                    <option value="Yemen">Yemen</option> 
                                    <option value="Zambia">Zambia</option> 
                                    <option value="Zimbabwe">Zimbabwe</option>
                                    </select>
                                    </div>
                                    </div>
                                    <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label">City</label>
                                        <input type="text" class="form-control" name="city" placeholder="City" autocomplete="off" value="<?php echo $vendor['city'];?>">
                                    </div>
                                    </div>
                                    <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label">Street Address</label>
                                        <textarea placeholder="" rows="3" class="form-control" name="street_address"><?php echo $vendor['street_address'];?></textarea>
                                    </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label">Postal Address</label>
                                        <textarea placeholder="" rows="3" class="form-control" name="postal_address"><?php echo $vendor['postal_code'];?></textarea>
                                    </div>
                                    </div>
                                </div>
                                
                                <hr />
                                <div class="row">
                                <div class="col-12">
                                <div class="form-group">
                                <label class="control-label">Business Categories:</label>
                                    <select class="duallistbox form-control" multiple="multiple" name="business_categories[]" form-control>
                                    <?php 
                                        $cur_categs = DB::query('SELECT * from vendor_categories where vendor_id=%s', $vendor_id);
                                        foreach($cur_categs as $cur_categ)
                                        {
                                            $codes = DB::queryFirstRow('SELECT * from unspsc where fam_code=%s', $cur_categ['fam_code']);
                                        ?>	
                                        <option value="<?php echo $codes['fam_code'];?>" selected><?php echo $codes['description'];?></option>
                                        <?php } ?>
                                        

                                        <?php
                                        $all_codes = DB::query('SELECT * from unspsc order by description');
                                        foreach($all_codes as $code){
                                            $get_code = DB::queryFirstRow('SELECT * from vendor_categories where vendor_id=%s AND fam_code=%s', $vendor_id, $code['fam_code']);
                                            if($get_code){
                                                continue;
                                            }
                                        ?>
                                        <option value="<?php echo $code['fam_code'];?>"><?php echo $code['description'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                </div>
                                </div>
                                <hr/>
                                <label class="control-label">Business Supporting Documents:</label>
                                
                                <div class="row">
                                <?php 
                                $BASEPATH = dirname(__DIR__);
                                $uploads = DB::queryFirstRow('SELECT * from vendor_attachments where vendor_id=%s', $vendor_id);
                                $logo =  str_replace($BASEPATH, '..', $uploads['logo']);
                                $reg_cert =  str_replace($BASEPATH, '..', $uploads['registration_cert']);
                                $trade_license =  str_replace($BASEPATH, '..', $uploads['trading_cert']);
                                ?>
                                <br/>
                                <div class="col-md-4">
                                <div class="form-group">
                                <label class="control-label">Company Logo/Profile Photo</label> 
                                        <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <div class="input-append">
                                            <div class="uneditable-input">
                                            <span class="fileupload-preview" style="font-size: 12px; color:blue"></span>
                                            </div>
                                            <span class="btn btn-default btn-file">
                                            <span class="btn btn-md btn-mint fa fa-edit fileupload-exists" title="Change Attachment"></span>
                                            <span class="fileupload-new btn btn-primary fa fa-upload"> Select file</span>
                                            <input type="file" id="vendor_logo" name="vendor_logo" onchange="ValidateLogo(this);"/>
                                            </span>
                                            <a href="#" class="btn btn-md btn-danger demo-pli-trash fileupload-exists" title="Remove Attachment" data-dismiss="fileupload"></a>
                                            <p class="help-block">Accepted Formats: jpg, jpeg and png</p>
                                            <br/> <br/>
                                            <a href="<?php echo $logo;?>" target="_blank" class="btn-link text-semibold"><i class="fa fa-cloud-download icon-2x icon-fw"></i>Attachment</a>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Registration Certificate</label> 
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="input-append">
                                                <div class="uneditable-input">
                                                <span class="fileupload-preview" style="font-size: 12px; color:blue"></span>
                                                </div>
                                                <span class="btn btn-default btn-file">
                                                <span class="btn btn-md btn-mint fa fa-edit fileupload-exists" title="Change Attachment"></span>
                                                <span class="fileupload-new btn btn-primary fa fa-upload"> Select file</span>
                                                <input type="file" id="reg_cert" name="reg_cert" onchange="ValidateSingleInput(this);"/>
                                                </span>
                                                <a href="#" class="btn btn-md btn-danger demo-pli-trash fileupload-exists" title="Remove Attachment" data-dismiss="fileupload"></a>
                                                <p class="help-block">Accepted Formats: pdf, jpg, jpeg and png</p>
                                                <br/> <br/>
                                                <a href="<?php echo $reg_cert;?>" target="_blank" class="btn-link text-semibold"><i class="fa fa-cloud-download icon-2x icon-fw"></i>Attachment</a>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Trading License</label> 
                                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                            <div class="input-append">
                                                <div class="uneditable-input">
                                                <span class="fileupload-preview" style="font-size: 12px; color:blue"></span>
                                                </div>
                                                <span class="btn btn-default btn-file">
                                                <span class="btn btn-md btn-mint fa fa-edit fileupload-exists" title="Change Attachment"></span>
                                                <span class="fileupload-new btn btn-primary fa fa-upload"> Select file</span>
                                                <input type="file" id="trade_license" name="trade_license" onchange="ValidateSingleInput(this);"/>
                                                </span>
                                                <a href="#" class="btn btn-md btn-danger demo-pli-trash fileupload-exists" title="Remove Attachment" data-dismiss="fileupload"></a>
                                                <p class="help-block">Accepted Formats: pdf, jpg, jpeg and png</p>
                                                <br/> <br/>
                                                <a href="<?php echo $trade_license;?>" target="_blank" class="btn-link text-semibold"><i class="fa fa-cloud-download icon-2x icon-fw"></i>Attachment</a>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


					            </div>
                                
					        </div>
                            <hr/>
                                <div class="tab-footer clearfix">	
                                    <a href="../home" class="btn btn-danger">Cancel</a>	
                                    <button type="submit" id="btnSaveDraft" name="formBtn" value="btnSave" class="btn btn-info">Save Draft</button>
                                    <button type="submit" id="btnCreate" class="btn btn-mint pull-right">Submit Details</button>
                                </div>
					    </div>
					</div>
                            </form>
					    </div>
					</div>
                </div>
<?php }?>
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
            
            <!--MAIN NAVIGATION-->
            <!--===================================================-->
            <nav id="mainnav-container">
                <div id="mainnav">
                    <!--Menu-->
                    <!--================================-->
                    <div id="mainnav-menu-wrap">
                        <div class="nano">
                            <div class="nano-content">
                                <ul id="mainnav-menu" class="list-group">
						            <!--Menu list item-->
						            <li class="active-sub">
						                <a href="../home">
						                    <i class="demo-pli-home"></i>
						                    <span class="menu-title">Dashboard</span>
											<i class="arrow"></i>
						                </a>
						            </li>
									<li>
						                <a href="../logout">
						                    <i class="demo-pli-unlock icon-lg icon-fw"></i>
						                    <span class="menu-title">Logout</span>
											<i class="arrow"></i>
						                </a>
						            </li>
                            	 </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!--===================================================-->
            <!--END MAIN NAVIGATION-->
        </div>
		<?php 
    /*** Include the Global Footer and Java Scripts */
	include $DIR."/footers.php";  
    ?>
    <script>
          $(function () { 
    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

        })
    </script>
    <script>
	//Saving as Draft
$('#btnSaveDraft').on("click", function () {
	$("#demo-bv-bsc-tabs").submit(function(e) {
	e.preventDefault(); 
	var form = $(this).serializeArray();
	form.push({name: "formAction", value: "SaveDraft"});
		$.ajax({
			type: "POST",
			url: "edit_vendor",
			contentType: 'application/x-www-form-urlencoded',
			data: $.param(form),
			success: function(data)
			{

				var result = JSON.parse(data);
				if (result.Status == "Success") {
					$.niftyNoty({
						type: 'success',
						icon : 'pli-like-2 icon-2x',
						message : result.Message,
						container : 'floating',
						timer : 5000
					});
				}else{
					$.niftyNoty({
						type: 'danger',
						icon : 'pli-cross icon-2x',
						message : result.Message,
						container : 'floating',
						timer : 5000
					});  
				};
                setTimeout(function(){ window.location = "../home"; }, 6000);
			}
		});
	});

});


//Validate Submission Form
$(document).on('nifty.ready', function() {

// FORM VALIDATION FEEDBACK ICONS
// =================================================================
var faIcon = {
	valid: 'fa fa-check-circle fa-lg text-success',
	invalid: 'fa fa-times-circle fa-lg',
	validating: 'fa fa-refresh'
}

// FORM VALIDATION ON TABS
// =================================================================
$('#btnCreate').on("click", function () {
	$('#demo-bv-bsc-tabs').bootstrapValidator({
	excluded: [':disabled'],
	feedbackIcons: faIcon,

	fields: {
		vendor_name: { validators: { notEmpty: { message: 'Vendor is required' } } },
		registration_num: { validators: { notEmpty: { message: 'The Registration Number is required' } } },
		tin_num: { validators: { notEmpty: { message: 'Tax Idenfication Number is required' } } },
		email_address: { validators: { notEmpty: { message: 'Email Address is required' } } },
		phone_number: { validators: { notEmpty: { message: 'Phone Number ID is required' } } },
		street_address: { validators: { notEmpty: { message: 'Street Addresss is required' } } },
		business_categories: { validators: { notEmpty: { message: 'Select atleast 6 Business Categories' } } },

	}
}).on('status.field.bv', function(e, data) {
	var $form     = $(e.target),
	validator = data.bv,
	$tabPane  = data.element.parents('.tab-pane'),
	tabId     = $tabPane.attr('id'); 

	if (tabId) {
	var $icon = $('a[href="#' + tabId + '"][data-toggle="tab"]').parent().find('i');

	// Add custom class to tab containing the field
	if (data.status == validator.STATUS_INVALID) {
		$icon.removeClass(faIcon.valid).addClass(faIcon.invalid);
	} else if (data.status == validator.STATUS_VALID) {
		var isValidTab = validator.isValidContainer($tabPane);
		$icon.removeClass(faIcon.valid).addClass(isValidTab ? faIcon.valid : faIcon.invalid);
	}
	}
});
});
});
</script>
</body>
</html>