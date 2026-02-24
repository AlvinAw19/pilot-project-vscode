<?php
declare(strict_types=1);
namespace App\Controller;

use Cake\Cache\Cache;
use Cake\Http\Response;

/**
 * Simple diagnostics controller for session/cache checks.
 * NOTE: This endpoint is temporary and should be removed after debugging.
 */
class DiagnosticsController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Allow this diagnostics action to be called without authentication
        if (isset($this->Authentication)) {
            $this->Authentication->addUnauthenticatedActions(['sessionCheck']);
        }
        $this->Authorization->skipAuthorization();
    }

    public function sessionCheck(): Response
    {
        $this->request->allowMethod(['get']);
        $this->request->allowMethod(['get']);

        $result = [
            'cache_session_configured' => false,
            'cache_write_ok' => false,
            'cache_read_ok' => false,
            'session_cookie_present' => false,
            'session_id' => null,
            'identity' => null,
        ];

        // Check if 'session' cache config exists
        $sessionConfig = Cache::getConfig('session');
        $result['cache_session_configured'] = $sessionConfig !== null;

        // Provide a sanitized summary of the cache config (no secrets)
        if ($sessionConfig) {
            $summary = [];
            if (!empty($sessionConfig['className'])) {
                $summary['engine'] = $sessionConfig['className'];
            }
            if (!empty($sessionConfig['url'])) {
                $parts = parse_url($sessionConfig['url']);
                if ($parts) {
                    $summary['scheme'] = $parts['scheme'] ?? null;
                    $summary['host'] = $parts['host'] ?? null;
                    $summary['port'] = $parts['port'] ?? null;
                }
            }
            $result['cache_config_summary'] = $summary;
        }

        // Try writing/reading a test key using the 'session' cache config
        try {
            if ($result['cache_session_configured']) {
                $ok = Cache::write('diagnostics_test_key', 'ok', 'session');
                $result['cache_write_ok'] = $ok === true;
                $val = Cache::read('diagnostics_test_key', 'session');
                $result['cache_read_ok'] = ($val === 'ok');
                // cleanup
                Cache::delete('diagnostics_test_key', 'session');
            }
        } catch (\Exception $e) {
            $result['cache_error'] = $e->getMessage();
        }

        // Session cookie and id
        $phpSess = $this->request->getCookie('PHPSESSID');
        $result['session_cookie_present'] = !empty($phpSess);
        $result['session_id'] = $phpSess ?: session_id();

        // Identity from Authentication (if present)
        $identity = $this->request->getAttribute('identity');
        if ($identity) {
            $result['identity'] = $identity->getIdentifier();
        }

        $this->response = $this->response->withType('application/json')
            ->withStringBody(json_encode($result, JSON_PRETTY_PRINT));

        return $this->response;
    }
}
