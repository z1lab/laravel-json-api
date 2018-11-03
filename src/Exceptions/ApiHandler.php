<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 20/09/2018
 * Time: 13:54
 */

namespace Z1lab\JsonApi\Exceptions;

use Exception;
use Optimus\Heimdal\ExceptionHandler as BaseHandler;

class ApiHandler extends BaseHandler
{
    /**
     * Fix for error in Optimus\Heimdal\ExceptionHandler thats throws all error as \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR
     * @param \Illuminate\Http\Request $request
     * @param Exception                $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        $e = $this->prepareException($e);

        return parent::render($request, $e);
    }
}
