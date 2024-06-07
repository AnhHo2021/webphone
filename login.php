<?php

require_once 'init_out_admin.php';

$_title = 'Welcome';
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
<html lang="en">
    <?php include_once APP_PATH.'/includes/head.php'; ?>
    <!-- Modal error -->
    <div class="modal fade" id="modal-error" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-alert">
                    <h5 class="modal-title color-white" id="err-message">Error</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <body>
        <div class="page-wrapper auth h">
            <div class="page-inner bg-white">
                <div class="page-content-wrapper bg-transparent m-0">
                    <div class="flex-1">
                        <div class="container py-4 py-lg-5 my-lg-5 px-4 px-sm-0">
                            <div class="row middle-horizontal">
                                <div class="div-image-logo">
                                    <img class="div-image" src="<?= ASSETS_URL ?>/img1/1CXPro-icon.png">
                                </div>
                            </div>
                            <div class="row middle-horizontal">
                                <div class="col-sm-12 col-md-6 col-lg-5 col-xl-4">
                                    <div class="card p-4 rounded-plus bg-faded">
                                        <form id="js-login" novalidate="">
                                            <div class="form-group">
                                                <label class="form-label" for="u_email">Email</label>
                                                <input type="email" id="u_email" class="form-control form-control-lg" placeholder="your email" required>
                                                <div class="invalid-feedback">Enter your email</div>
                                                <div class="help-block">Your email</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="u_password">Password</label>
                                                <input type="password" id="u_password" class="form-control form-control-lg" placeholder="password" value="" required>
                                                <div class="invalid-feedback">Enter your password</div>
                                                <div class="help-block">Enter your password</div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label" for="system">System</label>
                                                <select class="form-control form-control-lg" id="system">
                                                    <option value="phone">Phone only</option>
                                                    <option value="zoho">Zoho</option>
                                                </select>
                                            </div>
                                        </form>
                                        <div class="row no-gutters">
                                            <!--<div class="col-lg-6 pl-lg-1 my-2">
                                                <button id="register-btn" type="button" class="btn btn-info btn-block btn-lg">Sign up</button>
                                            </div> -->
                                            <div class="col-lg-6 pl-lg-1 my-2">
                                                <button id="login-btn" type="button" class="btn btn-danger btn-block btn-lg">Login</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="position-absolute pos-bottom pos-left pos-right p-3 text-center text-white bg_blue">
                                Powered by: <a href='https://1Wire.co' class='text-white a_none_decorate fw-500' title='https://1Wire.co' target='_blank'>1Wire.</a> Copyright 2022 (c)
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
        <script src="<?= APP_URL; ?>/js/register_login/login.js" type="text/javascript"></script>
        <script type="text/javascript">
            var lgin = new login();
            $(function(){
                lgin.init();
            });
        </script>
    </body>
</html>
