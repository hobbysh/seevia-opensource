opauth-qq
=========
Opauth strategy for QQ authentication.

Getting started
----------------
0. Make sure your website installation supports UTF-8

1. Install Opauth-QQ:
   ```bash
   cd path_to_opauth/Strategy
   git clone http://connect.qq.com QQ
   ```
2. Create QQ Weibo application at http://connect.qq.com
	 - It is a web application
	 - Callback: http://path_to_opauth/qq_callback

3. Configure Opauth-QQWeibo strategy with `key` and `secret`.

4. Direct user to `http://path_to_opauth/qq` to authenticate

Strategy configuration
----------------------

Required parameters:

```php
<?php
'QQ' => array(
	'key' => 'YOUR APP KEY',
	'secret' => 'YOUR APP SECRET'
)
```

License
---------
Opauth-QQ is MIT Licensed  
