=== OTP and Passwords for Google Authenticator, McAfee, DS3 ... ===
Contributors: s-crib
Tags: security, login, authentication, sCrib, S-CRIB, Smart Crib, otp,  oath, hotp, google, authenticator, google authenticator, McAfee
Requires at least: 3.0
Tested up to: 3.9.0
Stable tag: 2.1.2
License: GPLv3
Plugin URI: http://www.w-crib.com/wpotp/
Author URI: http://www.linkedin.com/in/dancvrcek
Donate Link: http://www.s-crib.com/buy/

Easy secure login, use password or OTP as you need. Works with Smart Crib dongles and free apps: Google Authenticator, Pledge, DS3 OATH, AuthWay Token. 

== Description ==

From version 2.0, we support TOTP (Time OTP) as well as HOTP (Event/counter OTP) versions of one-time passwords. The plugin also enforces a password policy for short or weak passwords (based on real hacking attacks).


<b>SUPPORTED HARDWARE TOKENS</B>

1. Password S-CRIB (<a href="https://www.s-crib.com/buy-channel/">Smart Crib</a>).
1. Yubikey (<a href="http://www.yubico.com/products/yubikey-hardware/yubikey/">Yubico</a>).
1. ... let us know which you'd like us to test.

<b>SUPPORTED SOFTWARE TOKENS / APPS</B>

1. <b>Google Authenticator</b> (<a href="https://itunes.apple.com/en/app/google-authenticator/id388497605?mt=8">iTunes</a>).
1. <b>McAfee Pledge (by Nordic Edge AB)</b> (<a href="http://www.mcafee.com/us/products/one-time-password.aspx">iTunes</a>, <a href="http://www.mcafee.com/us/products/one-time-password.aspx#vt=vtab-Methodology">McAfee</a>).
1. <b>DS3 OATH</b> (<a href="https://itunes.apple.com/us/app/ds3-oath/id371260838?mt=8">iTunes</a>, <a href="https://play.google.com/store/apps/details?id=com.dsss">Android Apps</a>, <a href="http://ds3global.com/index.php/en/news-a-events/news/89-securing-data-delivery-hassle-free-">DS3 Global</a>).
1. <b>AuthWay Token</b> (<a href="https://itunes.apple.com/en/app/authway-token/id422831507?mt=8">iTunes</a>).
1. <b>OTP (by GMB eyeT Ltd)</b> (<a href="https://itunes.apple.com/au/app/otp/id539922062?mt=8">iTunes</a>).
1. <b>HDE OTP</b> (<a href="https://itunes.apple.com/us/app/hde-otp-generator/id571240327?mt=8">iTunes</a>).
1. <b>OTP Auth</b> (<a href="https://itunes.apple.com/us/app/otp-auth/id659877384?mt=8">iTunes</a>).
1. .... let us know if you want us to test any other.

"OTP and Passwords" for WordPress works with Smart Crib (Password S-CRIB), Google Authenticator, Pledge (McAfee), DS3 OATH, AuthWay Token, and other OTP generators. It creates QR codes (Google Authenticator or Pledge) and also allows to type OTP secrets manually. 

"OTP and Passwords" introduces one time password (OTP) authentication into Wordpress (we now support counter/event mode as well as time-based OTP according to standard OATH). The plugin has been design for use with Password S-CRIB dongles but we also tested it with Google Authenticator (iOS, Android), Pledge (iOS, Android), DS3 OATH (iOS, Android), and AuthWay Token. It is compliant with RFC4226 and RFC 6238 when the OTP code can be 6, 7, or 8 digits long.

You can enable OTP on your account through "Edit My Profile" page (accessible from top right corner when logged in). Administrators can do the same for other users by selecting their names from from the list of users  

You can set OTP secret as well as PIN - highly recommended as the minimum length of OTP codes should be 10 digits! When PIN is set, users get a full 2 factor authentication. The PIN can be any string of up to 32 characters - so you can use a password as the PIN.

To login, just enter your PIN (if set) and OTP code into the password box. Your previous static password will still work so you can use it to login if/when you want.

OTP login will require an additional OTP code if there were 5 (6-digit OTP) or 10 (7 and 8 digit OTP) unsuccessful tries. This is a new policy replacing timeouts. It turns out that the internet is indeed a toxic place and OTP authentication got locked-down way too often.

You can purchase Password S-CRIB from <a href="http://www.s-crib.com/buy-channel/">Amazon UK (+26 EU countries), Amazon US or Paypal</a>.

