<?php

namespace App\Helpers;

class CookieHelper
{
    public readonly array $cookies;
    public function __construct()
    {
        $this->cookies = [
            [
                'Name' => 'AF_SYNC',
                'Value' => '1736988620379',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2025-01-23T00:50:20.000Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => 'JSESSIONID',
                'Value' => 'B5267E8A8DD8ED1EAE900C8634BB356E.mono-web-prod_192.122',
                'Domain' => 'www.encar.com',
                'Path' => '/',
                'Expires' => null, // Сессионная кука
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => 'OAX',
                'Value' => 'nvfgOmeIXVIAAJkn',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2026-02-20T01:13:54.151Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => 'PCID',
                'Value' => '17369900311250407341545',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2026-02-20T05:25:04.651Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => 'RecentViewAllCar',
                'Value' => '38542378%2C38549243%2C38662391',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2025-02-15T03:42:19.000Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => 'RecentViewCar',
                'Value' => '38542378%2C38549243%2C38662391',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2025-02-15T03:42:19.000Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => 'RecentViewTruck',
                'Value' => '',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2025-02-15T03:42:19.000Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => '_encar_hostname',
                'Value' => 'http://www.encar.com',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2025-01-17T05:25:02.000Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => '_enlog_lpi',
                'Value' => 'd038.aHR0cDovL3d3dy5lbmNhci5jb20vZGMvZGNfY2Fyc2VhcmNobGlzdC5kbz9jYXJUeXBlPWtvciMhJTdCJTIyYWN0aW9uJTIyJTNBJTIyKEFuZC5IaWRkZW4uTi5fLkNhclR5cGUuWS4pJTIyJTJDJTIydG9nZ2xlJTIyJTNBJTdCJTdEJTJDJTIybGF5ZXIlMjIlM0ElMjIlMjIlMkMlMjJzb3J0JTIyJTNBJTIyTW9kaWZpZWREYXRlJTIyJTJDJTIycGFnZSUyMiUzQTElMkMlMjJsaW1pdCUyMiUzQTIwJTJDJTIyc2VhcmNoS2V5JTIyJTNBJTIyJTIyJTJDJTIybG9naW5DaGVjayUyMiUzQWZhbHNlJTdE.a44',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2025-01-16T05:55:04.000Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => '_fbp',
                'Value' => 'fb.1.1736990035303.807071331395634042',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2025-04-16T05:25:09.000Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => 'Lax'
            ],
            [
                'Name' => '_fwb',
                'Value' => '528EJtIOMMD5ozNxxduIPA.1736990029781',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2026-02-20T05:25:03.906Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => '_ga',
                'Value' => 'GA1.2.2028116001.1736990031',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2026-02-20T05:25:06.722Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => '_ga_WY0RWR65ED',
                'Value' => 'GS1.2.1737005108.4.1.1737005108.0.0.0',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2026-02-20T05:25:08.308Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => '_gat_UA-56065139-3',
                'Value' => '1',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2025-01-16T05:26:06.000Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => '_gcl_au',
                'Value' => '1.1.1966906391.1736988616',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2025-04-16T00:50:16.000Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => '_gid',
                'Value' => 'GA1.2.988054870.1736990031',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2025-01-17T05:25:06.000Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => 'afUserId',
                'Value' => '852cb84d-4f88-4eee-85a6-bf5e0e4860d1-p',
                'Domain' => '.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2026-02-20T03:42:21.074Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ],
            [
                'Name' => 'wcs_bt',
                'Value' => '4b4e532670e38c:1737005103',
                'Domain' => 'www.encar.com',
                'Path' => '/',
                'Expires' => strtotime('2026-02-20T05:25:03.908Z'),
                'Secure' => false,
                'HttpOnly' => false,
                'SameSite' => ''
            ]
        ];
    }
}

