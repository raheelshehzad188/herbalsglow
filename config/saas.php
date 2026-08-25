<?php

return [

    /*
    | Primary marketing / control-plane host (no merchant storefront).
    | Local default: platform.herbalsglow.test
    | On the live server set SAAS_PRIMARY_DOMAIN to your real domain (e.g. salesground.ai).
    */
    'primary_domain' => env('SAAS_PRIMARY_DOMAIN', 'platform.herbalsglow.test'),

    /*
    | Hosts that keep the local first-store fallback and may open /platform
    | and /superadmin even when SAAS_PRIMARY_DOMAIN is set.
    */
    'local_hosts' => env('SAAS_LOCAL_HOSTS', 'localhost,127.0.0.1,::1'),

];