== Installation ==

Either 

1. Log into WordPress as na administrator.
1. Select Plugins -> Add New in the menu on the left.
1. Search for "s-crib otp".
1. Find "OTP and Passwords".
1. Select "Install Now" and wait for installation to complete.
1. Select "Activate".
1. Click on Settings --> OTP and Passwords and check that there is no problem with random number (RND) generator. Let us know if this is the case and please mention your platform.
1. To associate a token with a user, click on "Users" (users can do that on their own via "Edit My Profile", under their username in the top right corner).
1. Click "Edit" next to a user.
1. Follow instructions below "To change your OTP generator".

OR (using a zip file)

1. Download the "OTP and Passwords" for WordPress package.
1. Extract the ZIP into ~/wp-content/plugins.
1. Log into WordPress as an administrator.
1. In the Site Administration section go to "Plugins".
1. Activate the plugin "OTP and Passwords".
1. Click on Settings --> OTP and Passwords and check that there is no problem with random number (RND) generator. Let us know if this is the case and please mention your platform.
1. To associate a token with a user, click on "Users" (users can do that on their own via "Edit My Profile", under their username in the top right corner).
1. Click "Edit" next to a user.
1. Follow instructions below "To change your OTP generator".

Any users who do not have a token specified will just use their standard username and password to log into WordPress.


== Frequently Asked Questions ==

= List of Supported Tokens (software and hardware) =

We have tested our plugin with the following iPhone apps. Some of them have implementations for Android as well. We will keep adding new as we test, or receive feedback from users.

<b>HARDWARE TOKENS</B>

1. Password S-CRIB (<a href="https://www.s-crib.com/buy-channel/">S-CRIB</a>) - HOTP only, it generates a secret and types it into your Wordpress profile.
1. Yubikey (<a href="http://www.yubico.com/products/yubikey-hardware/yubikey/">Yubico</a>) - HOTP only, once reprogrammed, it generates a secret for your Wordpress profile. 

<b>SOFTWARE TOKENS / APPS</B>

1. <b>Google Authenticator</b> (<a href="https://itunes.apple.com/en/app/google-authenticator/id388497605?mt=8">iTunes</a>) - HOTP and TOTP, it needs to scan QR code from your Wordpress profile.
1. <b>McAfee Pledge (by Nordic Edge AB)</b> (<a href="http://www.mcafee.com/us/products/one-time-password.aspx">iTunes</a>, <a href="http://www.mcafee.com/us/products/one-time-password.aspx#vt=vtab-Methodology">McAfee</a>) - HOTP and TOTP, it needs to scan QR code from your Wordpress profile (click on camera when creating new profile).
1. <b>DS3 OATH</b> (<a href="https://itunes.apple.com/us/app/ds3-oath/id371260838?mt=8">iTunes</a>, <a href="https://play.google.com/store/apps/details?id=com.dsss">Android Apps</a>, <a href="http://ds3global.com/index.php/en/news-a-events/news/89-securing-data-delivery-hassle-free-">DS3 Global</a>) - it creates a secret that has to be typed / copied to your Wordpress profile.
1. <b>AuthWay Token</b> (<a href="https://itunes.apple.com/en/app/authway-token/id422831507?mt=8">iTunes</a>) - TOTP and HOTP. One has to type a hexadecimal string for secret, or copy a generated one into your Wordpress profile. You then have to select whether TOTP or HOTP and also can choose the length of codes.
1. <b>OTP (by GMB eyeT Ltd)</b> (<a href="https://itunes.apple.com/au/app/otp/id539922062?mt=8">iTunes</a>) - TOTP and HOTP, secret must be typed from your Wordpress profile, one can choose the length of codes.
1. <b>HDE OTP</b> (<a href="https://itunes.apple.com/us/app/hde-otp-generator/id571240327?mt=8">iTunes</a>) - TOTP only, QR code scanned from your Wordpress Profile.
1. <b>OTP Auth</b> (<a href="https://itunes.apple.com/us/app/otp-auth/id659877384?mt=8">iTunes</a>) - TOTP and HOTP, secret typed as hexadecmal string or scanned from QR code.

= Why Should I use this plugin for OTP? =

