<center><h4><strong>SAAS ECOMMERCE SINGLE VENDOR</strong></h4></center>
<p>There is three roles(with Permissions) and 1 Customer with SSLCOMMERZ Payment System</p>
<br>
![Screenshot](backend/images/screenshots/SAAS_SV_ECOMMERCE.png)
<br>
<p>
<b><u>Management</u></b> <br>
#	Management (Admin Panel)<br>
- Super Admin<br>
- Manager<br>
- Editor<br>
#	Customer Panel<br>
- Customer<br>
<br>
<b><u>Features</b></u><br>
#	Realtime Dashboard : Laravel Reverb as Web Socket (Update On New Order Place)<br>
#	Role and Permissions : Spatie/Permission (User For Admin Management Team)<br>
#	Payment Method : SSL Commerz (Api WebView Link for Payment)<br>
#	Authentication : Laravel JetStream (Web) and JWT Token(Api)<br>
Socialite(Api Only as a WebView)<br>
#	Import : Product Imported from Excel or CSV[*CSV Tested!] <br>
#   Filter : Product Fileter with Different Criteria <br>
#   SMS Integration : SMS Gateway Intergrated <br>
<br>

#Admin Panel <br>
admin@rana.my.id <br>
admin1122<br>

#Manager Panel <br>
manager@rana.my.id <br>
manager1122<br>

#Editor Panel <br>
editor@rana.my.id <br>
editor1122<br>



</p>
<br>
<p style="font-color:red">Live Preview(Without Realtime), LocalHost works perfectly
Please Modify this value, Using your data(.env) <br>
 <br>
SSLCZ_STORE_ID=xxxxxxxxxxxxxxxxxx <br>
SSLCZ_STORE_PASSWORD=xxxxxxxxxxxxxxxxxx <br>
SSLCZ_TESTMODE=true 

JWT_SECRET=xxxxxxxxxxxxxxxxxx <br>


REVERB_APP_ID=xxxxxxxxxxxxxxxxxx <br>
REVERB_APP_KEY=xxxxxxxxxxxxxxxxxx <br>
REVERB_APP_SECRET=xxxxxxxxxxxxxxxxxx <br>
REVERB_HOST="localhost" <br>
REVERB_PORT=8080 <br>
REVERB_SCHEME=http <br>

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}" <br>
VITE_REVERB_HOST="${REVERB_HOST}" <br>
VITE_REVERB_PORT="${REVERB_PORT}" <br>
VITE_REVERB_SCHEME="${REVERB_SCHEME}" <br>

GOOGLE_CLIENT_ID=xxxxxxxxxxxxxxxxxx <br>
GOOGLE_CLIENT_SECRET=xxxxxxxxxxxxxxxxxx <br>
GOOGLE_REDIRECT_URL="http://127.0.0.1:8000/api/v1/auth/google/callback" <br>

SMS_URL =xxxxxxxxxxxxxxxxxx <br>
SMS_SENDER_ID =xxxxxxxxxxxxxxxxxx <br>
SMS_API_KEY =xxxxxxxxxxxxxxxxxxxx <br>
<br><br>
</p>



<br>
<br>
Thanks,<br>
<a href="https://rana.my.id">Rana Bepari</a>

