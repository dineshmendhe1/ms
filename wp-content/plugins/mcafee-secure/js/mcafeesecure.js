jQuery(function(){
    var $activationSection = jQuery("#mcafeesecure-activation");
    var $dashboardSection = jQuery("#mcafeesecure-dashboard");
    var $data = jQuery("#mcafeesecure-data");

    var host = $data.attr('data-host');
    if(!host){ host = '';}

    var email = $data.attr('data-email');
    if(!email){ email = '';}

    var endpointUrl = 'https://www.mcafeesecure.com';
    var apiUrl = endpointUrl + '/rpc/ajax?do=lookup-site-status&jsoncallback=?&rand='+new Date().getTime()+'&host=' + encodeURIComponent(host)
    var loginUrl = endpointUrl + "/login";
    var certificationTrustmarkUrl = endpointUrl + "/user/site/trustmark";
    var upgradeUrl = endpointUrl + "/user/upgrade";
    var sitemapUrl = endpointUrl + "/user/site/sitemap";
    var diagnosticsUrl = endpointUrl + "/user/site/diagnostics";
    var profileUrl = endpointUrl + "/user/site/directory";

    jQuery("#activate-now").click(function(){
        var left = window.innerWidth / 2 - 250;
        var top = 200;
        var signupUrl = endpointUrl + "/app/partner2/signup?ctx=popup&host=" + encodeURIComponent(host) + "&email=" + encodeURIComponent(email) + "&aff=221269";
        var signupWindow = window.open(signupUrl, "_blank", "width=900 height=700 left=" + left + " top=" + top);
    });

    // McAfee SECURE
    function renderSecurity(data){
        var issuesFound = data['diagnosticsFoundIssues'] === 1;
        var $row = jQuery("#security");
        var secure = data['isSecure'] === 1
        var inProgress = data['scanInProgress'] === 1

        if(inProgress){
            setStatusText($row, "Security Scan In Progress." );
            spinIcon($row);
        }else{
            if(secure){
                setStatusText($row, "All tests passed!" );
                checkIcon($row);
            }else{
                setStatusText($row, "Security Problems!");
                timesIcon($row);
            }   
        }

        setLinkText($row, "View Details");
        setLinkHref($row, loginUrl);
    }

    function renderCertificationTrustmark(data){
        var pro = data['isPro'] === 1;
        var $row = jQuery("#certification");
        var exceeded = data['maxHitsExceeded'] === 1;

        if(pro){
            setStatusText($row, "All good.");
            setLinkText($row, "View Details");

            checkIcon($row);
            setLinkHref($row, certificationTrustmarkUrl);
        }else{
            if(exceeded){
                setStatusText($row, "Monthly Visitor Limit Reached.");
                timesIcon($row);
            }else{
                setStatusText($row, "All good.");
                checkIcon($row);
            }

            setLinkText($row, "Go Unlimited");
            setLinkHref($row, upgradeUrl);
        }
    }

    function renderSearchHighlighting(data){
        var pro = data['isPro'] === 1;
        var $row = jQuery("#highlighting");

        if(pro){
            setStatusText($row, "Site is highlighted in Google, Yahoo, and Bing search results.");
            checkIcon($row);
            setLinkText($row, "View Details");
            setLinkHref($row, loginUrl);
        }else{
            showPillGrey($row);
            setStatusText($row, "Enable highlighting in Google, Yahoo, and Bing search results.");
            timesIcon($row);

            setLinkText($row, "Learn More");
            setLinkHref($row, upgradeUrl);
        }
    }

    function renderEngagementTurstmark(data){
        var pro = data['isPro'] === 1;
        var $row = jQuery("#engagement");

        if(pro){
            var installed = data['tmEngagementInstalled'] === 1;

            if(installed){
                setStatusText($row, "Active");
                checkIcon($row);

                setLinkText($row, "View Details");
            }else{
                setStatusText($row, "Not Installed");
                timesIcon($row);

                setLinkText($row, "Install Now");
            }

            setLinkHref($row, certificationTrustmarkUrl);
        }else{
            showPillGrey($row);
            setStatusText($row, "Enable the engagement trustmark to boost visitor confidence.");
            timesIcon($row);

            setLinkText($row, "Learn More");
            setLinkHref($row, upgradeUrl);
        }
    }

    function renderSip(data) {
        var pro = data['isPro'] === 1;
        var $row = jQuery("#sip");

        if(pro){
            var sipEnabled= data['sipEnabled'] === 1;

            if(sipEnabled){
                setStatusText($row, "Active");
                checkIcon($row);
                setLinkText($row, "View Details");
            }else{
                setStatusText($row, "Inactive");
                timesIcon($row);
                setLinkText($row, "Enable");
            }
        }else{
            showPillGrey($row);
            setStatusText($row, "Enable Shopper Identity Protection and protect your customers.");
            timesIcon($row);
            setLinkText($row, "Learn More");
            setLinkHref($row, upgradeUrl);
        }
    }

    // TrustedSite
    function renderSiteReviews(data){
        var $row = jQuery("#reviews");
        var $reviewStatus = $row.find('.review-status');
        var ratingCount = parseInt(data['tsRatingCount'])
        var ratingAverage = parseFloat(data['tsRatingAverage']);

        if(ratingCount <= 0){
            $reviewStatus.html('<div class="dot-yellow"></div> No Reviews');
        }else{
            var $rs = jQuery('<div class="review-stars" style="vertical-align:middle;display:inline-block;">' + reviewStars(ratingAverage) + ' </div>');
            var $rc = jQuery('<div class="small-grey rating-count" style="vertical-align:middle;line-height:25px;height:25px;display:inline-block;"></div>');
        
            $rs.html(reviewStars(ratingAverage));
            $rc.html(ratingCount + " ratings");

            $reviewStatus.html('<div class="review-stars" style="vertical-align:middle;display:inline-block;">' + reviewStars(ratingAverage) + ' </div>&nbsp;&nbsp;<div class="small-grey rating-count" style="vertical-align:middle;line-height:25px;height:25px;display:inline-block;">' + ratingCount + ' ratings </div>');
        }

        setLinkText($row, "View Details");
        setLinkHref($row, loginUrl);
    }

    function renderPurchaseReviewEmail(data){
        var $row = jQuery("#purchase-review-email");
        var pro = data['isPro'] === 1;

        if(pro){
            checkIcon($row);
            setLinkText($row, "View Details");
            setLinkHref($row, loginUrl);
        }else{
            showPillGrey($row);
            timesIcon($row);
            setStatusText($row, "Automatically requests customers to leave a review after they buy.");
            setLinkText($row, "Enable Now");
            setLinkHref($row, loginUrl);
        }
    }

    function renderSitemap(data){
        var pro = data['isPro'] === 1;
        var inProgress = pro && !data['sitemapCreatedDate'];
        var $row = jQuery("#sitemap");
        
        if(pro){

            if(inProgress){
                setStatusText($row, 'Indexing Website In Progress');
            }else{
                var createdDate = data['sitemapCreatedDate'];
                var numPages = data['sitemapUrlCount'];

                if(createdDate){
                    var dateStr = createdDate.split(" ")[0];
                    var parts = dateStr.split("-");

                    var d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                    var start = d.getTime();
                    var now = (new Date()).getTime();
                    var daysAgo = Math.floor((now - start)/1000/60/60/24);

                    setStatusText($row, 'Created <b class="days-ago">' + daysAgo + ' days ago</b>.  Contains <b class="num-pages">' + numPages + '</b> pages.');
                }
            }

            checkIcon($row);

            setLinkText($row, "View Details");
            setLinkHref($row, sitemapUrl);

        }else{
            showPillGrey($row);
            setStatusText($row, "Get your site in Google, Yahoo, Bing and more.");
            timesIcon($row);

            setLinkText($row, "Learn More");
            setLinkHref($row, upgradeUrl);
        }
    }

    function renderDiagnostic(data){
        var issuesFound = data['diagnosticsFoundIssues'] === 1;
        var $row = jQuery("#diagnostics");
        var pro = data['isPro'] === 1;

        if(pro){
            if(issuesFound){
                setStatusText($row, "Issues found.");
                timesIcon($row);
            }else{
                setStatusText($row, "All good.");
                checkIcon($row);
            }

            setLinkText($row, "View Details");
            setLinkHref($row, diagnosticsUrl);

        }else{
            showPillGrey($row);
            setStatusText($row, "Keep your site looking flawless.");
            timesIcon($row);

            setLinkText($row, "Learn More");
            setLinkHref($row, upgradeUrl);
        }
    }

    function renderProfile(data){
        var profileDone = data['profileDone'] === 1;
        var $row = jQuery("#profile");

        if(profileDone){
            setStatusText($row, "All good.");
            checkIcon($row);
        }else{
            setStatusText($row, "Complete your profile today.");
            warningIcon($row);
        }

        setLinkText($row, "Edit Profile");
        setLinkHref($row, profileUrl);
    }

    function setStatusText($el, statusText){
        $el.find(".status-text").html(statusText);
    }

    function setLinkText($el, linkText){
        $el.find(".link").html(linkText);   
    }

    function setLinkHref($el, href){
        $el.find(".link").attr('href', href);
    }

    function showPillGrey($el){
        $el.find(".pill-grey").show();
    }

    function hidePillGrey($el){
        $el.find(".pill-grey").hide();   
    }

    function checkIcon($el){
        $el.find('.status-icon').html('<i class="fa fa-check"></i>');
    }

    function timesIcon($el){
        $el.find('.status-icon').html('<i class="fa fa-times"></i>');
    }

    function warningIcon($el){
        $el.find('.status-icon').html('<i class="fa fa-warning"></i>');
    }

    function spinIcon($el){
        $el.find('.status-icon').html('<i class="fa fa-circle-o-notch fa-spin"></i>');
    }

    function reviewStars(v){
        var s = "";
        for(var i = 1; i <= 5; i++) {
            var c = "fa-star";
            if(v < i){c = "fa-star-o";} 
            if(v + 0.5 === i){
              c = "fa-star-half-o";  
            } 
            s += "<i data-value=\""+i+"\" class=\"fa "+c+"\"></i>";
        }
        return s;
    }

    function refresh(){
        jQuery.getJSON(apiUrl,function(data) {
            console.log("lookup-site-status");
            console.log(data);

            var status = data['status'];

            if(status === 'none'){
                $activationSection.show();
            }else{
                setTimeout(function(){
                    clearInterval(refreshInterval);
                    loadDashboard();
                }, 500);
            }
        });
    }

    function loadDashboard(){
        jQuery.getJSON(apiUrl,function(data) {
            renderSecurity(data);
            renderCertificationTrustmark(data);
            renderSearchHighlighting(data);
            renderEngagementTurstmark(data);

            renderSiteReviews(data);
            renderPurchaseReviewEmail(data);
            renderSitemap(data);
            renderDiagnostic(data);
            renderProfile(data);
            renderSip(data);

            $activationSection.hide();
            $dashboardSection.show();
        });
    }

    var refreshInterval = setInterval(refresh, 1000);
    refresh();
});