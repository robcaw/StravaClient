<?php 

namespace Endurance\Strava;

use Buzz\Browser;
use Buzz\Message\Form\FormRequest;
use Buzz\Message\Form\FormUpload;
use Buzz\Message\Response;
use Buzz\Util\Url;

class StravaClient
{
    protected $browser;
    protected $token;

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function signIn($email, $password)
    {
        $request = new FormRequest(FormRequest::METHOD_POST);

        // Set the request URL
        $url = new Url('https://www.strava.com/api/v2/authentication/login');
        $url->applyToRequest($request);

        // Set the form fields
        $request->setField('email', $email);
        $request->setField('password', $password);

        $response = new Response();
        $this->browser->getClient()->send($request, $response);

        $result = json_decode($response->getContent(), true);

        if (!isset($result['token'])) {
            throw new \RuntimeException('Unable to sign in');
        }

        $this->token = $result['token'];
    }

    public function isSignedIn()
    {
        return $this->token !== null;
    }

    public function uploadActivity($file)
    {
        if (!$this->isSignedIn()) {
            throw new \RuntimeException('Not signed in');
        }

        $request = new FormRequest(FormRequest::METHOD_POST);

        // Set the request URL
        $url = new Url('http://www.strava.com/api/v2/upload');
        $url->applyToRequest($request);

        // Set the form fields
        $request->setField('token', $this->token);
        $request->setField('type', 'fit');
        $request->setField('activity_type', 'ride');

        $data = new FormUpload($file);
        $data->setContentType('image/x-fits');
        $request->setField('data', $data);

        $response = new Response();
        $this->browser->getClient()->send($request, $response);

        return json_decode($response->getContent(), true);
    }
}