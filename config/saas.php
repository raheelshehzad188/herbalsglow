<?php

return [

    /*
    | Primary marketing / control-plane host (no merchant storefront).
    | www and bare host are treated as the same site.
    */
    'primary_domain' => env('SAAS_PRIMARY_DOMAIN', ''),

    /*
    | Hosts that keep the local first-store fallback and may open /platform
    | and /superadmin even when SAAS_PRIMARY_DOMAIN is set.
    */
    'local_hosts' => env('SAAS_LOCAL_HOSTS', 'localhost,127.0.0.1,::1'),

];
