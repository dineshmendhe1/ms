<?php
$jsFolder = WP_PLUGIN_URL . '/mcafee-secure/js/';
$cssFolder = WP_PLUGIN_URL . '/mcafee-secure/css/';
$email = get_option( 'admin_email' );;
$partner = 'wp-generic';
$affiliate = '221269';
$arrHost = parse_url(home_url('', $scheme = 'http'));
$host = $arrHost['host'];
$endpointUrl = 'https://www.mcafeesecure.com';

?>
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<script src="<?php echo $jsFolder; ?>mcafeesecure.js"></script>

<link rel="stylesheet" href="<?php echo $cssFolder; ?>common.css" type="text/css">
<link rel="stylesheet" href="<?php echo $cssFolder; ?>user.css" type="text/css">
<link rel="stylesheet" href="<?php echo $cssFolder; ?>settings.css" type="text/css">
<link rel="stylesheet" href="<?php echo $cssFolder; ?>override.css" type="text/css">

<div class="wrap" id="mcafeesecure-container">
    <div id="mcafeesecure-data" data-host="<?php echo $host; ?>" data-email="<?php echo $email; ?>"></div>

    <div id="mcafeesecure-activation" style="display: none;">
        <h1>McAfee SECURE Activation</h1>
        <br/>
        <input type="submit" name="submit" class="button button-primary" value="Activate Now" id="activate-now"></p>
    </div>

    <div id="mcafeesecure-dashboard" style="display: none;">
        <h1>McAfee SECURE</h1>
        <div id="mcafeesecure-announcement">
            <i class="fa fa-lightbulb-o" style="color:#FDB813; font-size:20px"></i>

            <span style="margin-left:16px;">
                <strong style="color:rgb(51,51,51);">New Pro Feature:</strong> Give your customers $100,000 identity theft protection.
            </span>

            <a href="https://www.mcafeesecure.com/shopper-identity-protection" target="_blank" style="font-weight: bold;">
                Learn More &#187;
            </a>
        </div>
        <br/>
        <div class="wrapper">
            <div id="content">
                <div class="row row-toggle" id="security">
                    <a href="javascript:void(0);" class="link" target="_blank">View Details</a>

                    <div class="row-title">
                        <span class="status-icon"></span>
                        <b>Security</b>
                    </div>

                    <span class="status-text"></span>
                </div>

                <div class="row row-toggle" href-linked="1" id="certification">
                    <a href="javascript:void(0);" class="link" target="_blank">Go Unlimited</a>

                    <div class="row-title">
                        <span class="status-icon"></span>
                        <b>Certification Trustmark</b>
                    </div>

                    <span class="status-text"></span>
                </div>


                <div class="row lite" href-linked="1" id="highlighting">
                    <a href="javascript:void(0);" class="link" target="_blank">Learn More</a>

                    <div class="row-title">
                        <span class="status-icon"></span>
                        <b>Search Highlighting</b>
                    </div>

                    <div class="pill pill-grey" style="display: none;" target="_blank">Pro Feature</div>
                    <span class="status-text">
                    </span>
                </div>

                <div class="row lite" href-linked="1" id="engagement">
                    <a href="javascript:void(0);" class="link" target="_blank">Learn More</a>

                    <div class="row-title">
                        <span class="status-icon"></span>
                        <b>Engagement Trustmark</b>
                    </div>

                    <div class="pill pill-grey" style="display: none;">Pro Feature</div>
                    <span class="status-text"></span>
                </div>

                <div class="row lite" href-linked="1" id="sip">
                    <a href="javascript:void(0);" class="link" target="_blank">Learn More</a>

                    <div class="row-title">
                        <span class="status-icon"></span>
                        <b>ID Protection</b>
                    </div>

                    <div class="pill pill-grey" style="display: none;">Pro Feature</div>
                    <span class="status-text"></span>
                </div>

            </div>    
        </div>
        
        <br/>

        <h1>TrustedSite</h1>
        <br/>

        <div class="wrapper">
            <div id="content">
                <div class="row" href-linked="1" id="reviews">
                    <a href="javascript:void(0);" class="link" target="_blank">View Details</a>

                    <div class="row-title">
                        <i class="fa fa-check"></i>
                        <b>Site Reviews</b>
                    </div> 

                    <span class="review-status"></span>
                </div>

                <div class="row" href-linked="1" id="purchase-review-email">
                    <a href="javascript:void(0);" class="link" target="_blank" class="link">View Details</a>

                    <div class="row-title">
                        <span class="status-icon"></span>
                        <b>Purchase Review Email</b>
                    </div>

                    <div class="pill pill-grey" style="display: none;">Pro Feature</div>
                    <span class="status-text"></span>
                </div>

                <div class="row" href-linked="1" id="sitemap">
                    <a href="javascript:void(0);" class="link" target="_blank" class="link">View Details</a>

                    <div class="row-title">
                        <span class="status-icon"></span>
                        <b>Sitemap</b>
                    </div>

                    <div class="pill pill-grey" style="display: none;">Pro Feature</div>
                    <span class="status-text"></span>
                </div>

                <div class="row" href-linked="1" id="diagnostics">
                    <a href="javascript:void(0);" target="_blank" class="link">View Details</a>

                    <div class="row-title">
                        <span class="status-icon"></span>
                        <b>Diagnostics</b>
                    </div>

                    <div class="pill pill-grey" style="display: none;">Pro Feature</div>
                    <span class="status-text"></span>
                </div>

                <div class="row" href-linked="1" id="profile">
                    <a href="javascript:void(0);" target="_blank" class="link">Edit Profile</a>

                    <div class="row-title">
                        <span class="status-icon"></span>
                        <b>Website Profile</b>
                    </div>

                    <span class="status-text"></span>
                </div>
            </div>    
        </div>
    </div>
</div>