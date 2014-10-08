<?php

// use Subbly;

class AuthController extends BaseController 
{

  public function askPermission()
  {
    $provider = new League\OAuth2\Client\Provider\Facebook(array(
        'clientId'  =>  '372319239612356',
        'clientSecret'  =>  '8c78a15dfaa0bf16a81191b68ec89638',
        'redirectUri'   =>  'http://www.subbly.dev/auth'
    ));

    if ( ! isset($_GET['code'])) {

        // If we don't have an authorization code then get one
        header('Location: '.$provider->getAuthorizationUrl());
        exit;

    } else {

        // Try to get an access token (using the authorization code grant)
        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // Optional: Now you have a token you can look up a users profile data
        try {

            // We got an access token, let's now get the user's details
            $userDetails = $provider->getUserDetails($token);

            // Use these details to create a new profile
            printf('Hello %s!', $userDetails->firstName);

        } catch (Exception $e) {

            // Failed to get user details
            exit('Oh dear...');
        }

        try
        {
          // Find the user using the user id
          $user = Sentry::findUserByLogin( $userDetails->email );

          // Log the user in
          Sentry::login( $user, false );

          // return Redirect::route('home');
        }
        catch( Cartalyst\Sentry\Users\UserNotFoundException $e )
        {
            // Register the user
            $user = Sentry::register(array(
                'activated'  =>  1
              , 'email'      => $userDetails->email
              , 'password'   => Hash::make( uniqid( time() ) )
              , 'first_name' => $userDetails->firstName
            ));

            // $usergroup = Sentry::getGroupProvider()->findById(2);
            // $user->addGroup($usergroup);

            Sentry::login( $user, false );

            // return Redirect::route('account');
        }

Debugbar::info($userDetails);
Debugbar::info($user);
// exit;
        // Use this to interact with an API on the users behalf
        echo $token->accessToken;

        // Use this to get a new access token if the old one expires
        echo $token->refreshToken;

        // Number of seconds until the access token will expire, and need refreshing
        echo $token->expires;
    }
  }

}
