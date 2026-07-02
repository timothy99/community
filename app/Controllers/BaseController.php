<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        // $this->helpers = ['form', 'url'];

        $this->helpers = array();
        $this->helpers[] = "alert";
        $this->helpers[] = "array";
        $this->helpers[] = "authority";
        $this->helpers[] = "board";
        $this->helpers[] = "config";
        $this->helpers[] = "curl";
        $this->helpers[] = "date";
        $this->helpers[] = "logging";
        $this->helpers[] = "paging";
        $this->helpers[] = "privacy";
        $this->helpers[] = "security";
        $this->helpers[] = "session";
        $this->helpers[] = "text";
        $this->helpers[] = "view";

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // 최초 로딩 시 환경설정 캐시를 미리 워밍해 이후 반복 조회 비용을 줄인다.
        getConfigInfoCached();
        getLanguageListCached(true);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }
}
