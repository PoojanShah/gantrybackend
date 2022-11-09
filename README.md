## How to star up an application locally
docker-compose -f docker-compose.dev.yaml up -d </br></br>
**Pay attention - port 80 and 3306 should not be busy!**</br></br>
<b>Then execute following commands:</b></br></br>
docker exec app composer install</br>
docker exec app php artisan migrate</br>
docker exec app php artisan db:seed</br>
## Login to admin panel
Go to http://localhost/login </br>
Use following credentials 
**email** - k.makienko1990@gmail.com, **password** - 12345
## Connection to database 
Host - 0.0.0.0</br>
User - mysql-laravel</br>
Port  - 3306</br>
Database - laravel</br>
DB password - 9AYp9wiW7xoYPJWP

## Sync with ZOHO
<ul>
    <li>
        <h2>Hooks</h2>
        <p>
            Go to https://subscriptions.zoho.com/app/ -> Settings -> Automation  ->  Hooks. </br>
            There should be two hooks every of them should have URL leading to https://api.comfort-health.net/api/subscriptions </br>
            Also every of hooks has to have Zoho-Authorization-Token header. Value has to be equal to ZOHO_TOKEN from .env
        </p>
    </li>
    <li>
        <h2>Plans</h2>
        <p>
            Every plan has unique plan_code. We use only code from "All inclusive" plan.
            If you deleted this plan then you should create new one, and specify  its plan_code in ZOHO_ALL_INCLUSIVE_PLAN_CODE variable from .env</br>
            There should be two hooks every of them should have URL leading to https://api.comfort-health.net/api/subscriptions </br>
            Also every of hooks has to have Zoho-Authorization-Token header. Value has to be equal to ZOHO_TOKEN from .env
        </p>
    </li>
    <li>
        <h2>Addons / Media</h2>
        <p>
            Every Media in admin panel of this app has addon_code field. Value of this field has to be taken from Addon in Zoho.
            Otherwise web-hooks worn't work properly. And api get no sync with zoho!
        </p>
    </li>
     <li>
        <h2>ZOHO API Tokens</h2>
        <p>
            Zoho documentation of this section is not clear. So Ill try to explain everything in a nut shell.  </br>
            Zoho uses  oauth 2.0. https://www.zoho.com/subscriptions/api/v1/oauth/#overview </br>
            So it requires users of our admin panel to have an account in Zoho. They basically need to press "allow" button from zoho website and then api access token will be created.  </br>
            This approach doesnt suit our purposes. So we decided to go with stupid way based on storing and using of Refresh token
            Once token and refresh token received we store refresh token in database and use it whe access token gets expired. </br>
            <b>Refresh token never expires!!!</b> </br>
            We also created a page for refresh token updating ( /admin/tokens ). It should be used for the first start of application. </br>
        </p>
    </li>
 <li>
        <h2>ZOHO Subscription creation</h2>
        <p>
            ZOHO doesnt wait till payment gt processed!!! It makes subscription live once you created it!!!    
        </p>
    </li>
</ul>