1. We made it very simple to use, when you're at home, you can still use your password only, when you're travelling, you can use OTP.
1. We do not require password AND OTP as we believe it is overkill and it is much better to use OTP with a PIN.
1. The plugin was tested with Google Authenticator but it works with any event based OTP generator (HOTP OATH).
1. The plugin was tested with Google Authenticator but it works with any time based OTP generator (TOTP OATH)
1. We encrypt OTP secrets to make it more difficult for attackers to fully compromise OTP authentication.
1. When you go to your Profile (Edit My Profile), you can see an IP address of the last unsuccessful login - this gives a bit of information about someone's possibly trying to hack into your Wordpress account.
1. The plugin now implements event-based as well as time-based OTP. (see FAQs for a bit more about this).

= Mobile phone apps that work with "OTP and Passwords" =

We have not done extensive testing but we have scanned about 160 applications one can find in AppStore. The following four apps work with this plugin:

* Google Authenticator (Google)
* Pledge (McAfee)
* DS3 OATH (DS3 - Data Security Systems Solutions)
* AuthWay Token (oathtoken.googlecode.com)
* OTP (by GMB eyeT Ltd)
* HDE OTP
* OTP Auth

= Random Number Generator - Why is it important? =

You want your OTP codes to be as unpredictable as possible. That is why we try to use a cryptographically secure random number generator. If this does not work then we use a number of time measurements - microsecond timer provided by the operating system. 

This approach is different from many other OTP plugins who use insecure random number generators like rand() or mt_rand().

= Why does my profile page opens slowly? =

Our plugin will generate a new random secret each time you open your profile. This may slow the page opening by 3 seconds.

= Can I still use my old password? =

Yes, the plugin does not disable static password. If you decide not to use normal passwords any more, set them to some random and long values.

= Just one password for OTP - is it secure? =

We have noticed that existing plugins basically add an additional password box when you start using OTP. It means that you need your old password and OTP codes as well. I have put some security arguments on the plugin web page: <a href="http://www.s-crib.com/wpotp">WP OTP from Smart Crib</a> and here is a summary:

