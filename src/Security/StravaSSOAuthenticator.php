<?php

namespace App\Security;

use App\Security\SSOAuthenticator;

class StravaSSOAuthenticator extends SSOAuthenticator
{
    protected const string SERVICE_SSO_NAME  = 'strava';
}
