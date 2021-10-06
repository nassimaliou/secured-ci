<?php

namespace App\Filters;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Exception;

class JWTAuthenticationFilter implements FilterInterface
{
    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null)
    {
        $authenticationHeader = $request->getServer('HTTP_AUTHORIZATION');

        try {
            helper('jwt');
            $encodedToken = getJWTFromRequest($authenticationHeader);
            validateJWTFromRequest($encodedToken);
            return $request;
        }
        catch (Exception $e) {
            return Services::response()->setJson(
                    [
                        'error' => $e->getMessage()
                    ]
            )->setStatusCode(RequestInterface::HTTP_AUTHORIZATION);
        }
    }

    public function after(RequestInterface $request,ResponseInterface $response,$arguments = null)
    {
    }

}