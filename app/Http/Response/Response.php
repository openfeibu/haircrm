<?php

namespace App\Http\Response;

use Form;
use App\Traits\Http\Request;
use App\Traits\Http\Theme;
use App\Traits\Http\View;

abstract class Response
{
    use View, Request, Theme;
    /**
     * @var store the response data.
     */
    protected $data = null;

    protected $totalRow = null;

    /**
     * @var Response message for the response.
     */
    protected $msg = null;

    /**
     * @var Response status for the response.
     */
    protected $status = null;

    /**
     * @var Response code for the response.
     */
    protected $code = 0;

    /**
     * @var  Url for the redirect response.
     */
    protected $url = null;

    protected $count = 0;

    protected $http_code = 200;

    /**
     * Return the type of response for the current request.
     *
     * @return  string
     */
    protected function getType()
    {

        if ($this->type) {
            return $this->type;
        }

        if (request()->wantsJson()) {
            return 'json';
        }

        if (request()->ajax()) {
            return 'ajax';
        }

        return 'http';

    }

    /**
     * Return json array for  json response.
     *
     * @return json string
     *
     */
    public function json()
    {
        $data = [
            'msg' => $this->getMessage(),
            'status' => $this->getStatus(),
            'code' => $this->getCode(),
            'data' => $this->getData(),
            'count' => $this->getCount(),
            'url'     => $this->getUrl(),
        ];
        $totalRow = $this->getTotalRow();
        if($totalRow)
        {
            $data['totalRow'] = $totalRow;
        }
        return response()->json($data, $this->http_code);
    }

    /**
     * Return view for the ajax response.
     *
     * @return view
     *
     */
    protected function ajax()
    {
        Form::populate($this->getFormData());
        $view = $this->getView();

        if (!is_array($view)) {
            return view($this->getView(), $this->getData());
        }

        return view()->first($this->getView(), $this->getData());
    }

    /**
     * Return  whole page for the http request.
     *
     * @return theme page
     *
     */
    public function http()
    {
        Form::populate($this->getFormData());

        $this->theme->prependTitle($this->getTitle());
        return $this->theme->of($this->getView(), $this->getData())->render();
        /*
        $this->theme->prependTitle($this->getTitle());
        $this->theme->of($this->getView(), $this->getData());
        return response()->view($this->getView(), $this->getData());
        */
    }

    /**
     * Return  whole page for the http request.
     *
     * @return theme page
     *
     */
    public function redirect()
    {
        if ($this->typeIs('json') || $this->typeIs('ajax')) {
            return $this->json();
        }
        return redirect($this->url)
            ->withMessage($this->getMessage())
            ->withStatus($this->getStatus())
            ->withCode($this->getCode());
    }

    public function back()
    {
        if ($this->typeIs('json') || $this->typeIs('ajax')) {
            return $this->json();
        }
        return back()
            ->withMessage($this->getMessage())
            ->withStatus($this->getStatus())
            ->withCode($this->getCode());
    }
    /**
     * Return the output for the current response.
     *
     * @return theme page
     *
     */
    public function output()
    {

        if ($this->typeIs('json')) {
            return $this->json();
        }

        if ($this->typeIs('ajax')) {
            return $this->ajax();
        }

        return $this->http();
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->msg;
    }

    /**
     * @param mixed $msg
     *
     * @return self
     */
    public function message($msg)
    {
        $this->msg = $msg;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return self
     */
    public function status($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->status == 'success' ? 200 : 400;
    }

    public function count($count)
    {
        $this->count = $count ? $count : 0;

        return $this;
    }
    public function getCount()
    {
        return $this->count ? $this->count : 0;
    }
    /**
     * @param mixed $code
     *
     * @return self
     */
    public function code($code)
    {
        $this->code = $code;

        return $this;
    }
    public function success($msg=NULL)
    {
        $this->code = '0';
        $this->status = 'success';
        $this->msg = $msg;
        return $this;
    }
    public function error($msg=NULL,$code=400)
    {
        $this->code = $code;
        $this->status = 'error';
        $this->msg = $msg;
        return $this;
    }
    /**
     * @return  View for the request
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param  View for the request $url
     *
     * @return self
     */
    public function url($url)
    {
        $this->url = $url;

        return $this;
    }

    public function http_code($http_code)
    {
        $this->http_code = $http_code;

        return $this;
    }
    /**
     * @param store the response type $this->getData()
     *
     * @return self
     */
    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    public function totalRow($totalRow)
    {
        $this->totalRow = $totalRow;
        return $this;
    }
    /**
     * @param store the response data $data
     *
     * @return self
     */
    public function getData()
    {
        return is_array($this->data) ? $this->data : [];
    }

    public function getTotalRow()
    {
        return is_array($this->totalRow) ? $this->totalRow : [];
    }
    /**
     * @param store the response data $data
     *
     * @return self
     */
    public function getFormData()
    {

        if (is_array($this->data)) {
            return current($this->data);
        }

        return [];
    }

    /**
     * Return auth guard for the current route.
     *
     * @return type
     *
     */
    protected function getGuard()
    {
        return getenv('guard');
    }

    /**
     * Handle dynamic method calls.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $callable = preg_split('|[A-Z]|', $method);

        if (in_array($callable[0], ['set', 'prepend', 'append', 'has', 'get'])) {
            $value = lcfirst(preg_replace('|^' . $callable[0] . '|', '', $method));
            array_unshift($parameters, $value);
            call_user_func_array([$this->theme, $callable[0]], $parameters);
        }

        return $this;
    }

}
