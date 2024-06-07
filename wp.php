<?php
session_start();
if(!isset($_SESSION['1wire']['web_phone'])) header("Location: login.php");

require_once 'init_out_admin.php';

$_title = 'Wellcome';
$_head = '<link rel="stylesheet" media="screen, print" href="'.ASSETS_URL.'/css/fa-brands.css">
          <link rel="stylesheet" media="screen, print" href="'.ASSETS_URL.'/css1/web_phone.css">
';

?>
<!DOCTYPE html>
<!-- 
Template Name:: SmartAdmin PHP 7 Responsive WebApp - Template built with Bootstrap 4 and PHP 7
Version: 4.5.3
Author: Jovanni Lo
Website: https://smartadmin.lodev09.com
Purchase: https://wrapbootstrap.com/theme/smartadmin-php-7-responsive-webapp-WB05M9585
License: You must have a valid license purchased only from wrapbootstrap.com (link above) in order to legally use this theme for your project.
-->
<style>
    .page-logo1 img {
        width: 28px;
        height: 28px;
        margin: 5px;
    }
    .page-avatar1{float: right}
    .page-avatar1 img {
        width: 28px;
        height: 28px;
        position: absolute;
        right: 25px;
    }
    .dropbtn {
       /* background-color: #04AA6D;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;*/
    }

    .dropdown {
        position: relative;
        min-width: 70px;
        min-height: 35px;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        top: 40px;
        right: 1px;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {background-color: #ddd;}

    .dropdown:hover .dropdown-content {display: block;}

    .dropdown:hover .dropbtn {/*background-color: #3e8e41;*/}

    .rounded-plus1{
        border-top-left-radius: 10px!important;
        border-top-right-radius: 10px!important;
        border-bottom-left-radius:0px !important;
        border-bottom-right-radius:0px !important;
    }

    .rounded-plus2{
        border-top-left-radius: 0px!important;
        border-top-right-radius: 0px!important;
        border-bottom-left-radius: 10px!important;
        border-bottom-right-radius: 10px!important;
    }

    .head_hight{
        height: 50px;
        display: flex;
        vertical-align: middle;
    }

</style>
<html lang="en">
    <?php include_once APP_PATH.'/includes/head.php'; ?>
    <body>
        <div class="page-wrapper auth">
            <div class="page-inner bg-brand-gradient">
                <div class="page-content-wrapper bg-transparent m-0">
                    <div class="flex-1" style="background: url(<?= ASSETS_URL ?>/img/svg/pattern-1.svg) no-repeat center bottom fixed; background-size: cover;">
                        <div class="container py-4 py-lg-5 my-lg-5 px-4 px-sm-0">
                            <div class="row middle-horizontal">
                                <div class="col-sm-12 col-md-6 col-lg-5 col-xl-4">
                                    <div class="card  rounded-plus1 bg-faded">
                                        <div class="form-group ">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="page-logo1 head_hight">
                                                        <a href="home.php" class="page-logo-link press-scale-down d-flex align-items-center position-relative">
                                                            <img src="<?=ASSETS_URL;?>/img/logo.png" alt=">Phone" aria-roledescription="logo">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="page-avatar1 dropdown head_hight">
                                                        <a  class="dropbtn  header-icon1 d-flex align-items-center justify-content-center1 ml-2">
                                                            <img src="http://s3.amazonaws.com/appforest_uf/f1584376106762x402033725538128100/avatar.png" alt=">Phone" class="profile-image rounded-circle">
                                                        </a>
                                                        <div class="dropdown-content">
                                                            <a class="b-b-c111-s" href="<?=APP_URL;?>/profile.php">Profile</a>
                                                            <a class="b-b-c111-s"  href="<?=APP_URL;?>/dashboard_admin.php">Admin</a>
                                                            <a  href="<?=APP_URL;?>/php/clearSession.php">Logout</a>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card p-4 rounded-plus2 bg-faded">
                                        <form id="js-login" novalidate="">

                                            <div class="form-group" style="display: none">
                                                <label class="form-label" for="grant_type">Grant type</label>
                                                <input type="text" id="grant_type" class="form-control form-control-lg" placeholder="Grant type" value="password" required>
                                                <div class="invalid-feedback">Grant type</div>
                                            </div>
                                            <div class="form-group" style="display: none">
                                                <label class="form-label" for="client_id">Client id</label>
                                                <input type="text" id="client_id" class="form-control form-control-lg" placeholder="Client id" value="connect" required>
                                                <div class="invalid-feedback">Enter Client id</div>
                                            </div>
                                            <div class="form-group" style="display: none">
                                                <label class="form-label" for="client_secret">Client secret</label>
                                                <input type="password" id="client_secret" class="form-control form-control-lg" placeholder="Client secret" value="53d870bbc924586045f568c73bb777a9" required>
                                                <div class="invalid-feedback">Enter Client secret</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="username">User name</label>
                                                <input type="text" id="username" class="form-control form-control-lg" placeholder="User name" required>
                                                <div class="invalid-feedback">Enter User namel</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="password">Passwork</label>
                                                <input type="password" id="password" class="form-control form-control-lg" placeholder="password" value="" required>
                                                <div class="invalid-feedback">Enter your password</div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label" for="system">System</label>
                                                <select class="form-control form-control-lg">
                                                    <option value="phone-only">Phone only</option>
                                                    <option value="zoho">Zoho</option>
                                                </select>
                                            </div>

                                        </form>
                                        <div class="row no-gutters">
                                            <div class="col-lg-6 pl-lg-1 my-2">
                                                <button id="register-btn" type="button" class="btn btn-info btn-block btn-lg">Reset Pass</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="position-absolute pos-bottom pos-left pos-right p-3 text-center text-white">
                                2020 © SmartAdmin for PHP by&nbsp;<a href='https://smartadmin.lodev09.com' class='text-white opacity-40 fw-500' title='smartadmin.lodev09.com' target='_blank'>@lodev09</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- BEGIN Colors -->
        <!-- BEGIN Color profile -->
        <!-- this area is hidden and will not be seen on screens or screen readers -->
        <!-- we use this only for CSS color refernce for JS stuff -->
        <p id="js-color-profile" class="d-none">
            <span class="color-primary-50"></span>
            <span class="color-primary-100"></span>
            <span class="color-primary-200"></span>
            <span class="color-primary-300"></span>
            <span class="color-primary-400"></span>
            <span class="color-primary-500"></span>
            <span class="color-primary-600"></span>
            <span class="color-primary-700"></span>
            <span class="color-primary-800"></span>
            <span class="color-primary-900"></span>
            <span class="color-info-50"></span>
            <span class="color-info-100"></span>
            <span class="color-info-200"></span>
            <span class="color-info-300"></span>
            <span class="color-info-400"></span>
            <span class="color-info-500"></span>
            <span class="color-info-600"></span>
            <span class="color-info-700"></span>
            <span class="color-info-800"></span>
            <span class="color-info-900"></span>
            <span class="color-danger-50"></span>
            <span class="color-danger-100"></span>
            <span class="color-danger-200"></span>
            <span class="color-danger-300"></span>
            <span class="color-danger-400"></span>
            <span class="color-danger-500"></span>
            <span class="color-danger-600"></span>
            <span class="color-danger-700"></span>
            <span class="color-danger-800"></span>
            <span class="color-danger-900"></span>
            <span class="color-warning-50"></span>
            <span class="color-warning-100"></span>
            <span class="color-warning-200"></span>
            <span class="color-warning-300"></span>
            <span class="color-warning-400"></span>
            <span class="color-warning-500"></span>
            <span class="color-warning-600"></span>
            <span class="color-warning-700"></span>
            <span class="color-warning-800"></span>
            <span class="color-warning-900"></span>
            <span class="color-success-50"></span>
            <span class="color-success-100"></span>
            <span class="color-success-200"></span>
            <span class="color-success-300"></span>
            <span class="color-success-400"></span>
            <span class="color-success-500"></span>
            <span class="color-success-600"></span>
            <span class="color-success-700"></span>
            <span class="color-success-800"></span>
            <span class="color-success-900"></span>
            <span class="color-fusion-50"></span>
            <span class="color-fusion-100"></span>
            <span class="color-fusion-200"></span>
            <span class="color-fusion-300"></span>
            <span class="color-fusion-400"></span>
            <span class="color-fusion-500"></span>
            <span class="color-fusion-600"></span>
            <span class="color-fusion-700"></span>
            <span class="color-fusion-800"></span>
            <span class="color-fusion-900"></span>
        </p>
        <!-- END Color profile -->
        <!-- END Colors -->
        <?php include_once APP_PATH.'/includes/js.php'; ?>

        <script src="<?= APP_URL ?>/js/refresh_token.js"></script>

        <script src="<?= APP_URL; ?>/js/register_login/login_wph.js" type="text/javascript"></script>
        <script type="text/javascript">
            var lgin = new login_wph();
            $(function(){
                lgin.init();
            });
        </script>
    </body>
</html>