1. Even 6 digit OTP is as strong an average password - based on a large study of Yahoo passwords conducted by Joseph Bonneau at the University of Cambridge (<a http://www.cl.cam.ac.uk/~jcb82/doc/B12-IEEESP-analyzing_70M_anonymized_passwords.pdf>Analyzing 70,000,000 anonymized passwords</a>) - see Figure 6 (success rate with increasing number of guesses and Table V for just 1,000 guesses). 

1. While 6 digit OTP is as strong as an average password, it is actually much more secure as once an OTP value is guessed, it cannot be re-used. This makes attacks more complicated as the tool for guessing passwords must also apply the "payload".

1. Many of you may think that OTP is overkill. Well, it may be true when you login from your "secure" network but we sometimes really need to login from insecure network and if you can just use OTP when you need it, it justifies the additional hassle.

= What if someone hacks my server, is OTP still secure? =

Actually, OTP is more vulnerable than passwords when your server gets hacked. The problem is that while passwords can be scrambled (only values computed with one-way function like SHA1, SHA256, ... are stored), OTP secrets must be available in plaintext.

Once the secret is compromised, all future values of your OTP codes can be computed by the attacker - you may remember RSA SecurID incident a couple of years back - one article from many: <a href="http://arstechnica.com/security/2011/06/rsa-finally-comes-clean-securid-is-compromised/">RSA Finally Comes Clean - SecurID Is Compromised</a>.

Our plugin encrypts seeds with AES-256 so they cannot be read directly but the key is still stored somewhere on our server. We hope to improve this with our management and monitoring system that we intend to launch in Q3 2013.

= Sure, OTP is more secure but there is still a chance of being hacked =

That is very true. There are so many Wordpress users that there is a good chance of someone getting hacked every day. One needs monitoring and auditing system to detect attempts to hack someone's account.

We have been building such system for some time now and we hope to launch it in Q3 2013. This system will also offer additional security improvements. We want to use cryptographic modules like this (<a href="http://hsm.utimaco.com/en/products/product-release-safeguard-cryptoserver-lan-v4/">Utimaco CryptoServer V4</a> ) to store your secrets. 

= OTP - Time based or Event based? =

OTP codes can be computed using an incrementing counter or real time. Each of them has its advantages and disadvantages. We decided to do Event-based OTP (aka Counter-based aka HOTP) first. Our reasons were:
1. Event based OTP does not require synchronised time between your mobile phone and server - this may be sometimes quite difficult to do as you may not be the master of the server where your Wordpress is running.
2. Time based OTP assumes trusted time source on both sides. However, it is fairly easy to change time on your mobile phone and write down OTP codes that will be valid in an hour or next day. 
3. There is a danger of Event-based OTP codes to be eavesdropped and used by attackers - from your point of view it looks like you can't login to your server and get a message like "Server Is Down", "Try Again Later", and so on.

Version 2.0 implements time-based OTP as well.

Neither option is better - Time-based may be a bit more secure (sometime), Event-based is a little bit easier to use (other time). If you are new to OTP, try this one first as it is easier and more reliable.


= Why do we promote Password S-CRIB? =

Password S-CRIB is our product. It is a physical dongle that gives you OTP and a set of 4 secure passwords. Forget what I said about passwords above as the strength of these makes them unbreakable. 

In some situations, these passwords are much better than OTP - probability of guessing an 8 digit OTP with 4 digit PIN is 1:2^40, while for these passwords it is 1:2^120  (each increment between 40 and 120 means trying twice as many passwords).

Password S-CRIB is a digital key you have in your pocket with powerful password recovery support options - from a hardcopy in your strongbox to an online recovery and management system.

= Where can I get a Password S-CRIB dongle? =

Tokens can be obtained from <a href="http://www.s-crib.com/buy/">Amazon UK (+26 EU countries), Amazon US, and Paypal</a> or get in touch via email to sales@s-crib.com as we can offer substantial discounts if you buy more of them.

= I need more help! =

If you get stuck, you can always contact us at support@s-crib.com.

== Screenshots ==

1. Sample login page
2. Configuration in the user profile page
3. Warning when an additional OTP code is required

== Changelog ==

= 1.0 =

* Initial release - testes with Password S-CRIB and Google Authenticator, supports HOTP OATH wit lengths of 6, 7, and 8. Secrets are stored encrypted with a unique key and AES256. Secrets can be entered as hex strings or scanned from QR codes.

= 1.1 =

* Fixing a typo in a string formatting and the maximum length of the OTP secret (now it is indeed 64 bytes).
* Showing the secret encoded in the QR code so that it can be typed to a mobile phone app - base32 and hex formats.
* User ID in the QR code now shows login name and URL of the blog.

= 1.2 =

* Removing the length of OTP codes - this is now computed automatically from the first OTP code.
* Adding a couple of cryptographic keys for future security monitoring.
* Extending database for new token types - TOTP and MOTP.
* Fixing a bug where the version is not correctly stored in the WP options.

= 1.3 =

* No actual changes to the code.

= 1.4 =

* The internet is indeed a toxic place. The timeout policy did not work as it locked-out accounts far too often. A new policy has been implemented:
* When a password is shorter than 7 characters or on the list of weak passwords (weak_passwords.txt), user has to enter it twice.
* When there have been at least 5 (for 6 digit OTP) or 10 (7 & 8 digit OTP) unsuccessful login attempts, users have to enter additional OTP code.

= 1.5 =

* When entering an additional OTP code, it would accepted even when the PIN is missing.

= 2.0 =

* Simplifying the administration form.
* Update of weak passwords.
* Introducing TOTP.
* Enforcing the minimum length of the secret to 80 bits (common is 160 bits, but Google uses 80 bits only).
* Window (the number of acceptable values around expected time <-D,+D> / counter <0,D>) is set to D=2 for OTP long 6 digits and D=4 for OTP long 8 digits.
* We attempt to identify the size of the time window for TOTP from values 30 seconds, 60 seconds, and 90 seconds.
* If OTP is left empty (in the administration form), only PIN and counter are updated.
* Fixing bug and enabling editing of the counter for HOTP.

= 2.0.1 =

* Updating database queries to get rid of PHP warnings.
* Update of screen shots.

= 2.0.2 =

* Renaming to makes sense as the password policy has been changed.
* A bit of code cleaning.

= 2.1 =

* Improvement of logging to troubleshoot install / update problems.
* Adding contact / support information in the plugin's settings page.
* Fixing a bug for plugin update (previously not called without activation).
* Various code cleaning.

= 2.1.1 =

* Added software RNG generator to resolve problems with PRNGs provided by operating systems.
* Added a new module for SHA256 computation.
* The plugin tries to send installation logs to our server for future support requests.
* Experimental push of encryption keys to plugin instances - test for new future services.

= 2.1.2 =

* Got rid of "1 unexpected character read" warning.
* Fixed issues with RNG, when no hardware random generator is not available.
* Improved use of RNG in Windows.
* Thank you all for feedback with problems!

== Upgrade Notice ==

Version 1.2 updates the plugin's database table if an older version is installed. 

